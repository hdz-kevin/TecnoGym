<div>
  @if ($showModal && $membership)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-5xl"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-3.5 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                Historial de Membresía
              </h3>
              <flux:button wire:click="closeModal" variant="ghost">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </flux:button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 pt-5">
              <!-- Membership Info -->
              <div class="bg-gray-50 rounded-lg p-4 mb-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-gray-800">Socio</p>
                    <p class="font-medium text-lg text-gray-800">
                      {{ $membership->member->name }}
                    </p>
                  </div>
                  <div>
                    <p class="text-gray-800">Plan</p>
                    <p class="font-medium text-lg text-gray-800">
                      {{ $membership->plan_name }}
                    </p>
                  </div>
                  <div>
                    <p class="text-gray-800">Estado</p>
                    @php
                      $status = $membership->status;
                    @endphp
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-base font-medium
                      {{ $status == \App\Enums\MembershipStatus::ACTIVE ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }}"
                    >
                      {{ $status->label() }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Period History -->
              <div>
                <h4 class="text-lg font-medium text-gray-800 mb-6">Historial de Periodos</h4>

                @if ($membership->periods->count() > 0)
                  <div class="max-h-72 overflow-y-auto scroll-smooth px-2">
                    <div class="relative border-l border-gray-200 ml-3 space-y-4">
                      @foreach ($membership->periods as $period)
                        <div wire:key="{{ $period->id }}" class="relative ml-5">
                          <!-- Timeline Dot -->
                          <span
                            class="absolute -left-9 flex h-7 w-7 items-center justify-center rounded-full ring-8 ring-white
                            {{ $period->status->value === 'completed' ? 'bg-gray-100' : 'bg-green-100' }}"
                          >
                            @if ($period->status->value === 'completed')
                              <svg class="h-3.5 w-3.5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd" />
                              </svg>
                            @else
                              <svg class="h-3.5 w-3.5 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                  clip-rule="evenodd" />
                              </svg>
                            @endif
                          </span>

                          <!-- Content Card -->
                          <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-white border border-gray-200 rounded-lg p-4 py-5 shadow-xs hover:shadow-sm transition-shadow">
                            <div class="flex items-center gap-2">
                              <h3 class="text-base font-medium text-gray-900">
                                {{ $period->start_date->format('d M Y') }} -> {{ $period->end_date->format('d M Y') }}
                              </h3>
                              <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $period->status->value === 'completed' ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-800' }}"
                              >
                                {{ $period->status->label() }}
                              </span>
                            </div>
                            <div class="mt-2 sm:mt-0 text-right">
                              <p class="text-lg font-semibold text-gray-900">${{ number_format($period->price_paid) }}
                              </p>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>

                  <!-- Summary -->
                  <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                      <span class="text-base text-gray-700">Total pagado</span>
                      <span class="text-xl font-semibold text-gray-900">
                        ${{ number_format($membership->total_paid) }}
                      </span>
                    </div>
                  </div>
                @else
                  <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <div class="mx-auto h-12 w-12 text-gray-500">
                      <flux:icon icon="calendar-days" class="w-full h-full" />
                    </div>
                    <h3 class="mt-2 text-base font-medium text-gray-900">Historial vacío</h3>
                    <p class="mt-1 text-sm text-gray-600">Esta membresía aún no tiene periodos registrados.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
