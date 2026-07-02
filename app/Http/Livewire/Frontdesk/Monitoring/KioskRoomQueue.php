<?php

namespace App\Http\Livewire\Frontdesk\Monitoring;

use App\Models\KioskCurrentBatch;
use App\Models\Room;
use App\Models\Type;
use App\Services\KioskBatchService;
use Livewire\Component;

/**
 * Full-page "Kiosk Room Queue" viewer for the frontdesk.
 *
 * Shows, per room type, the round-robin cycle the kiosk follows:
 *   NOW      — rooms currently visible on the kiosk (the active batch)
 *   NEXT     — upcoming rooms in priority order (what the picker will throw)
 *   AFTER    — all remaining queued rooms grouped by floor
 *   CLEANED  — least-priority rooms; they enter the queue last
 *
 * Read-only: all mutation stays in KioskBatchService / the kiosk flow.
 */
class KioskRoomQueue extends Component
{
    /** How many upcoming rooms to surface in the NEXT row. */
    public const NEXT_COUNT = 5;

    public function render()
    {
        $branchId = auth()->user()->branch_id;
        $types = Type::where('branch_id', $branchId)->orderBy('id')->get();

        $queueData = [];
        foreach ($types as $type) {
            $queueData[] = $this->buildTypeBlock($branchId, $type);
        }

        return view('livewire.frontdesk.monitoring.kiosk-room-queue', [
            'queueData' => $queueData,
            'totals' => $this->buildTotals($branchId),
        ]);
    }

    private function buildTypeBlock(int $branchId, Type $type): array
    {
        $now = KioskCurrentBatch::where('branch_id', $branchId)
            ->where('type_id', $type->id)
            ->with(['room:id,number,status', 'floor:id,number'])
            ->get()
            ->sortBy(fn ($b) => $b->floor->number ?? 0)
            ->values()
            ->map(fn ($b) => [
                'room_number' => $b->room->number ?? '?',
                'slot_status' => $b->slot_status,
            ])
            ->toArray();

        // Total available rooms of this type across the whole branch.
        $totalAvailable = Room::where('branch_id', $branchId)
            ->where('type_id', $type->id)
            ->whereIn('status', ['Available', 'Cleaned'])
            ->where('is_priority', 1)
            ->count();

        // The full priority-ordered queue, with the same exclusions the real
        // picker applies (current batch, holds, reservations, ghosts).
        $queue = collect(KioskBatchService::previewBatches($branchId, $type->id, max(1, $totalAvailable)))
            ->filter(fn ($r) => $r['room_number'] !== null)
            ->values();

        $next = $queue->take(self::NEXT_COUNT);
        $after = $queue->slice(self::NEXT_COUNT);

        // Map room number → status so AFTER can color Cleaned rooms as
        // least-priority (they enter the queue last).
        $statusByNumber = Room::where('branch_id', $branchId)
            ->where('type_id', $type->id)
            ->whereIn('number', $queue->pluck('room_number'))
            ->pluck('status', 'number');

        // Group AFTER by floor; Available first, Cleaned last, then natural
        // room-number order — mirroring the queue-entry priority.
        $afterByFloor = $after
            ->groupBy('floor_number')
            ->sortKeys()
            ->map(function ($rooms) use ($statusByNumber) {
                return $rooms
                    ->map(fn ($r) => [
                        'room_number' => $r['room_number'],
                        'cleaned' => ($statusByNumber[$r['room_number']] ?? '') === 'Cleaned',
                    ])
                    ->sortBy([['cleaned', 'asc'], ['room_number', 'asc']])
                    ->values()
                    ->toArray();
            })
            ->toArray();

        $onKiosk = collect($now)->where('slot_status', 'active')->count();

        return [
            'type_name' => $type->name,
            'now' => $now,
            'next' => $next->map(fn ($r) => $r['room_number'])->toArray(),
            'after_by_floor' => $afterByFloor,
            'total_available' => $totalAvailable,
            'on_kiosk' => $onKiosk,
            'in_queue' => $queue->count(),
        ];
    }

    private function buildTotals(int $branchId): array
    {
        $available = Room::where('branch_id', $branchId)
            ->whereIn('status', ['Available', 'Cleaned'])
            ->where('is_priority', 1)
            ->count();
        $occupied = Room::where('branch_id', $branchId)
            ->where('status', 'Occupied')
            ->count();
        $total = Room::where('branch_id', $branchId)->count();

        return [
            'available' => $available,
            'occupied' => $occupied,
            'other' => max(0, $total - $available - $occupied),
            'total' => $total,
        ];
    }
}
