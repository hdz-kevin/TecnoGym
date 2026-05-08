<x-slot:title>Productos</x-slot:title>
<x-slot:subtitle>Inventario de productos</x-slot:subtitle>

<div class="space-y-6">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Total</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->totalProducts }}</div>
    </div>

    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Activos</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->activeProducts }}</div>
    </div>

    <div class="bg-white py-4 px-5 rounded-lg border border-gray-200 shadow-sm">
      <div class="font-medium text-gray-500">Inactivos</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800">{{ $this->inactiveProducts }}</div>
    </div>
  </div>

  <!-- Products List -->
  @if ($this->products->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg border border-gray-200">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="cube" class="mx-auto h-12 w-12" />
      </div>
      <h3 class="mt-2 font-medium text-gray-900">No hay productos registrados</h3>
      <p class="mt-1.5 text-sm text-gray-600">Comienza agregando un nuevo producto.</p>
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
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->products as $product)
              <tr wire:key="{{ $product->id }}">
                {{-- Name & Description --}}
                <td class="pl-6 py-5">
                  <div class="font-medium text-lg text-gray-800">{{ $product->name }}</div>
                  @if ($product->description)
                    <div class="text-gray-700 mt-1.5">{{ $product->description }}</div>
                  @endif
                </td>
                {{-- Price --}}
                <td class="px-6 py-5 whitespace-nowrap text-left">
                  <span class="font-medium text-lg text-gray-800">${{ number_format($product->price, 2) }}</span>
                </td>
                {{-- Stock --}}
                <td class="px-6 py-5 whitespace-nowrap text-center">
                  <span class="font-medium text-lg text-gray-800">{{ $product->stock ?? '—' }}</span>
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
</div>
