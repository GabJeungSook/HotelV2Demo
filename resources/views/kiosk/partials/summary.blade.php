<div class="pt-4" x-data="{ showSummary: false }">
  {{-- Main Card --}}
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
    {{-- Back button --}}
    <div class="flex justify-start mb-4">
      <button x-on:click="showSummary ? showSummary = false : step = 3"
        class="inline-flex items-center text-[#00A0F5] font-semibold text-sm">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        BACK
      </button>
    </div>

    {{-- === FILL-OUT FORM === --}}
    <div x-show="!showSummary">
      <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center">Fill-Out Information</h1>

      {{-- Guest Info Card --}}
      <div class="mt-6 bg-[#E8F7FF] rounded-xl p-5">
        <h2 class="text-lg font-bold text-gray-800 text-center uppercase">Guest Information</h2>
        <p class="text-sm text-gray-400 text-center mt-1">Please fill-out your details below</p>

        <div class="mt-6 space-y-5">
          {{-- Name --}}
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">
              ENTER YOUR NAME
              <span class="inline-block w-2 h-2 rounded-full bg-[#00A0F5] ml-1 align-middle"></span>
            </label>
            <input type="text" wire:model="name" id="guest_name"
              class="block w-full rounded-lg border-gray-300 px-4 py-3 text-base focus:border-[#00A0F5] focus:ring-[#00A0F5] uppercase">
            @error('name')
              <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
          </div>

          {{-- Contact --}}
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">
              MOBILE NO. <span class="font-normal text-gray-400">(OPTIONAL)</span>
              <span class="inline-block w-2 h-2 rounded-full bg-[#00A0F5] ml-1 align-middle"></span>
            </label>
            <div class="flex items-center">
              <div class="flex items-center justify-center bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg px-3 py-3">
                <svg class="w-5 h-5 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <input type="number" wire:model="contact"
                class="block w-full min-w-0 flex-1 rounded-r-lg border-gray-300 px-4 py-3 text-base focus:border-[#00A0F5] focus:ring-[#00A0F5]"
                placeholder="09123456789">
            </div>
            @error('contact')
              <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
          </div>

          {{-- Discount --}}
          @if($discount_available)
            <div class="mt-4">
              <p class="text-sm font-bold text-gray-700 mb-2 uppercase">Available Discounts</p>
              <div class="space-y-2">
                {{-- PWD --}}
                <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200">
                  <div class="flex items-center space-x-2">
                    <span class="bg-[#00A0F5] text-white text-xs font-bold px-2 py-1 rounded">PWD</span>
                    <p class="text-sm font-semibold text-gray-700">Person with disability</p>
                  </div>
                  <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-gray-700">₱{{ number_format(auth()->user()->branch->discount_amount, 2) }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" wire:click="applyDiscount('pwd')" {{ $discountType === 'pwd' ? 'checked' : '' }} class="sr-only peer">
                      <div class="w-12 h-7 bg-gray-300 rounded-full peer-checked:bg-[#00A0F5] transition-colors duration-300"></div>
                      <div class="absolute top-0.5 left-0.5 w-6 h-6 bg-white rounded-full shadow transition-transform duration-300 peer-checked:translate-x-5"></div>
                    </label>
                  </div>
                </div>
                {{-- Senior Citizen --}}
                <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200">
                  <div class="flex items-center space-x-2">
                    <span class="bg-[#00A0F5] text-white text-xs font-bold px-2 py-1 rounded">SC</span>
                    <p class="text-sm font-semibold text-gray-700">Senior Citizen</p>
                  </div>
                  <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-gray-700">₱{{ number_format(auth()->user()->branch->discount_amount, 2) }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" wire:click="applyDiscount('senior')" {{ $discountType === 'senior' ? 'checked' : '' }} class="sr-only peer">
                      <div class="w-12 h-7 bg-gray-300 rounded-full peer-checked:bg-[#00A0F5] transition-colors duration-300"></div>
                      <div class="absolute top-0.5 left-0.5 w-6 h-6 bg-white rounded-full shadow transition-transform duration-300 peer-checked:translate-x-5"></div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Next Button --}}
      @if ($name)
        <div class="mt-8 flex justify-center">
          <button @click="showSummary = true"
            class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-16 rounded-full transition-all uppercase shadow-md active:scale-95">
            NEXT
          </button>
        </div>
      @endif
    </div>

    {{-- === CHECK-IN SUMMARY === --}}
    <div x-show="showSummary" x-cloak>
      <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center">Check-In Details</h1>

      {{-- Guest Info Header --}}
      <div class="mt-6 flex items-center space-x-3">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
          <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
        </div>
        <div>
          <p class="text-lg font-bold text-gray-800 uppercase">{{ $name }}</p>
          <p class="text-sm text-gray-400">{{ $contact ? '09' . $contact : '' }}</p>
        </div>
      </div>

      {{-- Details --}}
      <div class="mt-6 space-y-3 text-sm">
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>TRANSACTION:</span>
          </span>
          <span class="font-semibold text-gray-700">CHECK-IN</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>TERMS & CONDITIONS</span>
          </span>
          <span class="font-semibold text-gray-700">AGREED</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>ROOM TYPE</span>
          </span>
          <span class="font-semibold text-gray-700 uppercase">{{ $room_type }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>ROOM #</span>
          </span>
          <span class="font-semibold text-gray-700">{{ $room_number }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>STAYING HOURS</span>
          </span>
          <span class="font-semibold text-gray-700">
            @if ($longstay != null)
              {{ $longstay }}D
            @else
              {{ $room_rate }}H
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
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>ROOM AMOUNT</span>
          </span>
          <span class="font-semibold text-gray-700">P{{ number_format($room_pay, 2) }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 text-gray-500 font-semibold">
            <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            <span>+ KEY & REMOTE DEPOSIT <span class="text-gray-400">(REFUNDABLE)</span></span>
          </span>
          <span class="font-semibold text-gray-700">+P200.00</span>
        </div>
        @if ($discount_amount > 0)
          <div class="flex justify-between items-center">
            <span class="flex items-center space-x-2 text-gray-500 font-semibold">
              <svg class="w-4 h-4 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
              <span>DISCOUNT <span class="text-[#00A0F5]">({{ $discountType === 'senior' ? 'SC' : 'PWD' }})</span></span>
            </span>
            <span class="font-semibold text-red-500">-P{{ number_format($discount_amount, 2) }}</span>
          </div>
        @endif
      </div>

      {{-- Total --}}
      <div class="border-t border-gray-200 mt-4 pt-3">
        <div class="flex justify-between items-center">
          <span class="flex items-center space-x-2 font-bold text-gray-700">
            <svg class="w-5 h-5 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>TOTAL CHARGE</span>
          </span>
          <span class="font-extrabold text-xl text-[#00A0F5]">P{{ number_format(($room_pay + 200) - $discount_amount, 2) }}</span>
        </div>
      </div>

      {{-- Confirm Button --}}
      <div class="mt-8 flex justify-center">
        <button wire:click="confirmTransaction"
          wire:loading.attr="disabled"
          wire:loading.class="opacity-50 cursor-not-allowed"
          class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-16 rounded-full transition-all uppercase shadow-md active:scale-95">
          <span wire:loading.remove wire:target="confirmTransaction">NEXT</span>
          <span wire:loading wire:target="confirmTransaction">PROCESSING...</span>
        </button>
      </div>
    </div>
  </div>
</div>
