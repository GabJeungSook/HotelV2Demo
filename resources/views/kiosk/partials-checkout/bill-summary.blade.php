<div class="pt-4">
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
    {{-- Back button --}}
    <div class="flex justify-start mb-4">
      <button x-on:click="step--" wire:click="backToQR" class="inline-flex items-center text-[#00A0F5] font-semibold text-sm">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        BACK
      </button>
    </div>

    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center">Bill Summary</h1>

    {{-- Guest Info Header --}}
    <div class="mt-6 flex items-center space-x-3">
      <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
        <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
      </div>
      <div>
        <p class="text-lg font-bold text-gray-800 uppercase">{{ $guest->name ?? 'N/A' }}</p>
        <p class="text-sm text-gray-400">{{ $guest->contact ?? '' }}</p>
      </div>
    </div>

    {{-- Details --}}
    <div class="mt-6 space-y-3 text-sm">
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>TRANSACTION:</span>
        </span>
        <span class="font-semibold text-gray-700">CHECKOUT</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          <span>ROOM #</span>
        </span>
        <span class="font-semibold text-gray-700">{{ $checkInDetail->room->number ?? 'N/A' }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>CHECK-IN TIME</span>
        </span>
        <span class="font-semibold text-gray-700">{{ $checkInDetail ? \Carbon\Carbon::parse($checkInDetail->check_in_at)->format('M d, Y g:i A') : 'N/A' }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          <span>INITIAL TIME</span>
        </span>
        <span class="font-semibold text-gray-700">
          @if($checkInDetail)
            @if($checkInDetail->guest->is_long_stay)
              {{ $checkInDetail->guest->number_of_days }}D
            @else
              {{ $checkInDetail->hours_stayed }}H
            @endif
          @else
            N/A
          @endif
        </span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>TOTAL EXTENSION HOURS</span>
        </span>
        <span class="font-semibold text-gray-700">{{ $checkInDetail ? $extension_hours : 0 }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>TOTAL STAYING HOURS</span>
        </span>
        <span class="font-semibold text-gray-700">
          @if($checkInDetail)
            @if($checkInDetail->guest->is_long_stay)
              {{ ($checkInDetail->hours_stayed * $checkInDetail->guest->number_of_days) + $extension_hours }}H
            @else
              {{ $checkInDetail->hours_stayed + $extension_hours }}H
            @endif
          @else
            N/A
          @endif
        </span>
      </div>
    </div>

    {{-- Separator --}}
    <div class="border-t border-gray-200 my-4"></div>

    {{-- Amount Breakdown --}}
    <div class="space-y-3 text-sm">
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          <span>TOTAL DEPOSIT</span>
        </span>
        <span class="font-semibold text-gray-700">P{{ $checkInDetail ? number_format($total_deposit, 2) : '0.00' }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>ROOM AMOUNT</span>
        </span>
        <span class="font-semibold text-gray-700">P{{ $checkInDetail ? number_format($room_amount, 2) : '0.00' }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 text-gray-500 font-semibold">
          <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          <span>OTHER TRANSACTIONS</span>
        </span>
        <span class="font-semibold text-gray-700">P{{ $checkInDetail ? number_format($total_amount, 2) : '0.00' }}</span>
      </div>
    </div>

    {{-- Total --}}
    <div class="border-t border-gray-200 mt-4 pt-3">
      <div class="flex justify-between items-center">
        <span class="flex items-center space-x-2 font-bold text-gray-700">
          <svg class="w-5 h-5 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
          <span>TOTAL AMOUNT</span>
        </span>
        <span class="font-extrabold text-xl text-[#00A0F5]">P{{ $checkInDetail ? number_format(($room_amount + $total_amount), 2) : '0.00' }}</span>
      </div>
    </div>

    {{-- Confirm Button --}}
    <div class="mt-8 flex justify-center">
      <button
        x-on:confirm="{
          title: 'Confirm Check-Out',
          description: 'Are you sure you want to check-out? Please make sure all the details are correct before confirming.',
          icon: 'warning',
          method: 'confirmCheckOut',
        }"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
        class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-12 rounded-full transition-all uppercase shadow-md active:scale-95 flex items-center gap-2">
        <span wire:loading.remove wire:target="confirmCheckOut">CONFIRM CHECKOUT</span>
        <span wire:loading wire:target="confirmCheckOut">PROCESSING...</span>
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" wire:loading.remove wire:target="confirmCheckOut">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </button>
    </div>
  </div>
</div>
