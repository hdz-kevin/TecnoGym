<div>
    <div class="pt-4 -mt-6 space-y-6">
        @if ($visitTypes->count() > 0)
            <div class="flex justify-end">
                <flux:button variant="primary" icon="plus" wire:click="createModal">
                    Tipo de Visita
                </flux:button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            @forelse ($visitTypes as $visitType)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm" wire:key="{{ $visitType->id }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">{{ $visitType->name }}</h3>
                                <p class="text-lg font-semibold text-gray-900 mt-1">${{ number_format($visitType->price) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    class="border text-indigo-600! hover:text-indigo-700! hover:bg-indigo-50!"
                                    variant="ghost"
                                    size="sm"
                                    wire:click="editModal({{ $visitType->id }})"
                                    icon="pencil"
                                >
                                    Editar
                                </flux:button>
                                <flux:button
                                    class="border text-red-600! hover:text-red-700! hover:bg-red-50!"
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    wire:click="delete({{ $visitType->id }})"
                                    wire:confirm="¿Estás seguro de eliminar este tipo de visita?"
                                >
                                    Eliminar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-12">
                     <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No hay tipos de visita configurados</h3>
                    <p class="text-gray-500 mb-6">Comienza creando tu primer tipo de visita</p>
                    <flux:button variant="primary" icon="plus" wire:click="createModal">
                        Crear Uno
                    </flux:button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        wire:click.stop>

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $editingVisitType ? 'Editar Tipo de Visita' : 'Nuevo Tipo de Visita' }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <!-- Modal Body -->
                            <div class="px-6 py-4 space-y-4">
                                <flux:field>
                                    <flux:label for="name">Nombre</flux:label>
                                    <flux:input wire:model="name" id="name" placeholder="Ej: Visita General, Visita Estudiante" />
                                    <flux:error name="name" />
                                </flux:field>

                                <flux:field>
                                    <flux:label for="price">Precio (MXN)</flux:label>
                                    <flux:input wire:model="price" id="price" type="number" min="0" placeholder="50" />
                                    <flux:error name="price" />
                                </flux:field>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                                <flux:button type="submit" variant="primary">{{ $editingVisitType ? 'Guardar cambios' : 'Guardar' }}</flux:button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
