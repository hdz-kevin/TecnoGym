<x-slot:title>Ventas</x-slot:title>
<x-slot:subtitle>Historial de ventas</x-slot:subtitle>

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

  <!-- Search -->
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
    <div>
      <flux:button variant="primary" icon="plus" wire:click="createSale">
        Nueva Venta
      </flux:button>
    </div>
  </div>

  <!-- Sales List -->
  @if ($this->sales->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg border border-gray-200">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="shopping-bag" class="mx-auto h-12 w-12" />
      </div>
      @if ($this->total > 0)
        <h3 class="mt-2 font-medium text-lg text-gray-800">No hay resultados</h3>
        <p class="mt-1.5 text-gray-600">No hay ventas que coincidan con tu búsqueda.</p>
      @else
        <h3 class="mt-2 font-medium text-lg text-gray-800">No hay ventas registradas</h3>
        <p class="mt-1.5 text-gray-600">Comienza registrando tu primer venta.</p>
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
              <th scope="col" class="px-6 py-3 text-center font-medium text-gray-700">
                Productos
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Total
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->sales as $sale)
              <tr wire:key="{{ $sale->id }}">
                {{-- Date --}}
                <td class="px-6 py-5 whitespace-nowrap">
                  <div class="flex flex-col gap-2 font-medium text-gray-800">
                    <span>
                      {{ $sale->formatted_date }}
                    </span>
                    <span>
                      {{ $sale->formatted_time }}
                    </span>
                  </div>
                </td>
                {{-- Product Count --}}
                <td class="px-6 py-5 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-3.5 py-1 rounded-full font-medium bg-blue-100 text-blue-800">
                    {{ $sale->product_sales_count }} {{ $sale->product_sales_count === 1 ? 'producto' : 'productos' }}
                  </span>
                </td>
                {{-- Total --}}
                <td class="px-6 py-5 whitespace-nowrap text-right">
                  <span class="font-medium text-gray-800">${{ number_format($sale->total, 2) }}</span>
                </td>
                {{-- Actions --}}
                <td class="px-6 py-5 whitespace-nowrap text-right">
                  <flux:button size="sm" variant="outline" wire:click="showDetail({{ $sale->id }})">
                    Detalles
                  </flux:button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      {{ $this->sales->links('pagination.custom') }}
    </div>
  @endif

  <livewire:store.create-sale />

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
