<div class="flex flex-col h-[calc(100vh-14rem)] w-full max-w-7xl mx-auto items-center justify-center">
  {{-- Centered Input Form --}}
  <div class="w-full max-w-lg flex flex-col justify-center px-8 space-y-12">
    <div class="space-y-4 text-center">
      <h1 class="text-3xl md:text-5xl font-bold text-gray-900 tracking-tight">Verificar Membresía</h1>
      <p class="text-gray-500 text-xl">Ingresar el código de socio para verificar status.</p>
    </div>

    <div class="w-full">
      <form wire:submit="check" class="flex flex-col gap-10">
        <div class="space-y-2">
          <label for="code" class="block text-sm font-medium text-zinc-700 sr-only">Código de
            Socio</label>
          <input type="text" id="code" wire:model="code" placeholder="0000"
            class="block w-full text-center text-7xl font-mono font-bold tracking-widest border-0 border-b-2 border-zinc-200 bg-transparent py-4 focus:ring-0 focus:border-indigo-600 transition-colors placeholder-gray-200 outline-none text-gray-900 "
            maxlength="4" autofocus autocomplete="off" />
          <flux:error name="code" class="text-center text-lg!" />
        </div>

        <button type="submit"
          class="w-full h-16 bg-zinc-900 dark:bg-white text-white dark:text-black rounded-full text-xl font-bold hover:opacity-90 transition-all transform active:scale-95 shadow-lg">
          Verificar
        </button>
      </form>
    </div>
  </div>

  {{-- Result Modal --}}
  @if ($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="close" wire:keydown.window.escape="close">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="transform overflow-hidden rounded-xl bg-white dark:bg-zinc-900 text-left shadow-2xl transition-all w-full max-w-6xl" wire:click.stop>
      @if ($member)
        <div class="grid grid-cols-1 md:grid-cols-12 bg-white dark:bg-zinc-900 min-h-[580px]">
            {{-- Left Column: Large Image --}}
            <div class="md:col-span-6 relative bg-gray-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                @if ($member->photo)
                    <img src="{{ Storage::url($member->photo) }}" class="absolute inset-0 w-full h-full object-cover"
                        alt="{{ $member->name }}" />
                    <div class="absolute inset-x-0 bottom-0 h-1/3 bg-linear-to-t from-black/60 to-transparent pointer-events-none"></div>
                @else
                    <div class="flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                        <div class="w-32 h-32 bg-gray-200 dark:bg-zinc-700 rounded-full flex items-center justify-center mb-4">
                             <span class="text-5xl font-bold text-gray-400 dark:text-zinc-500">{{ $member->initials() }}</span>
                        </div>
                    </div>
                @endif

                {{-- ID Badge --}}
                <div
                  class="absolute bottom-4 left-4 bg-white/90 px-3.5 py-1.5 rounded-lg border border-gray-100 z-20">
                  <p class="text-lg font-semibold text-gray-900">
                    <span class="text-gray-600">#</span>{{ $member->code }}
                  </p>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="md:col-span-6 p-10 px-8 pb-8 flex flex-col justify-between h-full bg-white dark:bg-zinc-900">
                <div>
                   <div class="flex justify-between items-start mb-2">
                        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight mb-2">
                            {{ $member->name }}
                        </h2>
                   </div>
                    <p class="text-xl text-gray-700 dark:text-gray-400 font-medium mb-12">
                         {{ $member->activeMembership()?->plan_name ?? $member->latestMembership()?->plan_name ?? '' }}
                    </p>

                    <div class="space-y-6">
                        @if ($status === 'active')
                            <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-green-100 text-green-800 border border-green-200">
                                <flux:icon icon="check-circle" class="size-6" variant="mini"/>
                                <span class="font-bold uppercase text-lg">Membresía Activa</span>
                            </div>

                            <div>
                                <p class="text-lg font-semibold text-gray-600 mb-1">Vence en</p>
                                <p class="text-4xl font-bold text-gray-900 dark:text-white">
                                    {{ $member->activeMembership()?->expiration_time ?? '-' }}
                                </p>
                            </div>

                         @elseif ($status === 'expired')
                            <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-red-100 text-red-800 border border-red-200">
                                <flux:icon icon="x-circle" class="size-6" variant="mini"/>
                                <span class="uppercase font-bold text-lg">Membresía Vencida</span>
                            </div>

                            <div>
                                <p class="text-lg font-semibold text-gray-600 mb-1">Venció hace</p>
                                <p class="text-4xl font-bold text-gray-900 dark:text-white">
                                     {{ $member->latestMembership()?->expiration_time ?? '-' }}
                                </p>
                            </div>

                        @elseif ($status === 'no_membership')
                            <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <flux:icon icon="information-circle" class="size-6" variant="mini"/>
                                <span class="uppercase font-bold text-lg">Sin membresía</span>
                            </div>
                            <p class="text-lg text-gray-500 mt-4 leading-relaxed">
                                El socio aún no cuenta con una membresía.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end pt-8">
                     <flux:button wire:click="close" class="w-full md:w-auto" icon="x-mark">
                        Cerrar
                    </flux:button>
                </div>
            </div>
        </div>
      @elseif ($status === 'not_found')
        <div class="p-12 text-center flex flex-col items-center justify-center min-h-[580px]">
             <div class="inline-flex p-8 bg-red-100 rounded-full ring-1 ring-red-200 mb-8">
                <flux:icon icon="user" class="size-20 text-red-700" variant="solid" />
            </div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Socio No Encontrado</h2>
            <p class="text-xl text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
              Código no encontrado. Por favor verifique e intente nuevamente.
            </p>

            <flux:button wire:click="close">
                Intentar de nuevo
            </flux:button>
        </div>
      @endif
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
