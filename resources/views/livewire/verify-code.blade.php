@php
  use App\Enums\MembershipStatus;
@endphp

{{-- Override title/subtitle for the top header --}}
<x-slot:title>Bienvenida</x-slot:title>
<x-slot:subtitle></x-slot:subtitle>

<div class="flex flex-col min-h-[calc(100vh-8rem)] w-full max-w-7xl mx-auto">

  {{-- Gym Identity Header --}}
  <div class="text-center pt-6 pb-4 md:pt-10 md:pb-6">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
      {{ $gymName }}
    </h1>
    <div class="flex items-center justify-center gap-2 mt-3 text-gray-500">
      <flux:icon icon="map-pin" class="size-4 shrink-0" variant="mini" />
      <span class="text-base">{{ $gymAddress }}</span>
    </div>
  </div>

  {{-- Main Content: 2-column layout --}}
  <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 px-2 pb-8 items-start">

    {{-- Left Column: Welcome + Verification Form --}}
    <div class="flex flex-col justify-center h-full">
      <div class="w-full max-w-md mx-auto space-y-10">

        {{-- Welcome message --}}
        <div class="space-y-2">
          <p class="text-xl text-gray-600 leading-relaxed">
            Bienvenido, ingresa el código de un socio para verificar su membresía.
          </p>
        </div>

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
              class="block w-full text-center text-7xl font-mono font-bold tracking-widest border-0 border-b-2 border-zinc-200 bg-transparent py-4 focus:ring-0 focus:border-indigo-600 transition-colors placeholder-gray-200 outline-none text-gray-900"
              maxlength="5"
              autocomplete="off"
            />
            <flux:error name="code" class="text-center text-lg!" />
          </div>

          <button type="submit"
            class="w-full h-14 bg-zinc-900 text-white rounded-full text-xl font-bold hover:opacity-90 transition-all transform active:scale-95 shadow-lg">
            Verificar
          </button>
        </form>

        {{-- Current date/time --}}
        <div class="flex items-center justify-center gap-2 text-sm text-gray-400 pt-2">
          <flux:icon icon="clock" class="size-4" variant="mini" />
          <span>{{ now()->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
        </div>
      </div>
    </div>

    {{-- Right Column: Verification Result (inline) --}}
    <div class="flex items-center justify-center h-full">
      <div class="w-full max-w-lg">
        @if (!$showResult)
          {{-- Empty state --}}
          <div class="flex flex-col items-center justify-center text-center py-16 px-8">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
              <flux:icon icon="qr-code" class="size-12 text-gray-300" />
            </div>
            <p class="text-lg text-gray-400 max-w-xs">
              Ingresa un código de socio para ver el resultado de la verificación
            </p>
          </div>
        @elseif ($member)
          {{-- Member found --}}
          <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            {{-- Member photo --}}
            <div class="relative bg-gray-100 h-64 flex items-center justify-center overflow-hidden">
              @if ($member->photo)
                <img src="{{ Storage::url($member->photo) }}" class="absolute inset-0 w-full h-full object-cover"
                  alt="{{ $member->name }}" />
                <div
                  class="absolute inset-x-0 bottom-0 h-1/3 bg-linear-to-t from-black/60 to-transparent pointer-events-none">
                </div>
              @else
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <div
                    class="w-28 h-28 bg-gray-200 rounded-full flex items-center justify-center">
                    <span
                      class="text-4xl font-bold text-gray-400">{{ $member->initials() }}</span>
                  </div>
                </div>
              @endif
            </div>

            {{-- Member details --}}
            <div class="p-6 md:p-8 space-y-6">
              <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight leading-tight">
                {{ $member->name }}
              </h2>

              @if ($membership?->status === MembershipStatus::ACTIVE)
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-green-100 text-green-800 border border-green-200">
                  <flux:icon icon="check-circle" class="size-6" variant="mini" />
                  <span class="font-bold uppercase text-lg">Membresía Activa</span>
                </div>

                <div>
                  <p class="text-base font-semibold text-gray-600 mb-1">Vence en</p>
                  <p class="text-2xl md:text-3xl font-bold text-gray-800">
                    {{ $membership->expiration_time ?? '-' }}
                  </p>
                </div>
              @elseif ($membership?->status == MembershipStatus::EXPIRED)
                <div
                  class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-red-100 text-red-800 border border-red-200">
                  <flux:icon icon="x-circle" class="size-6" variant="mini" />
                  <span class="uppercase font-bold text-lg">Membresía Vencida</span>
                </div>

                <div>
                  <p class="text-base font-semibold text-gray-600 mb-1">Venció hace</p>
                  <p class="text-2xl md:text-3xl font-bold text-gray-800">
                    {{ $membership->expiration_time ?? '-' }}
                  </p>
                </div>
              @else
                <div
                  class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                  <flux:icon icon="information-circle" class="size-6" variant="mini" />
                  <span class="uppercase font-bold text-lg">Sin membresía</span>
                </div>
                <p class="text-base text-gray-600 leading-relaxed">
                  <span class="font-semibold">{{ $member->name }}</span> aún no cuenta con una membresía.
                </p>
              @endif

              {{-- Clear button --}}
              <div class="pt-2">
                <button wire:click="clear"
                  class="w-full h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-base font-semibold transition-all transform active:scale-95 flex items-center justify-center gap-2">
                  <flux:icon icon="arrow-path" class="size-5" variant="mini" />
                  Nueva consulta
                </button>
              </div>
            </div>
          </div>
        @else
          {{-- Member not found --}}
          <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-10 md:p-12 text-center flex flex-col items-center justify-center">
              <div class="inline-flex p-6 bg-red-100 rounded-full ring-1 ring-red-200 mb-6">
                <flux:icon icon="user" class="size-16 text-red-700" variant="solid" />
              </div>
              <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Socio no encontrado</h2>
              <p class="text-lg text-gray-500 max-w-sm mx-auto mb-8">
                No existe un socio con el código ingresado.
              </p>

              <button wire:click="clear"
                class="w-full max-w-xs h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-base font-semibold transition-all transform active:scale-95 flex items-center justify-center gap-2 mx-auto">
                <flux:icon icon="arrow-path" class="size-5" variant="mini" />
                Nueva consulta
              </button>
            </div>
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
