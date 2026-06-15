<div class="pt-4">
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8 text-center">

    {{-- Complete Header --}}
    <p class="text-lg font-bold text-gray-700 uppercase tracking-wide">Complete!</p>
    <div class="flex justify-center mt-3">
      <div class="w-16 h-16 bg-[#D6EEFB] rounded-full flex items-center justify-center">
        <svg class="w-10 h-10 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    {{-- Instruction --}}
    <p class="mt-6 text-base font-bold text-gray-700 uppercase leading-snug">
      Please take a picture of this<br>code for checkout purposes
    </p>

    {{-- QR Code --}}
    <div class="mt-6 inline-block border-2 border-[#87CEEB] rounded-xl p-6">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $generatedQrCode }}"
        alt="QR Code: {{ $generatedQrCode }}" class="w-48 h-48 mx-auto">
      <p class="mt-4 text-xl font-bold text-gray-800">{{ $generatedQrCode }}</p>
    </div>

    {{-- Done Button --}}
    <div class="mt-8 flex justify-center">
      <button id="print_qr" wire:click="redirectToHome" data-value="{{ $generatedQrCode }}"
        class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-16 rounded-full transition-all uppercase shadow-md active:scale-95">
        DONE
      </button>
    </div>
  </div>
</div>
