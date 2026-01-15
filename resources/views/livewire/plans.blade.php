<div>
  <div class="p-6 pt-4 -mt-6 space-y-6">
    @if ($planTypes->count() > 0)
      <div class="flex justify-end">
        <flux:button variant="primary" icon="plus" wire:click="createTypeModal">
          Tipo de Plan
        </flux:button>
      </div>
    @endif

    <!-- Plan Types Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7 items-start">
      @forelse ($planTypes as $planType)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm" wire:key="{{ $planType->id }}">
          <!-- Plan Type Header -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-[19px] font-medium text-gray-900">{{ $planType->name }}</h3>
              </div>
              <div class="flex items-center gap-2">
                <flux:button
                  class="border text-indigo-600! hover:text-indigo-700! hover:bg-indigo-50!"
                  variant="ghost"
                  size="sm"
                  wire:click="editTypeModal({{ $planType->id }})"
                  icon="pencil"
                >
                  Editar
                </flux:button>
                @if ($planType->plans->count() === 0)
                  <flux:button
                    class="border text-red-600!  hover:text-red-700! hover:bg-red-50!"
                    variant="ghost"
                    size="sm"
                    icon="trash"
                    wire:click="deleteType({{ $planType->id }})"
                    wire:confirm="¿Estás seguro de eliminar este tipo de plan?"
                  >
                    Eliminar
                  </flux:button>
                @endif
              </div>
            </div>
          </div>

          <!-- Plans -->
          <div class="p-6">
            @if ($planType->plans->count() > 0)
              <div class="space-y-3 mb-5">
                @foreach ($planType->plans as $plan)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg" wire:key="{{ $plan->id }}">
                    <div class="flex-1">
                      <div class="flex items-center gap-2">
                          <h3 class="font-medium text-gray-900">{{ $plan->name }}</h3>
                          <p>-</p>
                          <p class="text-lg font-semibold text-gray-900">${{ number_format($plan->price) }}</p>
                      </div>
                      <p class="text-sm text-gray-600">
                        {{ $plan->formatted_duration }}
                      </p>
                    </div>
                    <div class="flex items-center gap-1 ml-4">
                      <flux:button size="sm" variant="filled" wire:click="editPlanModal({{ $plan->id }})"
                        class="bg-indigo-50! text-indigo-600! hover:bg-indigo-100! hover:text-indigo-700!">
                        Editar
                      </flux:button>
                      @if ($plan->memberships->count() === 0)
                        <flux:button size="sm" variant="filled" wire:click="deletePlan({{ $plan->id }})"
                          wire:confirm="¿Estás seguro de eliminar este plan?"
                          class="bg-red-50! text-red-600! hover:bg-red-100! hover:text-red-700!">
                          Eliminar
                        </flux:button>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-6">
                <div class="text-gray-400 mb-2">
                  <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <p class="text-sm text-gray-500 mb-3">No hay planes configurados</p>
              </div>
            @endif

            <!-- New Plan Button -->
            <flux:button variant="outline" icon="plus" wire:click="createPlanModal({{ $planType->id }})"
              class="w-full">
              Agregar Plan
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
          <h3 class="text-lg font-medium text-gray-900 mb-1">No hay planes configurados</h3>
          <p class="text-gray-500 mb-6">Comienza creando tu primer tipo de plan</p>
          <flux:button variant="primary" icon="plus" wire:click="createTypeModal">
            Crear Uno
          </flux:button>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Plan Type Modal -->
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
                {{ $editingType ? 'Editar Tipo de Plan' : 'Nuevo Tipo de Plan' }}
              </h3>
              <button wire:click="closeTypeModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form wire:submit.prevent="saveType">
              <!-- Modal Body -->
              <div class="px-6 py-4">
                <flux:field>
                  <flux:label for="type_name">Nombre</flux:label>
                  <flux:input wire:model="type_name" id="type_name" placeholder="Ej: General, Estudiante, Premium" />
                  <flux:error name="type_name" />
                </flux:field>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeTypeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingType ? 'Guardar cambios' : 'Guardar' }}</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Plan Modal -->
  @if ($showPlanModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closePlanModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingPlan ? 'Editar Plan' : 'Nuevo Plan' }}
                @if ($selectedTypeForPlan)
                  <span class="text-sm font-normal text-gray-500">- {{ $selectedTypeForPlan->name }}</span>
                @endif
              </h3>
              <button wire:click="closePlanModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form wire:submit.prevent="savePlan">
              <!-- Body -->
              <div class="px-6 py-4 space-y-4">
              <!-- Name -->
                <flux:field>
                  <flux:label for="plan_name">Nombre del plan *</flux:label>
                  <flux:input wire:model="plan_name" id="plan_name" placeholder="Ej: Mensual, Semanal, Anual" />
                  <flux:error name="plan_name" />
                </flux:field>

              <!-- Duration -->
                <div class="grid grid-cols-2 gap-4">
                  <flux:field>
                    <flux:label for="duration_value">Duración *</flux:label>
                    <flux:input wire:model="duration_value" id="duration_value" type="number" min="1"
                      placeholder="1" />
                    <flux:error name="duration_value" />
                  </flux:field>

                  <flux:field>
                    <flux:label for="duration_unit">Unidad *</flux:label>
                    <flux:select wire:model="duration_unit" id="duration_unit" placeholder="Seleccionar">
                      <flux:select.option value="day">Día(s)</flux:select.option>
                      <flux:select.option value="week">Semana(s)</flux:select.option>
                      <flux:select.option value="month">Mes(es)</flux:select.option>
                    </flux:select>
                    <flux:error name="duration_unit" />
                  </flux:field>
                </div>

              <!-- Price -->
                <flux:field>
                  <flux:label for="price">Precio (MXN) *</flux:label>
                  <flux:input wire:model="price" id="price" type="number" min="1" placeholder="400" />
                  <flux:error name="price" />
                </flux:field>
              </div>

              <!-- Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closePlanModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingPlan ? 'Guardar cambios' : 'Guardar' }}</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  {{-- @if (session()->has('message'))
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
  @endif --}}
</div>
