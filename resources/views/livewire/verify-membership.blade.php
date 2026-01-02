<div class="flex flex-col md:flex-row h-[calc(100vh-14rem)] w-full max-w-7xl mx-auto items-center">
  {{-- Left Column: Input Form --}}
  <div class="w-full md:w-5/12 flex flex-col justify-center px-8 md:px-16 space-y-12">
    <div class="space-y-4 text-center">
      <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Verificar Membresía</h1>
      <p class="text-gray-500 text-lg">Ingresar el código de socio para verificar su membresía.</p>
    </div>

    <div class="w-full max-w-md mx-auto">
      <form wire:submit="check" class="flex flex-col gap-8">
        <div class="space-y-2">
          <label for="code" class="block text-sm font-medium text-zinc-700 sr-only">Código de
            Socio</label>
          <input type="text" id="code" wire:model="code" placeholder="0001"
            class="block w-full text-center text-5xl font-mono font-bold tracking-widest border-0 border-b-2 border-zinc-300 bg-transparent py-3 focus:ring-0 focus:border-indigo-600 transition-colors placeholder-gray-300 outline-none text-gray-800"
            maxlength="4" autofocus autocomplete="off" />
          <flux:error name="code" class="text-center text-base!" />
        </div>

        <button type="submit"
          class="w-5/6 mx-auto h-14 bg-zinc-900 dark:bg-white text-white dark:text-black rounded-full text-lg font-bold hover:opacity-90 transition-opacity">
          Verificar
        </button>
      </form>
    </div>
  </div>

  {{-- Vertical Divider (only visible on md+) --}}
  <div class="hidden md:block h-3/4 w-px bg-zinc-200 dark:bg-zinc-800"></div>

  {{-- Right Column: Result Display --}}
  <div class="w-full md:w-7/12 h-full flex flex-col items-center justify-center px-8 py-12">
    @if ($status)
      <div class="w-full max-w-4xl animate-in fade-in slide-in-from-right-8 duration-500">

        @if ($status === 'not_found')
          <div class="text-center space-y-6">
            <div class="inline-flex p-8 bg-red-50 dark:bg-red-900/20 rounded-full ring-1 ring-red-100 dark:ring-red-900/30">
                <flux:icon icon="user-minus" class="size-20 text-red-600 dark:text-red-400" variant="solid" />
            </div>
            <h2 class="text-4xl font-bold text-zinc-900 dark:text-white">Socio No Encontrado</h2>
            <p class="text-2xl text-zinc-500 dark:text-zinc-400 max-w-lg mx-auto">{{ $message }}</p>
          </div>
        @else
          {{-- Member Details (Direct Layout, No Card) --}}
          <div class="flex flex-col lg:flex-row gap-8 items-center lg:items-start text-center lg:text-left">

            {{-- Photo Column --}}
            <div class="shrink-0 relative">
               {{-- Status Indicator on Photo --}}
                {{-- <div @class([
                    'absolute -top-2 -right-2 size-8 rounded-full border-4 border-white dark:border-zinc-900 shadow-md',
                    'bg-emerald-500' => $status === 'active',
                    'bg-red-500' => $status === 'expired',
                    'bg-blue-500' => $status === 'no_membership',
                ])></div> --}}

              @if ($member->photo)
                <img src="{{ Storage::url($member->photo) }}"
                      class="size-80 object-cover rounded-3xl shadow-lg bg-zinc-100 dark:bg-zinc-800 ring-1 ring-black/5 dark:ring-white/10"
                      alt="{{ $member->name }}">
              @else
                <div class="size-64 flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 rounded-3xl ring-1 ring-black/5 dark:ring-white/10 shadow-lg">
                  <span class="text-7xl font-bold text-zinc-300 dark:text-zinc-600">{{ $member->initials() }}</span>
                </div>
              @endif
            </div>

            {{-- Details Column --}}
            <div class="flex-1 min-w-0 w-full py-2">
              <div class="space-y-3 mb-8">
                <h2 class="text-3xl md:text-4xl font-extrabold text-zinc-900 dark:text-white leading-none tracking-tight break-words">{{ $member->name }}</h2>
                {{-- <div class="flex items-center justify-center lg:justify-start gap-4">
                    <span class="text-lg font-mono font-medium text-zinc-400 dark:text-zinc-500">
                    #{{ $member->code }}
                  </span>
                </div> --}}
              </div>

              <div class="mb-7">
                <p class="text-sm font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2">Plan Actual</p>
                <p class="text-xl font-bold text-zinc-800 dark:text-zinc-100 leading-tight">
                      {{ $member->activeMembership()?->plan_name ?? $member->latestMembership()?->plan_name ?? 'Sin plan asignado' }}
                </p>
              </div>

              <div class="space-y-4">
                @if ($status === 'active')
                  <div class="inline-flex items-center gap-3 text-emerald-600 dark:text-emerald-400 text-lg font-bold">
                      <div class="relative flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                      </div>
                      MEMBRESÍA ACTIVA
                  </div>

                  <div class="mt-2">
                    <p class="text-sm font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Vence el</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight">
                        {{ $member->activeMembership()?->expiration_time ?? 'Fecha desconocida' }}
                    </p>
                  </div>

                @elseif ($status === 'expired')
                   <div class="inline-flex items-center gap-3 text-red-600 dark:text-red-400 text-2xl font-bold">
                      <flux:icon icon="x-circle" class="size-7 stroke-2" />
                      MEMBRESÍA VENCIDA
                  </div>

                  <div class="mt-2">
                    <p class="text-sm font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Venció el</p>
                    <p class="text-4xl font-black text-zinc-900 dark:text-white tracking-tight">
                          {{ $member->latestMembership()?->expiration_time ?? '-' }}
                    </p>
                  </div>

                @elseif ($status === 'no_membership')
                    <div class="inline-flex items-center gap-3 text-blue-600 dark:text-blue-400 text-2xl font-bold">
                      <flux:icon icon="information-circle" class="size-7 stroke-2" />
                      SIN MEMBRESÍA
                  </div>
                  <p class="text-xl text-zinc-500 dark:text-zinc-400 mt-2 leading-relaxed max-w-md mx-auto lg:mx-0">
                    El socio no cuenta con un historial de membresías activo.
                  </p>
                @endif
              </div>

            </div>
          </div>
        @endif

      </div>
    @else
      {{-- Idle State Placeholder --}}
      <div class="flex flex-col items-center justify-center text-center opacity-30 select-none">
        <flux:icon icon="qr-code" class="size-48 text-zinc-300 dark:text-zinc-700 mb-8" stroke-width="1" />
        <p class="text-4xl font-light text-zinc-400 dark:text-zinc-600">Esperando consulta...</p>
      </div>
    @endif
  </div>
</div>
