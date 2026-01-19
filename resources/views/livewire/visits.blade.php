<x-slot:title>Visitas</x-slot:title>
<x-slot:subtitle>Registro y control de visitas</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Hoy</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->today }}</div>
    </div>

    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Esta semana</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->thisWeek }}</div>
    </div>

    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Este mes</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->thisMonth }}</div>
    </div>

    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Total</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->total }}</div>
    </div>
  </div>

  <!-- Action Bar -->
  <div class="flex justify-end">
    <flux:button variant="primary" icon="plus" wire:click="create">
      Registrar Visita
    </flux:button>
  </div>

  <!-- Visits List -->
  @if ($this->visits->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg border border-gray-200">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="calendar-days" class="mx-auto h-12 w-12" />
      </div>
      <h3 class="mt-2 font-medium text-gray-900">No hay visitas registradas</h3>
      <p class="mt-1.5 text-sm text-gray-600">Comienza registrando una nueva visita.</p>
      <div class="mt-6">
        <flux:button variant="primary" icon="plus" wire:click="create">
          Registrar Visita
        </flux:button>
      </div>
    </div>
  @else
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left font-medium text-gray-700">
                Fecha y Hora
              </th>
              <th scope="col" class="px-6 py-3 text-left font-medium text-gray-700">
                Precio
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Tipo de Visita
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->visits as $visit)
              <tr wire:key="{{ $visit->id }}">
                {{-- DateTime --}}
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-row gap-2 font-medium text-gray-800">
                    <span>
                      {{ $visit->formatted_visit_at }}
                    </span>
                  </div>
                </td>
                {{-- Price --}}
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <span class="font-medium text-gray-800">${{ number_format($visit->price_paid) }}</span>
                </td>
                {{-- Visit Type --}}
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <span class="font-medium text-gray-800 text-sm uppercase tracking-wide">
                    {{ $visit->visitType->name }}
                  </span>
                </td>
                {{-- Actions --}}
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <flux:button variant="ghost" size="sm" icon="pencil-square" class="text-gray-800!" wire:click="edit({{ $visit->id }})" />
                    <flux:button variant="ghost" size="sm" icon="trash" class="text-gray-800!"
                    wire:click="delete({{ $visit->id }})"
                    wire:confirm="¿Seguro que deseas eliminar esta visita?" />
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      {{ $this->visits->links('pagination.custom') }}
    </div>
  @endif

  <!-- Create/Edit Form Modal -->
  @if ($showFormModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>
            <h3 class="px-6 py-4 text-lg font-medium text-gray-900 border-b border-gray-200">
              {{ $editingVisit ? 'Editar Visita' : 'Registrar Visita' }}
            </h3>

            <form wire:submit.prevent="save">
              <div class="px-6 py-4 pt-5 space-y-4">
                <!-- Visit Type -->
                <flux:field>
                  <flux:label>Tipo de Visita</flux:label>
                  <flux:select wire:model.live="visit_type_id" placeholder="Selecciona un tipo">
                    @foreach ($this->visitTypes as $type)
                      <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="visit_type_id" />
                </flux:field>

                <div class="grid grid-cols-2 gap-4">
                  <!-- Date -->
                  <flux:field>
                    <flux:label>Fecha</flux:label>
                    <flux:input type="date" wire:model="visit_date" />
                    <flux:error name="visit_date" />
                  </flux:field>

                  <!-- Time -->
                  <flux:field>
                    <flux:label>Hora</flux:label>
                    <flux:input type="time" wire:model="visit_time" />
                    <flux:error name="visit_time" />
                  </flux:field>
                </div>

                <!-- Price -->
                <flux:field>
                  <flux:label>Precio Pagado</flux:label>
                  <flux:input type="number" step="0.01" wire:model="price_paid" prefix="$" />
                  <flux:error name="price_paid" />
                </flux:field>
              </div>

              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  {{-- @if (session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data="{ show: true }"
      x-show="show" x-init="setTimeout(() => show = false, 3000)">
      {{ session('message') }}
    </div>
  @endif --}}
</div>
