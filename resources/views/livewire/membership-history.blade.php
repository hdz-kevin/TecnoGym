<div>
  @if ($showModal && $membership)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-5xl"
            wire:click.stop>

            <!-- Modal Body -->
            <div class="px-10 py-8">
              <!-- Header matching other modals -->
              <div class="flex justify-between items-start mb-8">
                <div>
                  <h2 class="text-2xl font-bold text-gray-900">Historial de Membresía</h2>
                </div>
              </div>

              <!-- Period History -->
              <div>
                @if ($membership->periods->count() > 0)
                  <div class="max-h-96 overflow-y-auto scroll-smooth pr-2">
                    <div class="relative border-l border-gray-200 ml-3 space-y-5">
                      @foreach ($membership->periods as $period)
                        <div wire:key="{{ $period->id }}" class="relative ml-6">
                          <!-- Timeline Dot -->
                          <span
                            class="absolute -left-[30px] flex h-6 w-6 items-center justify-center rounded-full ring-4 ring-white
                            {{ $period->status->value === 'completed' ? 'bg-gray-200' : 'bg-green-500' }}"
                          >
                          </span>

                          <!-- Content Card -->
                          <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-white border border-gray-100 rounded-xl p-5 shadow-xs hover:shadow-md transition-shadow hover:border-gray-200">
                            <div class="flex flex-col gap-1">
                              <h3 class="text-base font-bold text-gray-800">
                                {{ $period->start_date->format('d M, Y') }} — {{ $period->end_date->format('d M, Y') }}
                              </h3>
                              <div class="flex items-center gap-2">
                                <span
                                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide
                                  {{ $period->status->value === 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }}"
                                >
                                  {{ $period->status->label() }}
                                </span>
                              </div>
                            </div>
                            <div class="mt-4 sm:mt-0 text-right">
                              <p class="text-xl font-semibold text-gray-800">${{ number_format($period->price_paid) }}</p>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @else
                  <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <div class="mx-auto h-12 w-12 text-gray-300 mb-3">
                      <flux:icon icon="calendar-days" class="w-full h-full" />
                    </div>
                    <h3 class="text-base font-medium text-gray-900">Historial vacío</h3>
                    <p class="mt-1 text-sm text-gray-500">Esta membresía aún no tiene periodos registrados.</p>
                  </div>
                @endif
              </div>

              <!-- Unified Footer -->
              <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                <div>
                   @if ($membership->periods->count() > 0)
                     <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Total pagado</span>
                     <div class="text-2xl font-bold text-gray-800">${{ number_format($membership->total_paid) }}</div>
                   @endif
                </div>
                <flux:button wire:click="closeModal">
                  Cerrar
                </flux:button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
