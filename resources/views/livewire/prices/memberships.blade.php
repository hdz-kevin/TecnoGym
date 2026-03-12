<x-slot:subtitle>Gestiona los precios de las membresías y sus duraciones</x-slot:subtitle>

<div>
  <div class="pt-4 -mt-6 space-y-6">
    @if ($membershipTypes->count() > 0)
      <div class="flex justify-end">
        <flux:button variant="primary" icon="plus" wire:click="createMembershipTypeModal">
          Tipo de Membresía
        </flux:button>
      </div>
    @endif

    <!-- Plan Types Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7 items-start">
      @forelse ($membershipTypes as $membershipType)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm" wire:key="{{ $membershipType->id }}">
          <!-- Plan Type Header -->
          <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-[19px] font-medium text-gray-900">{{ $membershipType->name }}</h3>
              </div>
              <div class="flex items-center gap-2">
                @if ($membershipType->durations->count() === 0)
                  <flux:button
                    variant="outline"
                    size="sm"
                    wire:click="deleteMembershipType({{ $membershipType->id }})"
                    wire:confirm="¿Estás seguro de eliminar este tipo de membresía?"
                  >
                    Eliminar
                  </flux:button>
                @endif
                <flux:button
                  variant="primary"
                  size="sm"
                  wire:click="editMembershipTypeModal({{ $membershipType->id }})"
                >
                  Editar
                </flux:button>
              </div>
            </div>
          </div>

          <!-- Durations -->
          <div class="p-6">
            @if ($membershipType->durations->count() > 0)
              <div class="space-y-3 mb-5">
                @foreach ($membershipType->durations as $duration)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg" wire:key="{{ $duration->id }}">
                    <div class="flex-1">
                      <div class="flex items-center gap-2">
                        <h3 class="font-medium text-gray-900">{{ $duration->name }}</h3>
                        <p>-</p>
                        <p class="text-medium font-semibold text-gray-900">${{ number_format($duration->price) }}</p>
                      </div>
                      <p class="text-sm text-gray-700 mt-1">
                        {{ $duration->formatted }}
                      </p>
                    </div>
                    <div class="flex items-center gap-1 ml-4">
                      @if ($duration->periods->count() === 0)
                        <flux:button
                          size="sm"
                          variant="outline"
                          wire:click="deleteDuration({{ $duration->id }})"
                          wire:confirm="¿Estás seguro de eliminar esta duración?"
                        >
                          Eliminar
                        </flux:button>
                      @endif
                      <flux:button
                        size="sm"
                        variant="outline"
                        wire:click="editDurationModal({{ $duration->id }})"
                      >
                        Editar
                      </flux:button>
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
                <p class="text-sm text-gray-500 mb-3">No hay duraciones configuradas</p>
              </div>
            @endif

            <!-- New Duration Button -->
            <flux:button variant="outline" icon="plus" wire:click="createDurationModal({{ $membershipType->id }})"
              class="w-full">
              Añadir Duración
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
          <h3 class="text-lg font-medium text-gray-900 mb-1">No hay precios de membresías configurados</h3>
          <p class="text-gray-500 mb-6">Comienza creando tu primer tipo de membresía</p>
          <flux:button variant="primary" icon="plus" wire:click="createMembershipTypeModal">
            Tipo de Membresía
          </flux:button>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Plan Type Modal -->
  @if ($showMembershipTypeModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeMembershipTypeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingMembershipType ? 'Editar Tipo de Membresía' : 'Nuevo Tipo de Membresía' }}
              </h3>
            </div>

            <form wire:submit.prevent="saveMembershipType">
              <!-- Modal Body -->
              <div class="px-6 py-4">
                <flux:field>
                  <flux:label for="membership_type_name">Nombre</flux:label>
                  <flux:input wire:model="membership_type_name" id="membership_type_name" placeholder="Ej: General, Estudiante, Premium" />
                  <flux:error name="membership_type_name" />
                </flux:field>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeMembershipTypeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingMembershipType ? 'Guardar cambios' : 'Guardar' }}</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Plan Modal -->
  @if ($showDurationModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeDurationModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl"
            wire:click.stop>

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ ($editingDuration ? 'Editar Duración' : 'Nueva Duración').' - '.$selectedMembershipType->name }}
              </h3>
            </div>

            <form wire:submit.prevent="saveDuration">
              <!-- Body -->
              <div class="px-6 py-4 space-y-4">
              <!-- Name -->
                <flux:field>
                  <flux:label for="duration_name">Nombre</flux:label>
                  <flux:input wire:model="duration_name" id="duration_name" placeholder="Ej: Semanal, Mensual, Bimestral" />
                  <flux:error name="duration_name" />
                </flux:field>

              <!-- Duration -->
                <div class="grid grid-cols-2 gap-4">
                  <flux:field>
                    <flux:label for="duration_amount">Cantidad</flux:label>
                    <flux:input wire:model="duration_amount" id="duration_amount" type="number" min="1"
                      placeholder="Ej: 1, 2, 3" />
                    <flux:error name="duration_amount" />
                  </flux:field>

                  <flux:field>
                    <flux:label for="duration_unit">Unidad de tiempo</flux:label>
                    <flux:select wire:model="duration_unit" id="duration_unit" placeholder="Elige una unidad de tiempo">
                      <flux:select.option value="day">Día(s)</flux:select.option>
                      <flux:select.option value="week">Semana(s)</flux:select.option>
                      <flux:select.option value="month">Mes(es)</flux:select.option>
                    </flux:select>
                    <flux:error name="duration_unit" />
                  </flux:field>
                </div>

              <!-- Price -->
                <flux:field>
                  <flux:label for="duration_price">Precio</flux:label>
                  <flux:input wire:model="duration_price" id="duration_price" type="number" min="1" placeholder="El precio de la duración" />
                  <flux:error name="duration_price" />
                </flux:field>
              </div>

              <!-- Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeDurationModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingDuration ? 'Guardar cambios' : 'Guardar' }}</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div
      wire:key="{{ Str::random() }}"
      class="fixed font-medium top-5 right-5 bg-green-50 text-green-800 border border-green-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div
      wire:key="{{ Str::random() }}"
      class="fixed font-medium top-5 right-5 bg-red-50 text-red-800 border border-red-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('error') }}
    </div>
  @endif
</div>
