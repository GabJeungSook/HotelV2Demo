<div class="pt-4" x-data="{ showLongStay: {{ $longstay != null ? 'true' : 'false' }} }">
  {{-- Main Card --}}
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
    {{-- Back button --}}
    <div class="flex justify-end mb-4">
      <button x-on:click="step = 1" class="inline-flex items-center text-[#00A0F5] font-semibold text-sm">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        BACK
      </button>
    </div>

    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center mb-6">Select Rates</h1>

    {{-- Rate Cards --}}
    <div class="space-y-3">
      @foreach ($rates as $index => $rate)
        <button wire:key="{{ $rate->id }}rate" wire:click="selectRate({{ $rate->id }})" type="button" class="w-full transition-all duration-200">
          <div class="rounded-xl border-2 overflow-hidden flex transition-all duration-200 relative
            {{ $rate_id == $rate->id ? 'border-[#00A0F5] shadow-lg shadow-blue-100 ring-2 ring-blue-100' : 'border-gray-200' }}">
            {{-- Left: Hours --}}
            <div class="flex-1 py-4 px-5 text-left bg-white">
              <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Staying Hours:</p>
              <p class="text-2xl font-extrabold text-gray-800">{{ $rate->stayingHour->number }} HOURS</p>
            </div>
            {{-- Right: Rate --}}
            <div class="w-32 py-4 px-4 bg-[#D6EEFB] flex flex-col justify-center items-center">
              <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Rate:</p>
              <p class="text-xl font-extrabold text-gray-800">P{{ number_format($rate->amount, 0) }}</p>
              <p class="text-[8px] text-gray-400 mt-0.5">+P200 REMOTE AND KEY DEPOSIT</p>
            </div>
            {{-- Hot Offer badge on first rate --}}
            @if ($loop->first)
              <div class="absolute bottom-1 left-[40%] transform -translate-x-1/2">
                <span class="text-[10px] font-bold">🔥 HOT OFFER</span>
              </div>
            @endif
          </div>
        </button>
      @endforeach
    </div>

    {{-- Long Stay Button --}}
    <div class="mt-4">
      <button @click="showLongStay = !showLongStay" type="button"
        class="w-full py-3 rounded-xl font-bold text-lg uppercase transition-all
        {{ $longstay != null ? 'bg-[#00A0F5] text-white' : 'bg-[#00A0F5] text-white' }}">
        LONG STAY
      </button>
    </div>

    {{-- Long Stay Input (expandable) --}}
    <div x-show="showLongStay" x-cloak x-transition class="mt-4 space-y-3">
      <div class="flex items-start space-x-3">
        {{-- Days Input --}}
        <div class="flex-shrink-0">
          <input type="number" step="1" min="1" max="31" wire:model.debounce.500ms="longstay"
            class="w-24 h-16 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#00A0F5] focus:ring-[#00A0F5]"
            placeholder="0">
          <div class="flex items-center space-x-3 mt-2">
            <label class="flex items-center space-x-1 cursor-pointer">
              <input type="radio" wire:model="longstay_unit" value="days" class="text-[#00A0F5] focus:ring-[#00A0F5]">
              <span class="text-xs font-semibold text-gray-600">DAYS</span>
            </label>
            <label class="flex items-center space-x-1 cursor-pointer">
              <input type="radio" wire:model="longstay_unit" value="months" class="text-[#00A0F5] focus:ring-[#00A0F5]">
              <span class="text-xs font-semibold text-gray-600">MONTHS</span>
            </label>
          </div>
        </div>
        {{-- Calculated Rate --}}
        @if ($longstay && $longstay_preview > 0)
          <div class="flex-1 py-3 px-4 bg-[#D6EEFB] rounded-lg">
            <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Rate:</p>
            <p class="text-xl font-extrabold text-gray-800">P{{ number_format($longstay_preview, 2) }}</p>
            <p class="text-[8px] text-gray-400 mt-0.5">+P200 REMOTE AND KEY DEPOSIT</p>
          </div>
        @endif
      </div>
      @error('longstay')
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>

    {{-- Next Button --}}
    @if ($rate_id != null || $longstay != null)
      <div class="mt-8 flex justify-center">
        <button wire:click="proceedFillUp"
          class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-16 rounded-full transition-all uppercase shadow-md active:scale-95">
          NEXT
        </button>
      </div>
    @endif
  </div>
</div>
