<div x-data="{ show: false, title: '', description: '' }"
     x-on:kiosk-error.window="show = true; title = $event.detail.title; description = $event.detail.description"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center">

  {{-- Backdrop --}}
  <div class="fixed inset-0 bg-gray-500 bg-opacity-60"
       x-show="show"
       x-transition:enter="ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @click="show = false">
  </div>

  {{-- Modal --}}
  <div class="relative bg-white rounded-2xl shadow-xl p-8 w-full max-w-sm mx-4 text-center"
       x-show="show"
       x-transition:enter="ease-out duration-200"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="ease-in duration-150"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95">

    {{-- Error Icon --}}
    <div class="flex justify-center mb-4">
      <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
      </div>
    </div>

    {{-- Title --}}
    <h3 class="text-xl font-bold text-gray-800" x-text="title"></h3>

    {{-- Description --}}
    <p class="mt-2 text-sm text-gray-500" x-text="description"></p>

    {{-- OK Button --}}
    <div class="mt-6">
      <button @click="show = false"
        class="w-full bg-red-400 hover:bg-red-500 text-white font-bold text-lg py-3 rounded-full transition-all shadow-md active:scale-95">
        OK
      </button>
    </div>
  </div>
</div>
