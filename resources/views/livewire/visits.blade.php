<x-slot:title>Visitas</x-slot:title>
<x-slot:subtitle>Registro y control de visitas</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  <!-- Action Bar -->
  <div class="flex justify-between items-center">
    <div></div>
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
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Fecha y Hora
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tipo de Visita
              </th>
              <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Precio
              </th>
              <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->visits as $visit)
              <tr wire:key="{{ $visit->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="font-medium text-gray-900">
                      {{ \Carbon\Carbon::parse($visit->visit_at)->translatedFormat('d F Y') }}
                    </span>
                    <span class="text-xs text-gray-500">
                      {{ \Carbon\Carbon::parse($visit->visit_at)->format('H:i A') }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $visit->visitType->name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <span class="font-medium text-gray-900">${{ number_format($visit->price_paid, 2) }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <flux:dropdown>
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                    <flux:menu>
                      <flux:menu.item icon="pencil-square" wire:click="edit({{ $visit->id }})">Editar
                      </flux:menu.item>
                      <flux:menu.separator />
                      <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $visit->id }})">Eliminar
                      </flux:menu.item>
                    </flux:menu>
                  </flux:dropdown>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $this->visits->links() }}
    </div>
  @endif

  <!-- Create/Edit Form Modal -->
  <flux:modal wire:model="showFormModal" class="md:w-96">
    <div class="space-y-6">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900">
          {{ $editingVisit ? 'Editar Visita' : 'Registrar Visita' }}
        </h3>
        <p class="mt-1 text-sm text-gray-500">
          {{ $editingVisit ? 'Modifica los detalles de la visita.' : 'Ingresa los datos de la nueva visita.' }}
        </p>
      </div>

      <form wire:submit.prevent="save" class="space-y-4">
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

        <div class="flex justify-end space-x-2 mt-6">
          <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
          <flux:button type="submit" variant="primary">Guardar</flux:button>
        </div>
      </form>
    </div>
  </flux:modal>

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data="{ show: true }"
      x-show="show" x-init="setTimeout(() => show = false, 3000)">
      {{ session('message') }}
    </div>
  @endif
</div>
