<?php

namespace App\Http\Livewire\Kiosk;

use Livewire\Component;
use App\Models\Type;
use App\Models\Room;
use App\Models\Rate;
use WireUi\Traits\Actions;
use App\Models\Guest;
use App\Models\TemporaryCheckInKiosk;
use App\Models\CheckinDetail;
use App\Services\KioskBatchService;
use Carbon\Carbon;
use App\Jobs\TerminationInKiosk;
use App\Models\StayingHour;
use App\Events\CheckInEvent;
use App\Models\TemporaryReserved;
use DB;

class CheckIn extends Component
{
    use Actions;
    public $steps;
    public $types = [];
    // public $rooms = [];
    public $rates = [];
    public $type_id;
    public $room_id;
    public $rate_id;
    public $longstay;
    public $generatedQrCode;
    public $discount_available = false;
    public $discount_amount = 0;
    public $name, $contact;

    public $discountEnabled = false;
    public $discountType = null;
    public $longstay_preview = 0;
    public $longstay_unit = 'days';
    //check_in details
    public $room_number, $room_type, $room_floor, $room_rate, $room_pay;

    public function render()
    {
        $branchId = auth()->user()->branch_id;

        // Build a map of type_id → assigned room for display on the type
        // selection cards. Each type gets its single batch room shown
        // directly under the type name (room number + floor).
        $typeRoomMap = [];

        $temporaryCheckInKiosk = TemporaryCheckInKiosk::where('branch_id', $branchId)
            ->pluck('room_id')
            ->toArray();

        $temporaryReserved = TemporaryReserved::where('branch_id', $branchId)
            ->pluck('room_id')
            ->toArray();

        $pendingGuestRooms = Guest::where('branch_id', $branchId)
            ->whereDoesntHave('checkInDetail')
            ->where('created_at', '>=', now()->subHours(2))
            ->pluck('room_id')
            ->toArray();

        $ghostRoomIds = CheckinDetail::where('is_check_out', false)
            ->pluck('room_id')
            ->toArray();

        $excludedIds = array_merge($temporaryCheckInKiosk, $temporaryReserved, $pendingGuestRooms, $ghostRoomIds);

        foreach ($this->types as $type) {
            if (KioskBatchService::isEmpty($branchId, $type->id)) {
                KioskBatchService::throwNextBatch($branchId, $type->id);
            }

            KioskBatchService::refreshIfStale($branchId, $type->id);

            $activeRoomIds = KioskBatchService::activeRoomIds($branchId, $type->id);

            $room = Room::where('branch_id', $branchId)
                ->whereIn('id', $activeRoomIds)
                ->whereIn('status', ['Available', 'Cleaned'])
                ->whereNotIn('id', $excludedIds ?: [0])
                ->with('floor')
                ->first();

            $typeRoomMap[$type->id] = $room;
        }

        return view('livewire.kiosk.check-in', [
            'typeRoomMap' => $typeRoomMap,
        ]);
    }

    public function getTypes()
    {
        return $this->types = Type::whereBranchId(
            auth()->user()->branch_id
        )->get();
    }

    public function mount()
    {
        $this->getTypes();
        $this->discountEnabled = false;
        $this->discountType = null;
        $this->discount_amount = 0;

        $this->steps = 1;
    }

    public function selectType($type_id, $room_id)
    {
        if (!$room_id) {
            $this->dispatchBrowserEvent('kiosk-error', [
                'title' => 'SORRY',
                'description' => 'There is no available room in this type.',
            ]);
            return;
        }

        $this->type_id = $type_id;
        $this->selectRoom($room_id);
    }

    public function updatedLongstay()
    {
        $this->rate_id = null;
        $this->calculateLongstayPreview();
    }

    public function updatedLongstayUnit()
    {
        $this->calculateLongstayPreview();
    }

    private function calculateLongstayPreview()
    {
        if ($this->longstay && $this->longstay >= 1 && $this->type_id) {
            $maxAmount = Rate::where('branch_id', auth()->user()->branch_id)
                ->where('type_id', $this->type_id)
                ->max('amount');
            $days = $this->longstay_unit === 'months' ? $this->longstay * 30 : $this->longstay;
            $this->longstay_preview = ($maxAmount ?? 0) * $days;
        } else {
            $this->longstay_preview = 0;
        }
    }

    public function selectRoom($room_id)
    {
        $this->room_id = $room_id;
        $this->rates = Rate::whereBranchId(auth()->user()->branch_id)
            ->whereTypeId($this->type_id)
            ->with(['stayingHour'])
            ->get();
    }

    public function selectRate($rate_id)
    {
        $this->rate_id = $rate_id;
        $this->longstay = null;
    }

    public function proceedFillUp()
    {
        $this->room_number = Room::where('id', $this->room_id)->first()->number;
        $this->room_floor = Room::where('id', $this->room_id)
            ->first()
            ->floor->numberWithFormat();
        $this->room_type = Type::where('id', $this->type_id)->first()->name;

        if ($this->longstay == null) {
            $this->room_rate = Rate::where(
                'id',
                $this->rate_id
            )->first()->stayingHour->number;

            $this->room_pay = Rate::where(
                'id',
                $this->rate_id
            )->first()->amount;
            $rate = Rate::where('id', $this->rate_id)->first();
            if ($rate->has_discount && auth()->user()->branch->discount_enabled) {
                $this->discount_available = true;
            }

            $this->steps = 4;
        } else {
            $maxValue = $this->longstay_unit === 'months' ? 12 : 31;
            $this->validate([
                'longstay' => 'required|integer|min:1|max:' . $maxValue,
            ],[
                'longstay.required' => 'Please enter a value.',
                'longstay.integer' => 'Must be a whole number.',
                'longstay.min' => 'Must be at least 1.',
                'longstay.max' => 'Must not exceed ' . $maxValue . '.',
            ]);

            // Convert months to days for calculation
            $totalDays = $this->longstay_unit === 'months' ? $this->longstay * 30 : $this->longstay;

            $long = StayingHour::where('branch_id', auth()->user()->branch_id)
                ->where('number', 24)
                ->first();

            $rate_exist = Rate::where('branch_id', auth()->user()->branch_id)
                ->where('type_id', $this->type_id)
                ->where('staying_hour_id', $long->id)
                ->exists();

            if ($rate_exist) {
                $this->room_rate =
                    Rate::where('branch_id', auth()->user()->branch_id)
                        ->where('type_id', $this->type_id)
                        ->max('amount') * $totalDays;

                $this->rate_id = Rate::where(
                    'branch_id',
                    auth()->user()->branch_id
                )
                    ->where('type_id', $this->type_id)
                    ->where('staying_hour_id', $long->id)
                    ->first()->id;

                $this->room_pay = $this->room_rate;
                $this->steps = 4;
            } else {
                $this->dispatchBrowserEvent('kiosk-error', [
                    'title' => 'SORRY',
                    'description' => 'Long Stay rate is not set yet. Please contact the administrator.',
                ]);
            }
        }
    }

    public function confirmTransaction()
    {
        if ($this->contact == null) {
            $this->validate([
                'name' => 'required|min:3',
            ]);
        } else {
            $this->validate([
                'name' => 'required|min:3',
                'contact' => 'required|min:9|max:9',
            ]);
        }

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Confirm the Transaction?',
            'icon' => 'question',
            'accept' => [
                'label' => 'Yes, Confirm it',
                'method' => 'confirmCheckIn',
            ],
            'reject' => [
                'label' => 'No, cancel',
            ],
        ]);
    }

    public function confirmCheckIn()
    {

          DB::beginTransaction();

            try {

                $room = Room::where('branch_id', auth()->user()->branch_id)
                    ->where('id', $this->room_id)
                    ->where('status', 'Occupied')
                    ->with('latestCheckInDetail')
                    ->lockForUpdate()
                    ->first();

                if ($room) {
                    DB::rollBack();
                    $this->dispatchBrowserEvent('kiosk-error', [
                        'title' => 'SORRY',
                        'description' => 'Room is already occupied. Please select another room.',
                    ]);
                    return;
                }

                $temporaryCheckInKiosk = TemporaryCheckInKiosk::where('branch_id', auth()->user()->branch_id)
                    ->where('room_id', $this->room_id)
                    ->lockForUpdate()
                    ->exists();

                $temporaryReserved = TemporaryReserved::where('branch_id', auth()->user()->branch_id)
                    ->where('room_id', $this->room_id)
                    ->lockForUpdate()
                    ->exists();

                if ($temporaryCheckInKiosk || $temporaryReserved) {
                    DB::rollBack();
                    $this->dispatchBrowserEvent('kiosk-error', [
                        'title' => 'SORRY',
                        'description' => 'Room is already reserved. Please select another room.',
                    ]);
                    return;
                }

                // Guard: Block check-in if room has unresolved previous guest
                $openCheckin = CheckinDetail::where('room_id', $this->room_id)
                    ->where('is_check_out', false)
                    ->with('guest:id,name')
                    ->lockForUpdate()
                    ->first();

                if ($openCheckin) {
                    DB::rollBack();
                    $ghostName = $openCheckin->guest->name ?? 'Unknown';
                    $ghostDate = $openCheckin->check_in_at
                        ? Carbon::parse($openCheckin->check_in_at)->format('M d, Y g:i A')
                        : 'unknown date';
                    $this->dispatchBrowserEvent('kiosk-error', [
                        'title' => 'SORRY',
                        'description' => "Room has unresolved previous guest: {$ghostName} (checked in {$ghostDate}). Please contact the front desk.",
                    ]);
                    return;
                }

              $transaction = Guest::whereYear('created_at', now()->year)
                    ->lockForUpdate()
                    ->count();

                $transaction += 1;

                // limit to 4 digits
                $transaction = $transaction % 10000;

                $transaction_code =
                    auth()->user()->branch_id .
                    now()->format('y') .
                    str_pad($transaction, 4, '0', STR_PAD_LEFT);

                // $transaction_code = sprintf('%05d', random_int(0, 99999));

                $this->generatedQrCode = $transaction_code;

                $guest = Guest::create([
                    'branch_id' => auth()->user()->branch_id,
                    'name' => $this->name,
                    'contact' => $this->contact ? '09' . $this->contact : 'N/A',
                    'qr_code' => $transaction_code,
                    'room_id' => $this->room_id,
                    'rate_id' => $this->rate_id,
                    'type_id' => $this->type_id,
                    'static_amount' => $this->room_pay,
                    'is_long_stay' => $this->longstay ? true : false,
                    'number_of_days' => $this->longstay ? ($this->longstay_unit === 'months' ? $this->longstay * 30 : $this->longstay) : 0,
                    'has_discount' => $this->discountEnabled,
                    'discount_amount' => $this->discountEnabled ? $this->discount_amount : 0,
                ]);

                $kioskTimeLimit = auth()->user()->branch->kiosk_time_limit ?? 20;
                TemporaryCheckInKiosk::create([
                    'guest_id' => $guest->id,
                    'room_id' => $this->room_id,
                    'branch_id' => auth()->user()->branch_id,
                    'terminated_at' => now()->addMinutes($kioskTimeLimit),
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // do not silently swallow errors in production
            }

            // Mark this room's batch slot as picked. If this drains the last
            // active slot, the service auto-throws the next batch.
            KioskBatchService::markPicked(auth()->user()->branch_id, $this->room_id);

            // Immediately promote the next available room so the kiosk doesn't
            // go blank while frontdesk processes the current pick.
            $pickedRoom = Room::find($this->room_id);
            if ($pickedRoom) {
                KioskBatchService::maybeFillEmptySlot($pickedRoom);
            }

            $this->steps = 5;
    }

    public function redirectToHome()
    {
        return redirect()->route('kiosk.dashboard');
    }

    public function applyDiscount($type)
    {
        if ($this->discountType === $type) {
            $this->discountType = null;
            $this->discountEnabled = false;
            $this->discount_amount = 0;
        } else {
            $this->discountType = $type;
            $this->discountEnabled = true;
            $this->discount_amount = auth()->user()->branch->discount_amount;
        }
    }

    public function backRoom()
    {
        //
    }
}
