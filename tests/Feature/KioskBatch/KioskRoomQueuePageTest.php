<?php

namespace Tests\Feature\KioskBatch;

use App\Http\Livewire\Frontdesk\Monitoring\KioskRoomQueue;
use App\Models\Branch;
use App\Models\Floor;
use App\Models\Rate;
use App\Models\Room;
use App\Models\StayingHour;
use App\Models\Type;
use App\Models\User;
use App\Services\KioskBatchService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class KioskRoomQueuePageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function frontdesk_user_can_view_the_kiosk_room_queue_page()
    {
        [$branch, $type] = $this->seedBranchWithFloorsAndRooms(floors: 2, roomsPerFloor: 4);
        KioskBatchService::throwNextBatch($branch->id, $type->id);

        $user = $this->makeFrontdeskUser($branch);

        $this->actingAs($user)
            ->get(route('frontdesk.kiosk-room-queue'))
            ->assertOk()
            ->assertSeeText('Kiosk Room Queue');
    }

    /** @test */
    public function queue_page_shows_now_next_and_after_sections_per_type()
    {
        [$branch, $type] = $this->seedBranchWithFloorsAndRooms(floors: 2, roomsPerFloor: 4);
        KioskBatchService::throwNextBatch($branch->id, $type->id);

        $user = $this->makeFrontdeskUser($branch);

        Livewire::actingAs($user)
            ->test(KioskRoomQueue::class)
            ->assertSee('Test Type')
            ->assertSee('total available')
            ->assertSee('on kiosk')
            ->assertSee('in queue')
            ->assertSee('round-robin cycle')
            // Highest-priority room is thrown to the kiosk (NOW)
            ->assertSee('1001')
            // Remaining rooms appear in NEXT / AFTER
            ->assertSee('2001')
            ->assertSee('1002')
            ->assertSee('2002');
    }

    /** @test */
    public function cleaned_rooms_are_flagged_least_priority_in_the_queue()
    {
        [$branch, $type] = $this->seedBranchWithFloorsAndRooms(floors: 1, roomsPerFloor: 8);
        KioskBatchService::throwNextBatch($branch->id, $type->id);

        // Push one queued room to Cleaned so it lands in AFTER as least priority.
        Room::where('branch_id', $branch->id)->where('number', '1008')->update(['status' => 'Cleaned']);

        $user = $this->makeFrontdeskUser($branch);

        Livewire::actingAs($user)
            ->test(KioskRoomQueue::class)
            ->assertSee('least priority, enters the queue last');
    }

    private function makeFrontdeskUser(Branch $branch): User
    {
        $user = User::create([
            'name' => 'Queue Tester ' . uniqid(),
            'email' => uniqid() . '@test.local',
            'password' => bcrypt('secret-test-pass'),
            'branch_id' => $branch->id,
            'branch_name' => $branch->name,
            'cash_drawer_id' => 1,
        ]);
        $user->assignRole('frontdesk');

        return $user;
    }

    private function seedBranchWithFloorsAndRooms(int $floors, int $roomsPerFloor): array
    {
        $branch = Branch::create([
            'name' => 'Queue Test Branch ' . uniqid(),
            'kiosk_time_limit' => 10,
        ]);

        $type = Type::create([
            'branch_id' => $branch->id,
            'name' => 'Test Type',
        ]);

        $stayingHour = StayingHour::create([
            'branch_id' => $branch->id,
            'number' => 12,
        ]);

        Rate::create([
            'branch_id' => $branch->id,
            'type_id' => $type->id,
            'staying_hour_id' => $stayingHour->id,
            'amount' => 300,
        ]);

        for ($f = 1; $f <= $floors; $f++) {
            $floor = Floor::create([
                'branch_id' => $branch->id,
                'number' => $f,
            ]);

            for ($r = 1; $r <= $roomsPerFloor; $r++) {
                Room::create([
                    'branch_id' => $branch->id,
                    'floor_id' => $floor->id,
                    'type_id' => $type->id,
                    'number' => (string) (($f * 1000) + $r),
                    'status' => 'Available',
                    'is_priority' => true,
                ]);
            }
        }

        return [$branch, $type];
    }
}
