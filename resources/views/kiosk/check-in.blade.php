<x-kiosk-layout-update>
  <div class="p-4 md:p-6">
    <a href="{{ route('kiosk.dashboard') }}" class="inline-flex items-center text-[#00A0F5] font-semibold text-lg">
      <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      BACK
    </a>
    @livewire('kiosk.check-in')
  </div>
</x-kiosk-layout-update>
