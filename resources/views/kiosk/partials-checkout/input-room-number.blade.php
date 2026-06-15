<div class="pt-4">
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center">Enter Room Number</h1>
    <p class="text-center text-[#00A0F5] font-semibold uppercase mt-1">Checkout</p>

    <div class="mt-8 bg-gray-50 rounded-xl p-5">
      <label class="block text-sm font-bold text-gray-700 mb-3 uppercase">Your Room Number:</label>
      <input wire:model="room_number" type="text" id="room_number" maxlength="3"
        class="block w-full text-center p-4 text-2xl rounded-full border border-gray-300 focus:border-[#00A0F5] focus:ring-[#00A0F5] uppercase"
        autofocus autocomplete="off" oninput="this.value = this.value.toUpperCase()" />
    </div>

    @if ($room_number)
      <div class="mt-8 flex justify-center">
        <button wire:click="validateRoom" wire:loading.attr="disabled"
          wire:loading.class="opacity-50 cursor-not-allowed"
          class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-12 rounded-full transition-all uppercase shadow-md active:scale-95 flex items-center gap-2">
          <span wire:loading.remove wire:target="validateRoom">NEXT</span>
          <span wire:loading wire:target="validateRoom">LOADING...</span>
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" wire:loading.remove wire:target="validateRoom">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </button>
      </div>
    @endif
  </div>
</div>
