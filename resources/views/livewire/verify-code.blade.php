@php
  use App\Enums\MembershipStatus;
@endphp

<x-slot:title></x-slot:title>
<x-slot:subtitle></x-slot:subtitle>

<div class="flex flex-col min-h-[calc(100vh-4rem)] w-full mx-auto" wire:keydown.window.escape="clear">
  {{-- 2-column layout --}}
  <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-8 p-4 items-center">
    {{-- Left Column: gym name + verification form --}}
    <div class="flex flex-col justify-center h-full relative lg:col-span-1">
      <div class="w-full mx-auto">
        {{-- Gym name & address --}}
        <div class="space-y-4 absolute top-0">
          <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 tracking-tight">
            {{ $gymName }}
          </h1>
          <div class="flex items-center gap-2 text-gray-800">
            <flux:icon icon="map-pin" class="size-5 shrink-0" variant="mini" />
            <span class="text-lg font-normal">{{ $gymAddress }}</span>
          </div>
        </div>

        {{-- Form instructions --}}
        <p class="text-xl text-gray-800 text-center leading-loose mb-10">
          Bienvenid@, ingresa tu código y presiona
          <kbd class="px-2.5 py-1 bg-gray-100 rounded-lg text-gray-800">Enter</kbd>
          para verificar tu membresía.
        </p>

        {{-- Verification Form --}}
        <form wire:submit="check" class="flex flex-col gap-8">
          <div class="space-y-2">
            <label for="code" class="block text-sm font-medium text-zinc-700 sr-only">
              Código de Socio
            </label>
            <input
              type="text"
              id="code"
              wire:model="code"
              placeholder="00000"
              class="block w-full text-center text-6xl font-mono font-bold tracking-widest border-0 border-b-2 border-zinc-400 bg-transparent py-4 focus:ring-0 focus:border-gray-600 transition-colors placeholder-gray-200 outline-none text-gray-800"
              maxlength="5"
              autocomplete="off"
            />
            <flux:error name="code" class="text-center text-red-600 text-lg!" />
          </div>

          <button type="submit"
            class="w-full h-14 bg-zinc-800 text-white rounded-full text-xl font-semibold hover:opacity-90 transition-all transform active:scale-95 shadow-lg"
          >
            Verificar
          </button>
        </form>
      </div>
    </div>

    {{-- Right Column: verification result --}}
    <div class="flex items-center justify-center h-full lg:col-span-2">
      <div class="w-full mx-auto">
        @if (!$showResult)
          {{-- Empty state --}}
          <div class="flex flex-col items-center justify-center text-center py-16 px-8">
            <div class="w-28 h-28 bg-gray-100 rounded-full flex items-center justify-center mb-6">
              <flux:icon icon="hashtag" class="size-12 text-gray-400" />
            </div>
            <p class="text-xl text-gray-500">
              Verificación de Membresía por código
            </p>
          </div>
        @elseif ($member)
          <div class="flex flex-col items-center text-center">
            {{-- Member photo / avatar --}}
            @if ($member->photo)
              <div class="size-96 md:size-130 rounded-lg overflow-hidden shadow-md mb-5">
                <img src="{{ Storage::url($member->photo) }}" class="w-full h-full object-cover"
                  alt="{{ $member->name }}" />
              </div>
            @else
              <div class="size-96 md:size-130 bg-gray-100 rounded-lg flex items-center justify-center shadow-md mb-5">
                <span class="text-7xl md:text-8xl font-bold text-gray-400">{{ $member->initials() }}</span>
              </div>
            @endif

            {{-- Membership status message --}}
            @if ($membership?->status === MembershipStatus::ACTIVE)
              <p class="text-[44px] font-bold text-gray-800 leading-loose">
                {{ $member->name }}
              </p>
              <p class="text-[36px] font-semibold text-green-600 leading-snug max-w-[600px]">
                Tu membresía vence en {{ $membership->expiration_time ?? '-' }}.
              </p>
            @elseif ($membership?->status == MembershipStatus::EXPIRED)
              <p class="text-[44px] font-bold text-gray-800 leading-loose">
                {{ $member->name }}
              </p>
              <p class="text-[36px] font-semibold text-red-600 leading-snug max-w-[600px]">
                Tu membresía venció hace {{ $membership->expiration_time ?? '-' }}.
              </p>
            @else
              <p class="text-[44px] font-bold text-gray-800 leading-loose">
                {{ $member->name }}
              </p>
              <p class="text-[36px] font-semibold text-yellow-600 leading-snug max-w-[600px]">
                No cuentas con una membresía aún.
              </p>
            @endif
          </div>
        @else
          {{-- Member not found --}}
          <div class="flex flex-col items-center justify-center text-center">
            <div class="inline-flex p-6 bg-red-100 rounded-full ring-1 ring-red-200 mb-8">
              <flux:icon icon="user" class="size-20 text-red-700" variant="solid" />
            </div>
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Socio no encontrado</h2>
            <p class="text-xl text-gray-800 mx-auto">
              No existe un socio con el código ingresado.
            </p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Sounds --}}
  <audio id="sound-success" src="{{ asset('sounds/success.mp3') }}"></audio>
  <audio id="sound-error" src="{{ asset('sounds/error.mp3') }}"></audio>

  @script
    <script>
      // Focus on the code input on component init
      const codeInput = document.getElementById('code');
      if (codeInput) {
        codeInput.focus();
      }

      // Re-focus after clearing a result
      $wire.on('focus-code-input', () => {
        setTimeout(() => {
          const input = document.getElementById('code');
          if (input) {
            input.focus();
          }
        }, 100);
      });

      $wire.on('play-sound', (event) => {
        const status = event.status;
        const soundElement = document.getElementById(`sound-${status}`);
        if (soundElement) {
          soundElement.currentTime = 0;
          soundElement.play().catch(error => console.log('Error playing sound:', error));
        }
      });
    </script>
  @endscript
</div>
