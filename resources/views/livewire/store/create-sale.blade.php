<div>
  @if ($showModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-5xl"
            wire:click.stop>

            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-800">Nueva Venta</h3>
            </div>

            <form wire:submit.prevent="saveSale">
              {{-- Modal Body --}}
              <div class="p-6 space-y-7">
                {{-- Product Search --}}
                <flux:field>
                  <flux:label class="text-lg">Buscar Productos</flux:label>
                  <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <flux:icon icon="magnifying-glass" variant="mini" class="text-gray-400" />
                      </div>
                      <input
                        type="text"
                        wire:model.live.debounce.300ms="productSearch"
                        @focus="open = true"
                        @input="open = true"
                        x-init="setTimeout(() => $el.focus(), 100)"
                        x-on:keydown.escape="open = false"
                        id="search-input"
                        placeholder="Buscar productos por su nombre"
                        autocomplete="off"
                        class="block w-full pl-9 pr-3 py-2 text-base border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500"
                      />
                    </div>

                    {{-- Results dropdown --}}
                    <div
                      x-show="open && $wire.productSearch.length > 0"
                      x-transition:enter="transition ease-out duration-100"
                      x-transition:enter-start="opacity-0 scale-95"
                      x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="transition ease-in duration-75"
                      x-transition:leave-start="opacity-100 scale-100"
                      x-transition:leave-end="opacity-0 scale-95"
                      class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-y-auto"
                    >
                      @forelse($this->productResults as $product)
                        <button
                          type="button"
                          wire:key="product-result-{{ $product->id }}"
                          wire:click="selectProduct({{ $product->id }})"
                          @click="open = false"
                          class="w-full text-left px-4 py-3.5 hover:bg-gray-50 flex items-center justify-between transition-colors cursor-pointer border-b border-gray-200 last:border-b-0 {{ $product->stock === 0 ? 'opacity-70 cursor-not-allowed' : '' }}"
                          {{ $product->stock === 0 ? 'disabled' : '' }}
                        >
                          <div>
                            <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                              <span class="font-medium text-gray-800">${{ number_format($product->price, 2) }}</span>
                              @if($product->stock !== null)
                                <span class="font-medium">-</span>
                                <span class="font-medium text-gray-800">Stock: {{ $product->stock }}</span>
                              @endif
                            </div>
                          </div>
                          @if($product->stock === 0)
                            <span class="text-sm font-medium text-red-700 bg-red-50 px-2.5 py-1 rounded-lg">Agotado</span>
                          @endif
                        </button>
                      @empty
                        <div class="px-4 py-4 text-sm text-gray-600 text-center">
                          No se encontraron productos activos
                        </div>
                      @endforelse
                    </div>
                  </div>
                </flux:field>

                {{-- Selected Products List --}}
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <flux:label class="text-lg">Productos agregados</flux:label>
                    @php $cartCount = count($cart); @endphp
                    <span class="font-medium text-gray-700">{{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }}</span>
                  </div>

                  @if(empty($cart))
                    <div class="text-center py-14 bg-gray-50 border border-gray-200 border-dashed rounded-lg">
                      <flux:icon icon="shopping-cart" class="mx-auto h-8 w-8 text-gray-500 mb-2" />
                      <p class="text-gray-600">Agrega productos a la venta</p>
                    </div>
                  @else
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                      {{-- Added products table --}}
                      <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                          <tr class="text-gray-600">
                            <th scope="col" class="px-4 py-3 text-left font-medium ">Producto</th>
                            <th scope="col" class="px-4 py-3 text-left font-medium w-28">Precio</th>
                            <th scope="col" class="px-4 py-3 text-center font-medium w-32">Cantidad</th>
                            <th scope="col" class="px-4 py-3 text-right font-medium w-28">Subtotal</th>
                            <th scope="col" class="px-4 py-3 text-right font-medium w-16"></th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                          @foreach($cart as $index => $item)
                            <tr wire:key="sale-item-{{ $item['product_id'] }}">
                              <td class="px-4 py-3">
                                <div class="text-base font-medium text-gray-900">{{ $item['product_name'] }}</div>
                                @if($item['product_stock'] !== null)
                                  <div class="text-base text-gray-800 mt-1">Stock: {{ $item['product_stock'] }}</div>
                                @endif
                              </td>
                              <td class="px-4 py-3 text-base font-medium text-gray-800">
                                ${{ number_format($item['product_price'], 2) }}
                              </td>
                              <td class="px-4 py-3">
                                <input
                                  type="number"
                                  min="1"
                                  value="{{ $item['quantity'] }}"
                                  wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                  class="block w-full text-center py-1.5 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                >
                              </td>
                              <td class="px-4 py-3 font-medium text-gray-800 text-right">
                                ${{ number_format($item['subtotal'], 2) }}
                              </td>
                              <td class="px-4 py-3 text-right">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-700 hover:text-red-600 transition-colors">
                                  <flux:icon icon="trash" variant="mini" />
                                </button>
                              </td>
                            </tr>
                            @error("cart.{$index}.quantity")
                              <tr>
                                <td colspan="5" class="px-4 py-2 text-base text-red-700 bg-red-50/50 border-t-0">
                                  {{ $message }}
                                </td>
                              </tr>
                            @enderror
                          @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                          <tr>
                            <th scope="row" colspan="3" class="px-4 py-4 text-right text-base font-medium text-gray-800">
                              Total:
                            </th>
                            <td class="px-4 py-4 text-right text-lg font-semibold text-gray-800">
                              ${{ number_format($this->saleTotal, 2) }}
                            </td>
                            <td></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  @endif

                  @error('cart')
                    <p class="mt-2 text-red-700">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              {{-- Modal Footer --}}
              <div class="flex items-center justify-end gap-3 px-6 py-5 border-t border-gray-200">
                <flux:button class="text-base!" variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button class="text-base!" type="submit" variant="primary">Confirmar Venta</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

</div>
