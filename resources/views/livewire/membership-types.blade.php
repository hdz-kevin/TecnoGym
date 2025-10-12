<x-slot:subtitle>Configura los tipos de membresía y sus periodos</x-slot:subtitle>

<div>
  <div class="p-6 pt-4 space-y-6">
    <div class="flex justify-end">
      <flux:button variant="primary" icon="plus" wire:click="createType">
        Tipo de Membresía
      </flux:button>
    </div>

    <!-- Membership Types Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
      @forelse ($membershipTypes as $type)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
          <!-- Membership Type Header -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $type->name }}</h3>
                <p class="text-sm text-gray-500">
                  {{ $type->periods->count() }} {{ $type->periods->count() === 1 ? 'periodo' : 'periodos' }}
                </p>
              </div>
              <div class="flex items-center gap-2">
                <flux:button class="border !text-indigo-600 hover:!bg-indigo-50 hover:!text-indigo-700" size="sm"
                  variant="ghost" wire:click="editType({{ $type->id }})" icon="pencil">
                  Editar
                </flux:button>
                @if ($type->periods->count() === 0)
                  <flux:button size="sm" variant="ghost" wire:click="deleteType({{ $type->id }})"
                    wire:confirm="¿Estás seguro de eliminar este tipo de membresía?" icon="trash"
                    class="border !text-red-600 hover:!text-red-700 hover:!bg-red-50">
                    Eliminar
                  </flux:button>
                @endif
              </div>
            </div>
          </div>

          <!-- Membership Type Periods -->
          <div class="p-6">
            @if ($type->periods->count() > 0)
              <div class="space-y-3 mb-4">
                @foreach ($type->periods as $period)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                      <div class="flex items-center gap-3">
                        <div>
                          <h3 class="font-medium text-gray-900">{{ $period->name }}</h3>
                          <p class="text-sm text-gray-600">
                            {{ $period->duration_value }} {{ $period->duration_unit->value }}
                          </p>
                        </div>
                        <div class="text-right">
                          <p class="text-lg font-semibold text-gray-900">${{ number_format($period->price) }}</p>
                          <p class="text-xs text-gray-500">MXN</p>
                        </div>
                      </div>
                    </div>
                    <div class="flex items-center gap-1 ml-4">
                      <flux:button size="sm" variant="filled" wire:click="editPeriod({{ $period->id }})"
                        class="!bg-indigo-50 !text-indigo-600 hover:!bg-indigo-100 hover:!text-indigo-700">
                        Editar
                      </flux:button>
                      <flux:button size="sm" variant="filled" wire:click="deletePeriod({{ $period->id }})"
                        wire:confirm="¿Estás seguro de eliminar este periodo?"
                        class="!bg-red-50 !text-red-600 hover:!bg-red-100 hover:!text-red-700">
                        Eliminar
                      </flux:button>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8">
                <div class="text-gray-400 mb-2">
                  <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <p class="text-sm text-gray-500 mb-3">No hay periodos configurados</p>
              </div>
            @endif

            <!-- New Period Button -->
            <flux:button variant="outline" icon="plus" wire:click="createPeriod({{ $type->id }})"
              class="w-full">
              Agregar Periodo
            </flux:button>
          </div>
        </div>
      @empty
        <!-- Empty State -->
        <div class="col-span-full text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-1">No hay tipos de membresía</h3>
          <p class="text-gray-500 mb-6">Comienza creando tu primer tipo de membresía</p>
          <flux:button variant="primary" icon="plus" wire:click="createType">
            Crear Primer Tipo
          </flux:button>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Membership Type Modal -->
  @if ($showTypeModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeTypeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingType ? 'Editar Tipo de Membresía' : 'Nuevo Tipo de Membresía' }}
              </h3>
              <button wire:click="closeTypeModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
              <flux:field>
                <flux:label for="typeName">Nombre</flux:label>
                <flux:input wire:model="typeName" id="typeName" placeholder="Ej: General, Estudiante, Premium" />
                <flux:error name="typeName" />
              </flux:field>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
              <flux:button variant="ghost" wire:click="closeTypeModal">
                Cancelar
              </flux:button>
              <flux:button variant="primary" wire:click="saveType" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $editingType ? 'Actualizar' : 'Crear' }}</span>
                <span wire:loading>{{ $editingType ? 'Actualizando...' : 'Creando...' }}</span>
              </flux:button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Period Modal -->
  @if ($showPeriodModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closePeriodModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingPeriod ? 'Editar Periodo' : 'Nuevo Periodo' }}
                @if ($selectedTypeForPeriod)
                  <span class="text-sm font-normal text-gray-500">- {{ $selectedTypeForPeriod->name }}</span>
                @endif
              </h3>
              <button wire:click="closePeriodModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
              <!-- Name -->
              <flux:field>
                <flux:label for="periodName">Nombre del periodo *</flux:label>
                <flux:input wire:model="periodName" id="periodName" placeholder="Ej: Mensual, Semanal, Anual" />
                <flux:error name="periodName" />
              </flux:field>

              <!-- Duration -->
              <div class="grid grid-cols-2 gap-4">
                <flux:field>
                  <flux:label for="durationValue">Duración *</flux:label>
                  <flux:input wire:model="durationValue" id="durationValue" type="number" min="1"
                    placeholder="1" />
                  <flux:error name="durationValue" />
                </flux:field>

                <flux:field>
                  <flux:label for="durationUnit">Unidad *</flux:label>
                  <flux:select wire:model="durationUnit" id="durationUnit" placeholder="Seleccionar">
                    <flux:select.option value="day">Día(s)</flux:select.option>
                    <flux:select.option value="week">Semana(s)</flux:select.option>
                    <flux:select.option value="month">Mes(es)</flux:select.option>
                    <flux:select.option value="year">Año(s)</flux:select.option>
                  </flux:select>
                  <flux:error name="durationUnit" />
                </flux:field>
              </div>

              <!-- Price -->
              <flux:field>
                <flux:label for="price">Precio (MXN) *</flux:label>
                <flux:input wire:model="price" id="price" type="number" min="1" placeholder="400" />
                <flux:error name="price" />
              </flux:field>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
              <flux:button variant="ghost" wire:click="closePeriodModal">
                Cancelar
              </flux:button>
              <flux:button variant="primary" wire:click="savePeriod" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $editingPeriod ? 'Actualizar' : 'Crear' }}</span>
                <span wire:loading>{{ $editingPeriod ? 'Actualizando...' : 'Creando...' }}</span>
              </flux:button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
      x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data="{ show: true }"
      x-show="show" x-init="setTimeout(() => show = false, 5000)">
      {{ session('error') }}
    </div>
  @endif
</div>
