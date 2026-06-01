<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\CheckinDetail;
use App\Models\CheckOutGuestReport;
use App\Models\Frontdesk;
use App\Models\Guest;
use App\Models\NewGuestReport;
use App\Models\Rate;
use App\Models\Room;
use App\Models\RoomBoyReport;
use App\Models\ShiftLog;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoTransactionSeeder extends Seeder
{
    public function run()
    {
        $branch = Branch::first();
        $frontdeskUser = User::where('email', 'demo-frontdesk@gmail.com')->first();
        $frontdesk = Frontdesk::where('user_id', $frontdeskUser->id)->first();
        $roomboyUser = User::where('email', 'demo-roomboy@gmail.com')->first();

        // Get available rooms grouped by type
        $rooms = Room::where('branch_id', $branch->id)->get();
        $rates = Rate::where('branch_id', $branch->id)->get();

        // Create 2 shift logs spanning the demo period
        $shiftLog1 = ShiftLog::create([
            'branch_id' => $branch->id,
            'frontdesk_id' => $frontdeskUser->id,
            'cash_drawer_id' => 1,
            'beginning_cash' => 1.00,
            'end_cash' => 45000.00,
            'total_expenses' => 0,
            'total_remittances' => 0,
            'total_pos' => 0,
            'time_in' => Carbon::now()->subDays(3)->setHour(8)->setMinute(0),
            'time_out' => Carbon::now()->subDays(3)->setHour(20)->setMinute(0),
            'frontdesk_ids' => json_encode([$frontdesk->id, 'N/A']),
            'shift' => 'AM',
        ]);

        $shiftLog2 = ShiftLog::create([
            'branch_id' => $branch->id,
            'frontdesk_id' => $frontdeskUser->id,
            'cash_drawer_id' => 1,
            'beginning_cash' => 45000.00,
            'end_cash' => 85000.00,
            'total_expenses' => 0,
            'total_remittances' => 0,
            'total_pos' => 0,
            'time_in' => Carbon::now()->subDays(2)->setHour(8)->setMinute(0),
            'time_out' => Carbon::now()->subDays(2)->setHour(20)->setMinute(0),
            'frontdesk_ids' => json_encode([$frontdesk->id, 'N/A']),
            'shift' => 'AM',
        ]);

        $shiftLog3 = ShiftLog::create([
            'branch_id' => $branch->id,
            'frontdesk_id' => $frontdeskUser->id,
            'cash_drawer_id' => 1,
            'beginning_cash' => 85000.00,
            'end_cash' => 125000.00,
            'total_expenses' => 0,
            'total_remittances' => 0,
            'total_pos' => 0,
            'time_in' => Carbon::now()->subDays(1)->setHour(8)->setMinute(0),
            'time_out' => Carbon::now()->subDays(1)->setHour(20)->setMinute(0),
            'frontdesk_ids' => json_encode([$frontdesk->id, 'N/A']),
            'shift' => 'AM',
        ]);

        $assignedFrontdeskJson = json_encode([$frontdesk->id, 'N/A']);

        // Guest data - realistic Filipino names with varying room types and stay durations
        $guestData = [
            ['name' => 'Juan Dela Cruz',      'contact' => '09171234567', 'type_id' => 1, 'hours' => 6,  'days_ago' => 3, 'hour_start' => 8,  'shift_log' => $shiftLog1],
            ['name' => 'Maria Santos',         'contact' => '09181234568', 'type_id' => 2, 'hours' => 12, 'days_ago' => 3, 'hour_start' => 9,  'shift_log' => $shiftLog1],
            ['name' => 'Pedro Reyes',          'contact' => 'N/A',        'type_id' => 3, 'hours' => 24, 'days_ago' => 3, 'hour_start' => 10, 'shift_log' => $shiftLog1],
            ['name' => 'Ana Garcia',           'contact' => '09191234570', 'type_id' => 1, 'hours' => 12, 'days_ago' => 3, 'hour_start' => 11, 'shift_log' => $shiftLog1],
            ['name' => 'Roberto Fernandez',    'contact' => 'N/A',        'type_id' => 2, 'hours' => 6,  'days_ago' => 3, 'hour_start' => 14, 'shift_log' => $shiftLog1],
            ['name' => 'Elena Mendoza',        'contact' => '09201234572', 'type_id' => 1, 'hours' => 12, 'days_ago' => 2, 'hour_start' => 8,  'shift_log' => $shiftLog2],
            ['name' => 'Carlos Villanueva',    'contact' => 'N/A',        'type_id' => 3, 'hours' => 6,  'days_ago' => 2, 'hour_start' => 9,  'shift_log' => $shiftLog2],
            ['name' => 'Sofia Aquino',         'contact' => '09211234574', 'type_id' => 2, 'hours' => 12, 'days_ago' => 2, 'hour_start' => 10, 'shift_log' => $shiftLog2],
            ['name' => 'Miguel Ramos',         'contact' => 'N/A',        'type_id' => 1, 'hours' => 24, 'days_ago' => 2, 'hour_start' => 12, 'shift_log' => $shiftLog2],
            ['name' => 'Isabella Torres',      'contact' => '09221234576', 'type_id' => 2, 'hours' => 6,  'days_ago' => 2, 'hour_start' => 15, 'shift_log' => $shiftLog2],
            ['name' => 'Gabriel Lim',          'contact' => 'N/A',        'type_id' => 3, 'hours' => 12, 'days_ago' => 1, 'hour_start' => 8,  'shift_log' => $shiftLog3],
            ['name' => 'Angelica Bautista',    'contact' => '09231234578', 'type_id' => 1, 'hours' => 6,  'days_ago' => 1, 'hour_start' => 9,  'shift_log' => $shiftLog3],
            ['name' => 'Rafael Soriano',       'contact' => 'N/A',        'type_id' => 2, 'hours' => 12, 'days_ago' => 1, 'hour_start' => 11, 'shift_log' => $shiftLog3],
            ['name' => 'Patricia Navarro',     'contact' => '09241234580', 'type_id' => 1, 'hours' => 12, 'days_ago' => 1, 'hour_start' => 13, 'shift_log' => $shiftLog3],
            ['name' => 'Andres Magbanua',      'contact' => 'N/A',        'type_id' => 3, 'hours' => 24, 'days_ago' => 1, 'hour_start' => 14, 'shift_log' => $shiftLog3],
        ];

        // Map staying hours to staying_hour_id: 6h=1, 12h=2, 24h=4 (based on AlmaResidenceSeeder)
        $stayingHourMap = [6 => 1, 12 => 2, 24 => 4];

        $roomIndex = 0;

        foreach ($guestData as $i => $data) {
            $qrCode = '1260' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

            // Pick a room of the matching type
            $room = $rooms->where('type_id', $data['type_id'])->values()->get($roomIndex % $rooms->where('type_id', $data['type_id'])->count());
            $roomIndex++;

            // Find the matching rate
            $stayingHourId = $stayingHourMap[$data['hours']];
            $rate = $rates->where('type_id', $data['type_id'])->where('staying_hour_id', $stayingHourId)->first();

            $rateAmount = $rate->amount;
            $deposit = 200;
            $staticAmount = $rateAmount + $deposit;
            $paidAmount = $staticAmount;
            $excessAmount = ($i % 3 === 0) ? rand(3, 20) : 0; // Every 3rd guest pays excess
            if ($excessAmount > 0) {
                $paidAmount = $staticAmount + $excessAmount;
            }

            $checkInAt = Carbon::now()->subDays($data['days_ago'])->setHour($data['hour_start'])->setMinute(rand(0, 30))->setSecond(0);
            $checkOutAt = (clone $checkInAt)->addHours($data['hours'])->addMinutes(rand(5, 30));
            $shift = $data['hour_start'] < 12 ? 'AM' : 'PM';
            $shiftDate = $checkInAt->format('F j, Y');

            // Create Guest
            $guest = Guest::create([
                'branch_id' => $branch->id,
                'name' => $data['name'],
                'contact' => $data['contact'],
                'qr_code' => $qrCode,
                'room_id' => $room->id,
                'rate_id' => $rate->id,
                'type_id' => $data['type_id'],
                'static_amount' => $staticAmount,
                'is_long_stay' => 0,
                'number_of_days' => 0,
                'has_discount' => 0,
                'has_kiosk_check_out' => 0,
                'is_co' => 0,
                'created_at' => $checkInAt,
                'updated_at' => $checkOutAt,
            ]);

            // Create CheckinDetail
            $checkinDetail = CheckinDetail::create([
                'guest_id' => $guest->id,
                'frontdesk_id' => $frontdesk->id,
                'type_id' => $data['type_id'],
                'room_id' => $room->id,
                'rate_id' => $rate->id,
                'static_room_amount' => $rateAmount,
                'static_amount' => $staticAmount,
                'hours_stayed' => $data['hours'],
                'total_deposit' => $deposit + $excessAmount,
                'total_deduction' => 0,
                'check_in_at' => $checkInAt,
                'check_out_at' => $checkOutAt,
                'is_check_out' => 1,
                'is_long_stay' => 0,
                'number_of_hours' => $data['hours'],
                'next_extension_is_original' => 0,
                'created_at' => $checkInAt,
                'updated_at' => $checkOutAt,
            ]);

            // Transaction 1: Check In
            Transaction::create([
                'branch_id' => $branch->id,
                'shift_log_id' => $data['shift_log']->id,
                'checkin_detail_id' => $checkinDetail->id,
                'cash_drawer_id' => 1,
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'floor_id' => $room->floor_id,
                'transaction_type_id' => 1, // Check In
                'assigned_frontdesk_id' => $assignedFrontdeskJson,
                'description' => 'Guest Check In',
                'payable_amount' => $rateAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => 0,
                'deposit_amount' => 0,
                'paid_at' => $checkInAt,
                'remarks' => 'Guest Checked In at room #' . $room->number,
                'is_co' => 0,
                'shift' => $shift,
                'is_override' => 0,
                'created_at' => $checkInAt,
                'updated_at' => $checkInAt,
            ]);

            // Transaction 2: Deposit (Room Key & TV Remote)
            Transaction::create([
                'branch_id' => $branch->id,
                'shift_log_id' => $data['shift_log']->id,
                'checkin_detail_id' => $checkinDetail->id,
                'cash_drawer_id' => 1,
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'floor_id' => $room->floor_id,
                'transaction_type_id' => 2, // Deposit
                'assigned_frontdesk_id' => $assignedFrontdeskJson,
                'description' => 'Deposit',
                'payable_amount' => $deposit,
                'paid_amount' => $paidAmount,
                'change_amount' => 0,
                'deposit_amount' => $deposit,
                'paid_at' => $checkInAt,
                'remarks' => 'Deposit From Check In (Room Key & TV Remote)',
                'is_co' => 0,
                'shift' => $shift,
                'is_override' => 0,
                'created_at' => $checkInAt,
                'updated_at' => $checkInAt,
            ]);

            // Transaction 3: Excess deposit (for some guests)
            if ($excessAmount > 0) {
                Transaction::create([
                    'branch_id' => $branch->id,
                    'shift_log_id' => $data['shift_log']->id,
                    'checkin_detail_id' => $checkinDetail->id,
                    'cash_drawer_id' => 1,
                    'room_id' => $room->id,
                    'guest_id' => $guest->id,
                    'floor_id' => $room->floor_id,
                    'transaction_type_id' => 2, // Deposit
                    'assigned_frontdesk_id' => $assignedFrontdeskJson,
                    'description' => 'Deposit',
                    'payable_amount' => $excessAmount,
                    'paid_amount' => $paidAmount,
                    'change_amount' => 0,
                    'deposit_amount' => $excessAmount,
                    'paid_at' => $checkInAt,
                    'remarks' => 'Deposit From Check In (Excess Amount)',
                    'is_co' => 0,
                    'shift' => $shift,
                    'is_override' => 0,
                    'created_at' => $checkInAt,
                    'updated_at' => $checkInAt,
                ]);
            }

            // Extension transactions for guests 2, 7, 12 (indices 1, 6, 11)
            if (in_array($i, [1, 6, 11])) {
                $extendAt = (clone $checkInAt)->addHours($data['hours'])->subHour();
                Transaction::create([
                    'branch_id' => $branch->id,
                    'shift_log_id' => $data['shift_log']->id,
                    'checkin_detail_id' => $checkinDetail->id,
                    'cash_drawer_id' => 1,
                    'room_id' => $room->id,
                    'guest_id' => $guest->id,
                    'floor_id' => $room->floor_id,
                    'transaction_type_id' => 6, // Extend
                    'assigned_frontdesk_id' => $assignedFrontdeskJson,
                    'description' => 'Extend',
                    'payable_amount' => 112, // 6hr extension rate
                    'paid_amount' => 112,
                    'change_amount' => 0,
                    'deposit_amount' => 0,
                    'paid_at' => $extendAt,
                    'remarks' => 'Extended stay by 6 hours',
                    'is_co' => 0,
                    'shift' => $shift,
                    'is_override' => 0,
                    'created_at' => $extendAt,
                    'updated_at' => $extendAt,
                ]);
            }

            // Cashout transaction for guests 4, 9 (indices 3, 8)
            if (in_array($i, [3, 8])) {
                $cashoutAt = (clone $checkOutAt)->subMinutes(5);
                Transaction::create([
                    'branch_id' => $branch->id,
                    'shift_log_id' => $data['shift_log']->id,
                    'checkin_detail_id' => $checkinDetail->id,
                    'cash_drawer_id' => 1,
                    'room_id' => $room->id,
                    'guest_id' => $guest->id,
                    'floor_id' => $room->floor_id,
                    'transaction_type_id' => 5, // Cashout
                    'assigned_frontdesk_id' => $assignedFrontdeskJson,
                    'description' => 'Cashout',
                    'payable_amount' => 0,
                    'paid_amount' => 0,
                    'change_amount' => $deposit,
                    'deposit_amount' => 0,
                    'paid_at' => $cashoutAt,
                    'remarks' => 'Guest Cashout - Deposit Returned',
                    'is_co' => 0,
                    'shift' => $shift,
                    'is_override' => 0,
                    'created_at' => $cashoutAt,
                    'updated_at' => $cashoutAt,
                ]);
            }

            // New Guest Report
            NewGuestReport::create([
                'branch_id' => $branch->id,
                'room_id' => $room->id,
                'checkin_details_id' => $checkinDetail->id,
                'shift_date' => $shiftDate,
                'shift' => $shift,
                'frontdesk_id' => $frontdesk->id,
                'partner_name' => 'N/A',
                'created_at' => $checkInAt,
                'updated_at' => $checkInAt,
            ]);

            // Check Out Guest Report
            CheckOutGuestReport::create([
                'room_id' => $room->id,
                'checkin_details_id' => $checkinDetail->id,
                'shift_date' => $checkOutAt->format('F j, Y'),
                'shift' => $checkOutAt->hour < 12 ? 'AM' : 'PM',
                'frontdesk_id' => $frontdesk->id,
                'partner_name' => 'N/A',
                'created_at' => $checkOutAt,
                'updated_at' => $checkOutAt,
            ]);

            // Room Boy Report
            $cleaningStart = (clone $checkOutAt)->addMinutes(rand(3, 10));
            $cleaningEnd = (clone $cleaningStart)->addMinutes(rand(12, 25));
            $totalMinutes = $cleaningStart->diffInMinutes($cleaningEnd);

            RoomBoyReport::create([
                'branch_id' => $branch->id,
                'room_id' => $room->id,
                'checkin_details_id' => $checkinDetail->id,
                'roomboy_id' => $roomboyUser->id,
                'cleaning_start' => $cleaningStart,
                'cleaning_end' => $cleaningEnd,
                'total_hours_spent' => $totalMinutes,
                'interval' => ($i === 0) ? 0 : rand(0, 45),
                'shift' => $checkOutAt->hour < 12 ? 'AM' : 'PM',
                'is_cleaned' => 1,
                'created_at' => $cleaningStart,
                'updated_at' => $cleaningEnd,
            ]);
        }
    }
}
