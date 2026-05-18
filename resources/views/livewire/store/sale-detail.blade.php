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
              <h3 class="text-lg font-medium text-gray-800">Detalles de Venta</h3>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-8">
              {{-- Sale Info --}}
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Fecha</p>
                  <p class="text-base font-medium text-gray-800">{{ $sale->formatted_date }}</p>
                </div>
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Hora</p>
                  <p class="text-base font-medium text-gray-800">{{ $sale->formatted_time }}</p>
                </div>
                <div>
                  <p class="font-medium text-gray-600 mb-0.5">Total</p>
                  <p class="text-base font-medium text-gray-800">${{ number_format($sale->total, 2) }}</p>
                </div>
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
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                      <tr>
                        <th scope="row" colspan="3" class="p-4 text-right text-base font-medium text-gray-800">
                          Total:
                        </th>
                        <td class="px-4 py-4 text-right text-lg font-semibold text-gray-800">
                          ${{ number_format($sale->total, 2) }}
                        </td>
                      </tr>
                    </tfoot>
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
