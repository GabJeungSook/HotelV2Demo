<div class="pt-4">
  @php
    $hasAnyRoom = collect($types)->contains(fn($type) => isset($typeRoomMap[$type->id]));
  @endphp

  @if (!$hasAnyRoom)
    {{-- No Available Rooms State --}}
    <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8 text-center">
      {{-- Warning Icon --}}
      <div class="flex justify-center mb-4">
        <div class="w-20 h-20 bg-[#D6EEFB] rounded-full flex items-center justify-center">
          <svg class="w-12 h-12 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
        </div>
      </div>

      {{-- Badge --}}
      <div class="inline-flex items-center space-x-2 bg-gray-100 rounded-full px-4 py-1 mb-4">
        <span class="w-2 h-2 bg-[#00A0F5] rounded-full"></span>
        <span class="text-sm font-bold text-gray-700 uppercase">Fully Booked</span>
      </div>

      {{-- Heading --}}
      <h1 class="text-3xl font-extrabold text-gray-800 uppercase leading-tight">No Slots<br>Available Today</h1>
      <p class="mt-3 text-sm text-gray-500">We've reached full capacity for today's appointments. We apologize for the inconvenience.</p>

      {{-- Separator --}}
      <div class="border-t border-gray-200 my-6"></div>

      {{-- Need Assistance --}}
      <div class="bg-gray-50 rounded-xl p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-[#D6EEFB] rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-[#00A0F5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </div>
        <div class="text-left">
          <p class="text-sm font-bold text-gray-700">NEED ASSISTANCE?</p>
          <p class="text-xs text-gray-500">Approach our frontdesk for help.</p>
        </div>
      </div>

      {{-- Refresh Button --}}
      <div class="mt-6">
        <button wire:click="$refresh"
          class="inline-flex items-center space-x-2 text-gray-500 border border-gray-300 rounded-full px-6 py-2 hover:bg-gray-50 transition">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          <span class="font-semibold text-sm">Refresh</span>
        </button>
      </div>
    </div>
  @else
  {{-- Main Card --}}
  <div class="max-w-lg mx-auto bg-white rounded-2xl border border-[#87CEEB] p-6 md:p-8">
    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 uppercase text-center mb-8">Select Room Type</h1>

    <div class="space-y-4">
      @foreach ($types as $type)
        @php $assignedRoom = $typeRoomMap[$type->id] ?? null; @endphp
        <button wire:key="{{ $type->id }}" wire:click="selectType({{ $type->id }}, {{ $assignedRoom ? $assignedRoom->id : 'null' }})" type="button"
          class="w-full transition-all duration-200 {{ !$assignedRoom ? 'opacity-40 cursor-not-allowed' : '' }}">
          <div class="rounded-xl overflow-hidden border-2 transition-all duration-200 relative
            {{ $type_id == $type->id ? 'border-[#00A0F5] shadow-lg shadow-blue-100 ring-2 ring-blue-100' : 'border-gray-200 bg-white' }}">

            {{-- Selected checkmark badge --}}
            @if ($type_id == $type->id)
              <div class="absolute bottom-3 right-3 bg-[#00A0F5] text-white rounded-full w-8 h-8 flex items-center justify-center shadow z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            @endif

            {{-- Bed Icon Area --}}
            <div class="py-4 px-6 flex justify-center {{ $type_id == $type->id ? 'bg-[#1a1a2e]/70' : 'bg-white' }}">
              @switch($type->id)
                @case(1)
                  <svg width="80" height="80" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip_s{{ $type->id }})">
                    <path d="M10.3333 15.5C10.3333 13.3598 12.0682 11.625 14.2083 11.625H47.7917C49.9318 11.625 51.6667 13.3598 51.6667 15.5V29.7083H10.3333V15.5Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.75 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M54.25 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M37.4583 23.25H24.5417C22.4015 23.25 20.6667 24.9848 20.6667 27.125V29.7083H41.3333V27.125C41.3333 24.9848 39.5985 23.25 37.4583 23.25Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.16666 33.5833C5.16666 31.4432 6.90156 29.7083 9.04166 29.7083H52.9583C55.0985 29.7083 56.8333 31.4432 56.8333 33.5833V45.2083H5.16666V33.5833Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs><clipPath id="clip_s{{ $type->id }}"><rect width="62" height="62" fill="white"/></clipPath></defs>
                  </svg>
                  @break
                @case(2)
                  <svg width="80" height="80" viewBox="0 0 67 67" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 18.0714C8 15.8228 9.8888 14 12.2188 14H48.7812C51.1113 14 53 15.8228 53 18.0714V33H8V18.0714Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 50V55" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M56 50V55" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24.75 26H16.25C13.9027 26 12 27.8803 12 30.2V33H29V30.2C29 27.8803 27.0973 26 24.75 26Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M44.75 26H36.25C33.9027 26 32 27.8803 32 30.2V33H49V30.2C49 27.8803 47.0973 26 44.75 26Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 37.25C3 34.9027 4.84683 33 7.125 33H53.875C56.1532 33 58 34.9027 58 37.25V50H3V37.25Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  @break
                @case(3)
                  <div class="flex space-x-2">
                    <svg width="60" height="60" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip_t1{{ $type->id }})">
                      <path d="M10.3333 15.5C10.3333 13.3598 12.0682 11.625 14.2083 11.625H47.7917C49.9318 11.625 51.6667 13.3598 51.6667 15.5V29.7083H10.3333V15.5Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M7.75 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M54.25 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M37.4583 23.25H24.5417C22.4015 23.25 20.6667 24.9848 20.6667 27.125V29.7083H41.3333V27.125C41.3333 24.9848 39.5985 23.25 37.4583 23.25Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M5.16666 33.5833C5.16666 31.4432 6.90156 29.7083 9.04166 29.7083H52.9583C55.0985 29.7083 56.8333 31.4432 56.8333 33.5833V45.2083H5.16666V33.5833Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>
                      <defs><clipPath id="clip_t1{{ $type->id }}"><rect width="62" height="62" fill="white"/></clipPath></defs>
                    </svg>
                    <svg width="60" height="60" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip_t2{{ $type->id }})">
                      <path d="M10.3333 15.5C10.3333 13.3598 12.0682 11.625 14.2083 11.625H47.7917C49.9318 11.625 51.6667 13.3598 51.6667 15.5V29.7083H10.3333V15.5Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M7.75 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M54.25 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M37.4583 23.25H24.5417C22.4015 23.25 20.6667 24.9848 20.6667 27.125V29.7083H41.3333V27.125C41.3333 24.9848 39.5985 23.25 37.4583 23.25Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M5.16666 33.5833C5.16666 31.4432 6.90156 29.7083 9.04166 29.7083H52.9583C55.0985 29.7083 56.8333 31.4432 56.8333 33.5833V45.2083H5.16666V33.5833Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>
                      <defs><clipPath id="clip_t2{{ $type->id }}"><rect width="62" height="62" fill="white"/></clipPath></defs>
                    </svg>
                  </div>
                  @break
                @default
                  <svg width="80" height="80" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip_d{{ $type->id }})">
                    <path d="M10.3333 15.5C10.3333 13.3598 12.0682 11.625 14.2083 11.625H47.7917C49.9318 11.625 51.6667 13.3598 51.6667 15.5V29.7083H10.3333V15.5Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.75 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M54.25 45.2083V50.375" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M37.4583 23.25H24.5417C22.4015 23.25 20.6667 24.9848 20.6667 27.125V29.7083H41.3333V27.125C41.3333 24.9848 39.5985 23.25 37.4583 23.25Z" fill="{{ $type_id == $type->id ? '#A8B8E0' : '#2F88FF' }}" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.16666 33.5833C5.16666 31.4432 6.90156 29.7083 9.04166 29.7083H52.9583C55.0985 29.7083 56.8333 31.4432 56.8333 33.5833V45.2083H5.16666V33.5833Z" stroke="{{ $type_id == $type->id ? 'white' : '#1a1a2e' }}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs><clipPath id="clip_d{{ $type->id }}"><rect width="62" height="62" fill="white"/></clipPath></defs>
                  </svg>
              @endswitch
            </div>

            {{-- Type label (for unselected) --}}
            @if ($type_id != $type->id)
              <div class="text-center py-1">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ $type->name }}</span>
              </div>
            @endif

            {{-- Room Info --}}
            <div class="px-5 py-4 text-center {{ $type_id == $type->id ? 'bg-white' : '' }}">
              @if ($assignedRoom)
                <div class="flex items-baseline justify-center space-x-2">
                  <span class="text-sm text-gray-400 uppercase">Room #</span>
                  <span class="text-4xl font-extrabold text-gray-800">{{ $assignedRoom->number }}</span>
                  <span class="text-base font-bold text-gray-500 uppercase">{{ $assignedRoom->floor->numberWithFormat() }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">
                  @if($type->id == 1)
                    1 PILLOW | 1 BLANKET | 1 TOWEL
                  @elseif($type->id == 2)
                    2 PILLOWS | 1 BLANKET | 2 TOWELS
                  @elseif($type->id == 3)
                    2 PILLOWS | 2 BLANKETS | 2 TOWELS | 2 SINGLE BED
                  @else
                    PILLOWS | BLANKET | TOWELS
                  @endif
                </p>
              @else
                <span class="text-sm font-semibold text-red-400">No available room</span>
              @endif
            </div>
          </div>
        </button>
      @endforeach
    </div>

    {{-- Next Button --}}
    @if ($type_id)
      <div class="mt-8 flex justify-center">
        <button wire:click="$set('steps', 3)"
          class="bg-[#00A0F5] hover:bg-[#0090dd] text-white font-bold text-lg py-3 px-16 rounded-full transition-all uppercase shadow-md active:scale-95">
          NEXT
        </button>
      </div>
    @endif

    {{-- Cancel Transaction --}}
    <div class="mt-6 flex justify-center">
      <button wire:click="redirectToHome" type="button"
        class="text-[#00A0F5] hover:text-[#0090dd] font-semibold text-sm uppercase tracking-wide transition">
        CANCEL TRANSACTION
      </button>
    </div>
  </div>
  @endif
</div>
