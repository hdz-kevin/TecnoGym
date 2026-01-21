<div>
  @if ($showModal && $membership)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all w-full max-w-6xl"
            wire:click.stop>

            <div class="grid grid-cols-1 md:grid-cols-12 bg-white min-h-[600px]">
              <!-- Left Sidebar: Member & Membership Summary -->
              <div class="md:col-span-4 bg-gray-50 border-r border-gray-200 flex flex-col p-8">
                <!-- Member Profile Section -->
                <div class="flex flex-col items-center text-center mb-6">
                  <div class="relative mb-4">
                    @if($membership->member->photo)
                      <img
                        src="{{ Storage::url($membership->member->photo) }}"
                        class="h-36 w-36 rounded-full object-cover ring-4 ring-white shadow-sm"
                        alt="{{ $membership->member->name }}"
                      />
                    @else
                      <div class="h-36 w-36 rounded-full bg-white ring-4 ring-white shadow-sm flex items-center justify-center border border-gray-100">
                         <span class="text-3xl font-bold text-gray-300">{{ $membership->member->initials() }}</span>
                      </div>
                    @endif
                  </div>

                  <h2 class="text-xl font-bold text-gray-800 leading-tight mb-1">{{ $membership->member->name }}</h2>
                  <p class="text-base font-medium text-gray-700">#{{ $membership->member->code }}</p>
                </div>

                <hr class="border-gray-200 mb-6">

                <!-- Membership Specifics -->
                <div class="space-y-6">
                   <div class="">
                      @php
                        $statusColor = match ($membership->status) {
                            \App\Enums\MembershipStatus::ACTIVE => 'bg-green-100 text-green-700 border-green-200',
                            \App\Enums\MembershipStatus::EXPIRED => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        };
                      @endphp
                      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Estado</p>
                      <span
                        class="-ml-0.5 px-3.5 py-1.5 rounded-full text-sm font-semibold uppercase tracking-wide border {{ $statusColor }}">
                        Membresía {{ $membership->status->label() }}
                      </span>
                   </div>

                   <div>
                      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Plan</p>
                      <p class="text-lg font-semibold text-gray-800">{{ $membership->plan_name }}</p>
                   </div>

                   @if($membership->status == \App\Enums\MembershipStatus::ACTIVE)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Vence en</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $membership->expiration_time }}</p>
                    </div>
                   @else
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">venció hace</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $membership->expiration_time }}</p>
                    </div>
                   @endif

                   <div>
                      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Pagado</p>
                      <p class="text-lg font-semibold text-gray-800">${{ number_format($membership->total_paid) }}</p>
                   </div>
                </div>
              </div>

              <!-- Right Content: Payment History -->
              <div class="md:col-span-8 bg-white flex flex-col p-8 h-full">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                  <h3 class="text-xl font-bold text-gray-800">Historial de Pagos</h3>
                  <div class="bg-gray-100 px-3 py-1 rounded-full">
                     <span class="text-xs font-bold text-gray-600 uppercase">{{ $membership->periods->count() }} Periodos</span>
                  </div>
                </div>

                <div class="flex-1 overflow-y-auto pr-3 max-h-[400px]">
                  @if ($membership->periods->count() > 0)
                    <div class="relative pl-1 space-y-8">
                       <!-- Vertical Line -->
                       <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-gray-100"></div>

                      @foreach ($membership->periods as $period)
                        <div wire:key="{{ $period->id }}" class="relative pl-10">
                          <!-- Timeline Dot -->
                          <div class="absolute left-[13px] top-1.5 h-4 w-4 rounded-full bg-white z-10
                              {{ $period->status->value === 'completed' ? '' : '' }}">
                             @if($period->status->value !== 'completed')
                               <div class="absolute inset-0.5 rounded-full bg-green-600"></div>
                             @else
                               <div class="absolute inset-0.5 rounded-full bg-gray-300"></div>
                             @endif
                          </div>

                          <div class="flex flex-col sm:flex-row sm:items-start justify-between group">
                            <div>
                               <h4 class="text-base font-semibold text-gray-800">
                                 {{ $period->start_date->format('d M, Y') }} — {{ $period->end_date->format('d M, Y') }}
                               </h4>
                               {{-- <div class="mt-1 flex items-center gap-2"> --}}
                                 {{-- <span class="text-sm font-medium text-gray-500">{{ $period->status->label() }}</span> --}}


                                <span
                                  class="mt-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                  {{ $period->status->value === 'completed' ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-700' }}"
                                >
                                  {{ $period->status->label() }}
                                </span>

                               {{-- </div> --}}

                            </div>
                            <div class="mt-2 sm:mt-0 text-right">
                               <span class="text-lg font-bold text-gray-800">${{ number_format($period->price_paid) }}</span>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 py-12">
                      <flux:icon icon="document-text" class="w-16 h-16 mb-4 opacity-20" />
                      <p class="text-lg font-medium text-gray-500">Sin historial disponible</p>
                      <p class="text-sm text-gray-400 mt-1">No hay pagos registrados para esta membresía.</p>
                    </div>
                  @endif
                </div>

                <div class="pt-6 mt-4 border-t border-gray-100 flex justify-end">
                  <flux:button wire:click="closeModal">
                    Cerrar
                  </flux:button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  @endif
</div>
