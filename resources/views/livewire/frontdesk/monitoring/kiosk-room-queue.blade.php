<div class="p-4" wire:poll.15s>
  <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-x-3">
        <a href="{{ route('frontdesk.room-monitoring') }}" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="font-bold text-gray-800">Front Desk &mdash; Kiosk Room Queue</h1>
        <span class="text-xs text-gray-400 font-mono">{{ now()->format('D, M j') }} &middot; {{ now()->format('g:i:s A') }}</span>
      </div>
      {{-- Legend --}}
      <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
        <span class="inline-flex items-center rounded-full border border-gray-300 bg-white text-gray-700 px-3 py-1">NOW &mdash; on kiosk</span>
        <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-800 border border-amber-300 px-3 py-1">NEXT &mdash; kiosk batch</span>
        <span class="inline-flex items-center rounded-full bg-emerald-600 text-white px-3 py-1">AFTER &mdash; queued</span>
        <span class="inline-flex items-center rounded-full bg-blue-600 text-white px-3 py-1">CLEANED &mdash; least priority</span>
      </div>
    </div>

    {{-- Branch capacity --}}
    @php
      $tot = max(1, (int) $totals['total']);
      $availPct = round($totals['available'] / $tot * 100);
      $occPct = round($totals['occupied'] / $tot * 100);
      $otherPct = max(0, 100 - $availPct - $occPct);
    @endphp
    <div class="mt-5 flex items-center gap-x-4">
      <div class="text-xs text-gray-500 uppercase tracking-wider whitespace-nowrap">Branch capacity</div>
      <div class="flex-1 flex h-2 overflow-hidden rounded-full bg-gray-100">
        <div class="bg-emerald-500" style="width: {{ $availPct }}%" title="Ready: {{ $totals['available'] }}"></div>
        <div class="bg-rose-500" style="width: {{ $occPct }}%" title="Occupied: {{ $totals['occupied'] }}"></div>
        <div class="bg-gray-300" style="width: {{ $otherPct }}%" title="Other (cleaning/maintenance/reserved): {{ $totals['other'] }}"></div>
      </div>
      <div class="flex items-baseline gap-x-3 text-sm whitespace-nowrap">
        <span><span class="inline-block w-2 h-2 rounded-full bg-emerald-500 align-middle mr-1"></span><span class="font-semibold text-gray-900">{{ $totals['available'] }}</span> <span class="text-gray-500">ready</span></span>
        <span><span class="inline-block w-2 h-2 rounded-full bg-rose-500 align-middle mr-1"></span><span class="font-semibold text-gray-900">{{ $totals['occupied'] }}</span> <span class="text-gray-500">occupied</span></span>
        @if ($totals['other'] > 0)
          <span><span class="inline-block w-2 h-2 rounded-full bg-gray-300 align-middle mr-1"></span><span class="font-semibold text-gray-900">{{ $totals['other'] }}</span> <span class="text-gray-500">other</span></span>
        @endif
        <span class="text-gray-400">&middot;</span>
        <span class="text-gray-500">{{ $totals['total'] }} total</span>
      </div>
    </div>

    {{-- How to read --}}
    <div class="mt-4 rounded-md bg-gray-50 border border-gray-200 p-3 text-xs text-gray-700">
      <span class="font-semibold text-gray-900">How to read:</span>
      <span class="font-bold text-gray-800">NOW</span> is the room currently visible on the kiosk.
      <span class="font-bold text-amber-600">NEXT</span> are the upcoming rooms in priority order.
      <span class="font-bold text-emerald-600">AFTER</span> are all remaining queued rooms grouped by floor.
      <span class="font-bold text-blue-600">CLEANED</span> rooms are lowest priority and enter the queue last.
    </div>

    {{-- Per-type sections --}}
    <div class="mt-4 space-y-4">
      @foreach ($queueData as $block)
        <div class="border border-gray-200 rounded-lg p-4">
          <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-bold text-sm text-gray-800 uppercase">{{ $block['type_name'] }}</h2>
            <div class="text-xs text-gray-600 space-x-2">
              <span><span class="font-semibold text-emerald-700">{{ $block['total_available'] }}</span> total available</span>
              <span class="text-gray-400">&middot;</span>
              <span><span class="font-semibold">{{ $block['on_kiosk'] }}</span> on kiosk</span>
              <span class="text-gray-400">&middot;</span>
              <span><span class="font-semibold">{{ $block['in_queue'] }}</span> in queue</span>
            </div>
          </div>

          {{-- Round-robin cycle indicator --}}
          <div class="mt-3 inline-flex items-center gap-x-2 rounded-md bg-gray-50 border border-gray-200 px-3 py-1.5 text-[10px] font-bold">
            <span class="rounded border border-gray-300 bg-white text-gray-600 px-2 py-0.5">NOW</span>
            <span class="text-gray-400">&rarr;</span>
            <span class="rounded bg-amber-100 text-amber-800 border border-amber-300 px-2 py-0.5">NEXT</span>
            <span class="text-gray-400">&rarr;</span>
            <span class="rounded bg-emerald-600 text-white px-2 py-0.5">AFTER</span>
            <span class="text-gray-400">&rarr;</span>
            <span class="rounded bg-blue-600 text-white px-2 py-0.5">CLEANED</span>
            <span class="italic font-normal text-gray-500 normal-case">round-robin cycle</span>
          </div>

          {{-- NOW --}}
          <div class="mt-3 flex items-center flex-wrap gap-2">
            <span class="rounded bg-gray-800 text-white text-[10px] font-bold px-2 py-1">NOW</span>
            @forelse ($block['now'] as $slot)
              @if ($slot['slot_status'] === 'active')
                <span class="inline-block rounded border-2 border-gray-300 bg-white text-gray-800 text-xs font-bold px-2 py-1">{{ $slot['room_number'] }}</span>
              @else
                <span class="inline-block rounded bg-amber-400 text-white text-xs font-bold px-2 py-1 line-through" title="picked — waiting frontdesk confirmation">{{ $slot['room_number'] }}</span>
              @endif
            @empty
              <span class="text-xs text-gray-400 italic">No rooms on kiosk</span>
            @endforelse
          </div>

          {{-- NEXT --}}
          <div class="mt-2 flex items-center flex-wrap gap-2">
            <span class="rounded bg-amber-100 text-amber-800 border border-amber-300 text-[10px] font-bold px-2 py-1">NEXT</span>
            @forelse ($block['next'] as $roomNumber)
              <span class="inline-block rounded bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold px-2 py-1">{{ $roomNumber }}</span>
            @empty
              <span class="text-xs text-gray-400 italic">No upcoming rooms</span>
            @endforelse
          </div>

          {{-- AFTER — queued rooms grouped by floor --}}
          <div class="mt-4">
            <p class="text-center text-xs font-bold text-gray-500 uppercase tracking-widest">After &mdash; Queued Rooms (Round-Robin)</p>
            @if (empty($block['after_by_floor']))
              <p class="mt-2 text-xs text-gray-400 italic">No queued rooms</p>
            @else
              <div class="mt-2 space-y-1.5">
                @foreach ($block['after_by_floor'] as $floorNumber => $rooms)
                  <div class="flex items-start gap-x-2">
                    <span class="text-[10px] font-semibold text-gray-400 uppercase w-6 pt-1 shrink-0">F{{ $floorNumber }}</span>
                    <div class="flex flex-wrap gap-1">
                      @foreach ($rooms as $room)
                        @if ($room['cleaned'])
                          <span class="inline-block rounded bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5" title="Cleaned — least priority, enters the queue last">{{ $room['room_number'] }}</span>
                        @else
                          <span class="inline-block rounded bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5">{{ $room['room_number'] }}</span>
                        @endif
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
