<div>
  @if ($showModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-5xl"
            wire:click.stop>

            {{-- Modal Header --}}
            <div class="px-6 py-5 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Detalles de Venta</h3>
            </div>

            {{-- Modal Body --}}
            <div class="p-7 pb-8 space-y-8">
              {{-- Sale Info --}}
              <div class="flex justify-between items-center gap-4">
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Fecha y Hora</p>
                    <div class="flex gap-2 font-medium text-gray-800 text-lg">
                      <span>
                        {{ $sale->formatted_date }}
                      </span>
                      <span class="text-gray-500">•</span>
                      <span>
                        {{ $sale->formatted_time }}
                      </span>
                    </div>
                </div>
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Productos</p>
                  <p class="font-medium text-gray-800 text-lg">
                    {{ $sale->productSales->sum('quantity') }}
                  </p>
                </div>
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Total</p>
                  <p class="font-medium text-gray-800 text-lg">${{ number_format($sale->total, 2) }}</p>
                </div>
                {{-- empty div for spacing --}}
                <div></div>
              </div>

              {{-- Products Table --}}
              <div>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr class="text-gray-600">
                        <th scope="col" class="px-4 py-3 text-left font-medium">Producto</th>
                        <th scope="col" class="px-4 py-3 text-left font-medium w-28">Precio</th>
                        <th scope="col" class="px-4 py-3 text-center font-medium w-28">Cantidad</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium w-28">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      @foreach ($sale->productSales as $item)
                        <tr wire:key="detail-item-{{ $item->id }}">
                          <td class="p-4">
                            <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                          </td>
                          <td class="p-4 font-medium text-gray-900">
                            ${{ number_format($item->product_price, 2) }}
                          </td>
                          <td class="p-4 font-medium text-center text-gray-900">
                            {{ $item->quantity }}
                          </td>
                          <td class="p-4 font-medium text-gray-900 text-right">
                            ${{ number_format($item->subtotal, 2) }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-5 border-t border-gray-200">
              <flux:button class="text-base!" wire:click="closeModal" icon="x-mark">Cerrar</flux:button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
