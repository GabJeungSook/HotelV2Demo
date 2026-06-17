<x-kiosk-layout-update>
  <div class="flex flex-col items-center min-h-[85vh]">
    {{-- Welcome Section --}}
    <div class="w-full max-w-lg text-center pt-8 pb-6">
      <p class="text-lg md:text-xl text-gray-500 font-medium tracking-wide">WELCOME TO</p>
      <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mt-1 uppercase">{{ auth()->user()->branch->name ?? 'Hotel' }}</h1>
      <p class="text-sm md:text-base text-gray-400 italic mt-2">"A Great and Affordable Hotel to Stay!"</p>
      {{-- Amenity Icons --}}
      <div class="flex justify-center items-center space-x-3 mt-4">
        <img src="{{ asset('images/youtube.png') }}" alt="YouTube" class="w-7 h-7 object-contain">
        <img src="{{ asset('images/spotify.png') }}" alt="Spotify" class="w-7 h-7 object-contain">
        <img src="{{ asset('images/netflix.png') }}" alt="Netflix" class="w-7 h-7 object-contain">
        <img src="{{ asset('images/wifi.png') }}" alt="WiFi" class="w-7 h-7 object-contain">
        <img src="{{ asset('images/shower.png') }}" alt="Shower" class="w-7 h-7 object-contain">
        <img src="{{ asset('images/air.png') }}" alt="Aircon" class="w-7 h-7 object-contain">
      </div>
    </div>

    {{-- Select Transaction Section --}}
    <div class="w-full max-w-md bg-[#E8F7FF] rounded-3xl px-8 py-8 mt-12">
      <h2 class="text-xl font-extrabold text-gray-800 text-center uppercase mb-6">Select Transaction</h2>

      {{-- Check-In Button --}}
      <a href="{{ route('kiosk.house-rules') }}"
        class="flex items-center bg-[#00A0F5] hover:bg-[#0090dd] text-white rounded-full px-6 py-4 mb-4 transition-all shadow-md active:scale-95">
        <div class="w-10 h-10 flex items-center justify-center mr-4">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
        </div>
        <div class="flex-1">
          <span class="text-xl md:text-2xl font-bold block">CHECK-IN</span>
          <span class="text-xs opacity-80">Start your stay</span>
        </div>
        <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>

      {{-- Check-Out Button --}}
      <a href="{{ route('kiosk.check-out') }}"
        class="flex items-center bg-white hover:bg-gray-50 text-[#00A0F5] border-2 border-[#00A0F5] rounded-full px-6 py-4 transition-all shadow-sm active:scale-95">
        <div class="w-10 h-10 flex items-center justify-center mr-4">
          <svg class="w-7 h-7 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </div>
        <div class="flex-1">
          <span class="text-xl md:text-2xl font-bold block">CHECKOUT</span>
          <span class="text-xs opacity-70">Settle and depart</span>
        </div>
        <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    {{-- Footer --}}
    <div class="mt-auto pb-6 pt-8 flex items-center justify-center space-x-8 text-gray-400 text-sm">
      <div class="flex items-center space-x-2">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
        <span>{{ auth()->user()->branch->name ?? 'Hotel' }}</span>
      </div>
      <div class="flex items-center space-x-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        <span>+639123456789</span>
      </div>
    </div>
  </div>
</x-kiosk-layout-update>
