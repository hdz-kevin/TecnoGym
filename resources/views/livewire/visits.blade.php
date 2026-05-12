<x-slot:title>Visitas</x-slot:title>
<x-slot:subtitle>Registro y control de visitas</x-slot:subtitle>

<div class="space-y-8">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $dateFilter === 'today' ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-300' }}"
      wire:click="setDateFilter('today')"
    >
      <div class="font-medium text-gray-500">Hoy</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->today }}</div>
    </div>

    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $dateFilter === 'week' ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-300' }}"
      wire:click="setDateFilter('week')"
    >
      <div class="font-medium text-gray-500">Esta semana</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->thisWeek }}</div>
    </div>

    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $dateFilter === 'month' ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-300' }}"
      wire:click="setDateFilter('month')"
    >
      <div class="font-medium text-gray-500">Este mes</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->thisMonth }}</div>
    </div>

    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $dateFilter === 'all' ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-300' }}"
      wire:click="setDateFilter('all')"
    >
      <div class="font-medium text-gray-500">Total</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->total }}</div>
    </div>
  </div>
  
  <!-- Search & Action Bar -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input type="date" placeholder="Buscar por fecha..."
          wire:model.live.debounce.300ms="search"
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-600" />
      </div>
    </div>

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
      @if ($this->total > 0)
        <h3 class="mt-2 font-medium text-gray-800">No hay resultados</h3>
        <p class="mt-1.5 text-gray-700">No hay visitas que coincidan con tu búsqueda.</p>
      @else
        <h3 class="mt-2 font-medium text-gray-800">No hay visitas registradas</h3>
        <p class="mt-1.5 text-gray-700">Comienza registrando una primer visita.</p>
      @endif
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
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->visits as $visit)
              <tr wire:key="{{ $visit->id }}">
                {{-- DateTime --}}
                <td class="px-6 py-5 whitespace-nowrap">
                  <div class="flex flex-col gap-2 font-medium text-gray-800">
                    <span>
                      {{ $visit->formatted_date }}
                    </span>
                    <span>
                      {{ $visit->formatted_time }}
                    </span>
                  </div>
                </td>
                {{-- Price --}}
                <td class="px-6 py-5 whitespace-nowrap text-left">
                  <span class="font-medium text-gray-800">${{ number_format($visit->price, 2) }}</span>
                </td>
                {{-- Actions --}}
                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <flux:button
                      variant="ghost"
                      size="sm"
                      wire:click="edit({{ $visit->id }})"
                    >
                      Editar
                    </flux:button>

                    <flux:button
                      variant="outline"
                      size="sm"
                      wire:click="delete({{ $visit->id }})"
                      wire:confirm="¿Seguro que deseas eliminar esta visita?"
                    >
                      Eliminar
                    </flux:button>
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

            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingVisit ? 'Editar Visita' : 'Registrar Visita' }}
              </h3>
            </div>

            <form wire:submit.prevent="save">
              <div class="px-6 py-4 pt-5 space-y-4">
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
                  <flux:label>Precio</flux:label>
                  <flux:input type="number" wire:model="price" />
                  <flux:error name="price" />
                </flux:field>
              </div>

              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingVisit ? 'Guardar cambios' : 'Guardar' }}</flux:button>
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
      class="fixed font-medium top-5 right-5 bg-green-50 text-green-800 border border-green-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      wire:key="{{ Str::random() }}"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div
      class="fixed font-medium top-5 right-5 bg-red-50 text-red-800 border border-red-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      wire:key="{{ Str::random() }}"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('error') }}
    </div>
  @endif
</div>
