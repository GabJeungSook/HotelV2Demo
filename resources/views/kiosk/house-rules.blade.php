<x-kiosk-layout-update>
  <div class="px-4 md:px-10 py-6" x-data="{ agreed: false }">
    {{-- Back Link --}}
    <a href="{{ route('kiosk.dashboard') }}" class="inline-flex items-center text-[#00A0F5] font-semibold text-lg mb-4">
      <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      BACK
    </a>

    {{-- Main Card --}}
    <div class="max-w-2xl mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
      <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center">{{ auth()->user()->branch->name ?? 'Hotel' }}</h1>
      <h2 class="text-xl md:text-2xl font-extrabold text-gray-800 uppercase text-center mt-1">House Rules</h2>

      <div class="mt-6 overflow-y-auto max-h-[50vh] text-sm text-gray-700 leading-relaxed space-y-4 pr-2">
        <p class="font-bold text-gray-800">Hotel House Rules & Policies</p>

        <p class="font-semibold text-gray-800">TO OUR VALUED GUESTS, WE WOULD LIKE TO ASK FOR YOUR ATTENTION AND COOPERATION IN FOLLOWING OUR HOUSE RULES.</p>

        <ol class="list-decimal list-inside space-y-3">
          <li><span class="font-semibold">TARIFF.</span> The tariff is for the room only.</li>
          <li><span class="font-semibold">SINGLE-SIZED BED ROOM.</span> Good for only 1 person.</li>
          <li><span class="font-semibold">DOUBLE SIZED BED ROOM & TWIN BED - 2 SINGLE BED.</span> Good for 2 persons only. If caught with extra person, guests will be <span class="font-bold text-red-600">"DOUBLE CHARGED FOR THE EXTRA PERSON"</span>.</li>
          <li><span class="font-semibold">SETTLEMENT OF BILLS.</span> Bills must be settled on presentation by payment in cash. Full payment for the room/s will be collected upon check-in.</li>
          <li><span class="font-semibold">SENIOR CITIZENS & PWDs.</span> Please present your Senior Citizen or PWD card upon check-in for the discount.</li>
          <li><span class="font-semibold">DEPOSIT.</span> A deposit of PHP200 per room for the key and TV remote controls will also be collected upon check-in. REFUNDABLE upon check-out.</li>
          <li><span class="font-semibold">CHECK-IN & CHECK-OUT.</span> Guest may check-in anytime. Check-in time will start at the time of arrival. Check-out time depends on the room rate availed by the guest. Guest may check-out earlier than their expected time of check-out. Unused time and time extensions are non-refundable or consumable once checked-out.</li>
          <li>Please remove your <span class="font-semibold">CAP, BONNET, HELMET, SUNGLASSES, & HOOD</span> before entering and while inside the establishment.</li>
          <li>Guests are requested to report any damaged, lost, malfunction or failure of amenities, equipment, and fixtures to the staff immediately.</li>
          <li>Guests are to use their rooms for the agreed period. Failure to check-out by the agreed period will result in an additional fee for extension. Should you wish to extend your stay, kindly inform the Front Desk immediately.</li>
          <li><span class="font-semibold">VISITORS</span> are advised to wait at the lobby only.</li>
          <li>This is a <span class="font-semibold">NON-SMOKING</span> establishment. Smoking is strictly prohibited inside the room. If caught, you will be charged <span class="font-bold text-red-600">PHP200</span>.</li>
          <li><span class="font-semibold">ALCOHOLIC BEVERAGES.</span> Any alcoholic beverages are not allowed inside the establishment. If caught, you will be charged <span class="font-bold text-red-600">PHP200</span>.</li>
          <li><span class="font-semibold">DURIAN & MARANG</span> are strictly prohibited inside the room. If caught you will be charged <span class="font-bold text-red-600">PHP200</span>.</li>
          <li><span class="font-semibold">PETS ARE NOT ALLOWED</span> INSIDE THE ESTABLISHMENT.</li>
          <li><span class="font-semibold">FIREARMS & WEAPONS, HAZARDOUS GOODS, COMBUSTIBLE ARTICLES, & TOOLS</span> ARE STRICTLY PROHIBITED.</li>
          <li><span class="font-semibold">DAMAGE & LOSS</span> of company property by the guest will be charged accordingly upon checking out. Removing fixtures (TV, TELEPHONE, AIRCON, etc.) inside the room is prohibited. If caught, the guest will be advised to vacate.</li>
          <li><span class="font-semibold">VANDALISM IS STRICTLY PROHIBITED.</span> Any form of vandalism made by the guest will be charged <span class="font-bold text-red-600">PHP500</span>.</li>
          <li><span class="font-semibold">KEEP YOUR DOOR LOCK CLOSED</span> when not in the room or when you are sleeping. The management will not in any way whatsoever, be responsible for any loss of the guest's belongings or any other property (money, jewelry, documents or other articles of value).</li>
          <li><span class="font-semibold">ROOM KEYS</span> must be deposited at the Front Desk whenever guest leaves the premise and at the time of check-out.</li>
          <li>Guests are advised to be responsible in using the facilities and to observe cleanliness all the time.</li>
        </ol>

        <div class="mt-4 pt-3 border-t border-gray-300 text-sm text-gray-600">
          <p>To contact the Front Desk please dial <span class="font-bold">"0"</span></p>
          <p>To contact the Kitchen please dial <span class="font-bold">"125"</span></p>
          <p class="mt-2 font-semibold text-gray-800">THE MANAGEMENT</p>
        </div>
      </div>

      {{-- Agreement Checkbox --}}
      <div class="mt-6 flex items-center justify-center">
        <label class="flex items-center space-x-3 cursor-pointer select-none">
          <div class="relative">
            <input type="checkbox" x-model="agreed" class="sr-only peer">
            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-[#00A0F5] peer-checked:bg-[#00A0F5] flex items-center justify-center transition">
              <svg x-show="agreed" x-cloak class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
          </div>
          <span class="text-xs text-gray-600">I agree to the Terms of Service and acknowledge that my information will be handled in accordance with the Privacy Policy.</span>
        </label>
      </div>

      {{-- Agree Button --}}
      <div class="mt-6 flex justify-center">
        <a x-bind:href="agreed ? '{{ route('kiosk.check-in') }}' : '#'"
           x-bind:class="agreed ? 'bg-[#00A0F5] hover:bg-[#0090dd] cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
           @click="if(!agreed) $event.preventDefault()"
           class="px-12 py-3 text-white font-bold text-base rounded-full transition duration-200 uppercase">
          AGREE & CONTINUE
        </a>
      </div>
    </div>
  </div>
</x-kiosk-layout-update>
