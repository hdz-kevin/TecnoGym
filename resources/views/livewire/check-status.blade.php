<div class="flex flex-col md:flex-row h-[calc(100vh-100px)] w-full max-w-7xl mx-auto items-center">
    {{-- Left Column: Input Form --}}
    <div class="w-full md:w-1/2 flex flex-col justify-center px-8 md:px-16 space-y-12">
        <div class="space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-zinc-900 dark:text-white">Verificar Socio</h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-xl">Sistema de consulta de estado de membresía en tiempo real.</p>
        </div>

        <div class="w-full max-w-md">
            <form wire:submit="check" class="flex flex-col gap-8">
                <div class="space-y-2">
                    <label for="code" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 sr-only">Código de Socio</label>
                    <input
                        type="text"
                        id="code"
                        wire:model="code"
                        placeholder="0001"
                        class="block w-full text-center text-6xl font-mono font-bold tracking-widest border-0 border-b-2 border-zinc-300 bg-transparent py-4 focus:ring-0 focus:border-indigo-600 transition-colors placeholder-zinc-300 outline-none text-zinc-900"
                        maxlength="4"
                        autofocus
                        autocomplete="off"
                    />
                    @error('code') <span class="text-red-500 text-sm block text-center">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full h-16 bg-zinc-900 dark:bg-white text-white dark:text-black rounded-full text-xl font-bold hover:opacity-90 transition-opacity">
                    Consultar Estado
                </button>
            </form>
        </div>
    </div>

    {{-- Vertical Divider (only visible on md+) --}}
    <div class="hidden md:block h-3/4 w-px bg-zinc-200 dark:bg-zinc-800"></div>

    {{-- Right Column: Result Display --}}
    <div class="w-full md:w-1/2 h-full flex items-center justify-center px-8 md:px-16">
        @if ($status)
            <div class="w-full text-center space-y-8 animate-in fade-in slide-in-from-right-8 duration-700">
                {{-- Status Icon --}}
                <div class="flex justify-center">
                    @if ($status === 'active')
                        <div class="p-6 bg-green-50 dark:bg-green-900/20 rounded-full ring-1 ring-green-100 dark:ring-green-900/30">
                            <flux:icon icon="check-circle" class="size-24 text-green-600 dark:text-green-400" variant="solid" />
                        </div>
                    @elseif ($status === 'expired')
                        <div class="p-6 bg-red-50 dark:bg-red-900/20 rounded-full ring-1 ring-red-100 dark:ring-red-900/30">
                            <flux:icon icon="x-circle" class="size-24 text-red-600 dark:text-red-400" variant="solid" />
                        </div>
                    @elseif ($status === 'no_membership')
                        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-full ring-1 ring-blue-100 dark:ring-blue-900/30">
                            <flux:icon icon="information-circle" class="size-24 text-blue-600 dark:text-blue-400" variant="solid" />
                        </div>
                    @else
                        <div class="p-6 bg-amber-50 dark:bg-amber-900/20 rounded-full ring-1 ring-amber-100 dark:ring-amber-900/30">
                            <flux:icon icon="exclamation-triangle" class="size-24 text-amber-600 dark:text-amber-400" variant="solid" />
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    @if($status === 'not_found')
                        <h2 class="text-4xl font-bold text-zinc-900 dark:text-white">No Encontrado</h2>
                        <p class="text-xl text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">{{ $message }}</p>
                    @else
                        <div>
                            <h2 class="text-5xl font-extrabold tracking-tight text-zinc-900 dark:text-white mb-2">{{ $member->name }}</h2>
                            <p class="text-lg font-mono text-zinc-400">{{ $member->code }}</p>
                        </div>

                        @if($status === 'active')
                            <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-lg font-bold">
                                <div class="size-3 rounded-full bg-green-500 animate-pulse"></div>
                                MEMBRESÍA ACTIVA
                            </div>
                            <div class="mt-8">
                                <p class="text-sm font-semibold uppercase tracking-widest text-zinc-400">Vence en</p>
                                <p class="text-4xl font-bold text-zinc-900 dark:text-white mt-2">
                                    {{ $member->activeMembership()->expiration_time ?? 'Fecha desconocida' }}
                                </p>
                                <p class="text-zinc-500 mt-2">{{ $member->activeMembership()->plan_name }}</p>
                            </div>
                        @elseif($status === 'expired')
                             <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-lg font-bold">
                                MEMBRESÍA VENCIDA
                            </div>
                             <div class="mt-8">
                                <p class="text-sm font-semibold uppercase tracking-widest text-zinc-400">Venció hace</p>
                                <p class="text-4xl font-bold text-zinc-900 dark:text-white mt-2">
                                    {{ $member->latestMembership()->expiration_time ?? 'Fecha desconocida' }}
                                </p>
                                 <p class="text-zinc-500 mt-2">{{ $member->latestMembership()->plan_name }}</p>
                            </div>
                        @elseif($status === 'no_membership')
                            <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-lg font-bold">
                                SIN MEMBRESÍA
                            </div>
                            <div class="mt-8 max-w-md mx-auto">
                                <p class="text-xl text-zinc-600 dark:text-zinc-300 leading-relaxed">
                                    El socio no cuenta con un plan activo ni historial de membresías.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @else
            {{-- Idle State Placeholder --}}
            <div class="flex flex-col items-center justify-center text-center opacity-30">
                <flux:icon icon="qr-code" class="size-48 text-zinc-300 dark:text-zinc-700 mb-8" stroke-width="1" />
                <p class="text-3xl font-light text-zinc-400 dark:text-zinc-600">Resultados aquí</p>
            </div>
        @endif
    </div>
</div>
