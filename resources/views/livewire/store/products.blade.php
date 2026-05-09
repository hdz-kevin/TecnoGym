<x-slot:title>Productos</x-slot:title>
<x-slot:subtitle>Inventario de productos</x-slot:subtitle>

<div class="space-y-8">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $statusFilter === null ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-300' }}"
      wire:click="setStatusFilter(null)"
    >
      <div class="font-medium text-gray-500">Total</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->totalProducts }}</div>
    </div>

    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $statusFilter === 'active' ? 'border-green-300 ring-1 ring-green-200' : 'border-gray-300' }}"
      wire:click="setStatusFilter('active')"
    >
      <div class="font-medium text-gray-500">Activos</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->activeProducts }}</div>
    </div>

    <div
      class="bg-white py-4 px-5 rounded-lg border shadow-sm transition-all hover:shadow-md cursor-pointer
        {{ $statusFilter === 'inactive' ? 'border-gray-400 ring-1 ring-gray-300' : 'border-gray-300' }}"
      wire:click="setStatusFilter('inactive')"
    >
      <div class="font-medium text-gray-500">Inactivos</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->inactiveProducts }}</div>
    </div>
  </div>

  <!-- Search and Actions -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input type="text" placeholder="Buscar por nombre..."
          wire:model.live.debounce.300ms="search"
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-600" />
      </div>
    </div>
    <div>
      <flux:button variant="primary" icon="plus" wire:click="create">
        Nuevo Producto
      </flux:button>
    </div>
  </div>

  <!-- Products List -->
  @if ($this->products->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg border border-gray-200">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="cube" class="mx-auto h-12 w-12" />
      </div>
      @if ($statusFilter || $search)
        <h3 class="mt-2 font-medium text-lg text-gray-800">No hay resultados</h3>
        <p class="mt-1.5 text-gray-600">No hay productos que coincidan con tu búsqueda.</p>
      @else
        <h3 class="mt-2 font-medium text-lg text-gray-800">No hay productos registrados</h3>
        <p class="mt-1.5 text-gray-600">Comienza agregando un nuevo producto.</p>
      @endif
    </div>
  @else
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left font-medium text-gray-700">
                Nombre
              </th>
              <th scope="col" class="px-6 py-3 text-left font-medium text-gray-700">
                Precio
              </th>
              <th scope="col" class="px-6 py-3 text-center font-medium text-gray-700">
                Stock
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Estado
              </th>
              <th scope="col" class="px-6 py-3 text-right font-medium text-gray-700">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->products as $product)
              <tr wire:key="{{ $product->id }}">
                {{-- Name & Description --}}
                <td class="pl-6 py-5">
                  <div class="font-medium text-lg {{ $product->is_active ? 'text-gray-800' : 'text-gray-500' }}">{{ $product->name }}</div>
                  @if ($product->description)
                    <div class="mt-2 {{ $product->is_active ? 'text-gray-700' : 'text-gray-500' }}">{{ $product->description }}</div>
                  @endif
                </td>
                {{-- Price --}}
                <td class="px-6 py-5 whitespace-nowrap text-left">
                  <span class="font-medium text-lg {{ $product->is_active ? 'text-gray-800' : 'text-gray-500' }}">${{ number_format($product->price, 2) }}</span>
                </td>
                {{-- Stock --}}
                <td class="px-6 py-5 whitespace-nowrap text-center">
                  @if ($product->stock === 0)
                    <span class="font-medium text-base {{ $product->is_active ? 'text-red-700' : 'text-gray-500' }}">Agotado</span>
                  @else 
                    <span class="font-medium {{ $product->is_active ? 'text-gray-800' : 'text-gray-500' }} {{ $product->stock != null ? 'text-lg' : ''  }}">
                      {{ $product->stock ?? 'No definido' }}
                    </span>
                  @endif
                </td>
                {{-- Status --}}
                <td class="px-6 py-5 whitespace-nowrap text-right">
                  @if ($product->is_active)
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                      Activo
                    </span>
                  @else
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                      Inactivo
                    </span>
                  @endif
                </td>
                {{-- Actions --}}
                <td class="px-6 py-5 whitespace-nowrap text-right">
                  <flux:button size="sm" variant="outline" wire:click="edit({{ $product->id }})">
                    Editar
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
      {{ $this->products->links('pagination.custom') }}
    </div>
  @endif

  <!-- Create Product Modal -->
  @if ($showFormModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}
              </h3>
            </div>

            <form wire:submit.prevent="saveProduct">
              <!-- Modal Body -->
              <div class="px-6 py-4 space-y-5">
                <!-- Name -->
                <flux:field>
                  <flux:label for="name">Nombre</flux:label>
                  <flux:input wire:model="name" id="name" placeholder="Nombre del producto" />
                  <flux:error name="name" />
                </flux:field>

                <!-- Price -->
                <flux:field>
                  <flux:label for="price">Precio</flux:label>
                  <flux:input wire:model="price" id="price" type="number" step="0.01" min="0" placeholder="Precio del producto" />
                  <flux:error name="price" />
                </flux:field>

                <!-- Description -->
                <flux:field>
                  <flux:label for="description">Descripción <span class="text-gray-500 font-normal ml-1"> (opcional)</span></flux:label>
                  <flux:input wire:model="description" id="description" placeholder="Descripción del producto" />
                  <flux:error name="description" />
                </flux:field>

                <!-- Stock -->
                <flux:field>
                  <flux:label for="stock">Stock <span class="text-gray-500 font-normal ml-1"> (opcional)</span></flux:label>
                  <flux:input wire:model="stock" id="stock" type="number" min="0" step="1" placeholder="Stock disponible" />
                  <flux:error name="stock" />
                </flux:field>

                <!-- Estado -->
                <flux:field>
                  <flux:label>Estado</flux:label>
                  <label class="inline-flex items-center gap-3 cursor-pointer" x-init="console.log($wire.is_active)">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="text-sm font-medium text-gray-700" x-text="$wire.is_active ? 'Activo' : 'Inactivo'"></span>
                  </label>
                </flux:field>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingProduct ? 'Guardar cambios' : 'Guardar' }}</flux:button>
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
