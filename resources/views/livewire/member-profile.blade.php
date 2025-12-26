@php
  use App\Enums\MembershipStatus;
  use App\Enums\MemberStatus;
@endphp

<div>
  @if ($show && $member)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="close">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all max-w-6xl w-full"
            wire:click.stop>

            <div class="grid grid-cols-1 md:grid-cols-12 bg-white min-h-[580px]">
              <!-- Left Column: Large Image (5/12 cols) -->
              <div class="md:col-span-5 relative bg-gray-50 flex items-center justify-center overflow-hidden border-r border-gray-100">
                @if ($member->photo)
                  <img src="{{ Storage::url($member->photo) }}" class="absolute inset-0 w-full h-full object-cover"
                    alt="{{ $member->name }}" />
                  <div
                    class="absolute inset-x-0 bottom-0 h-1/4 bg-linear-to-t from-black/60 to-transparent pointer-events-none">
                  </div>
                @else
                  <div class="flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                    <div
                      class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 border border-gray-200">
                      <span class="text-4xl font-bold text-gray-300">{{ $member->initials() }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sin foto</span>
                  </div>
                @endif

                <!-- ID Badge on Image -->
                <div
                  class="absolute bottom-4 left-4 bg-white/90 px-3.5 py-1.5 rounded-lg border border-gray-100 z-20">
                  <p class="text-base font-semibold text-gray-900">
                    <span class="uppercase tracking-wide">#ID: </span>
                    000{{ $member->id }}
                  </p>
                </div>
              </div>

              <!-- Right Column: Details (7/12 cols) -->
              <div class="md:col-span-7 p-10 pb-8 flex flex-col h-full bg-white">
                <!-- Header -->
                <div class="flex justify-between mb-8">
                  <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $member->name }}</h2>
                    <div class="flex items-center gap-5 mt-3 text-sm text-gray-500 font-medium">
                      <span class="flex items-center gap-1.5 capitalize">
                        <flux:icon icon="user" variant="mini" class="text-gray-400" />
                        {{ $member->gender->label() }}
                      </span>
                      <span class="flex items-center gap-1.5">
                        <flux:icon icon="cake" variant="mini" class="text-gray-400" />
                        {{ $member->getAge() ? $member->getAge() . ' años' : 'Edad desconocida' }}
                      </span>
                    </div>
                  </div>
                  <div>
                    @php
                      $statusColor = match ($member->status) {
                          MemberStatus::ACTIVE => 'bg-green-100 text-green-700 border-green-200',
                          MemberStatus::EXPIRED => 'bg-red-100 text-red-700 border-red-200',
                          default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                      };
                    @endphp
                    <span
                      class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusColor }}">
                      {{ $member->status->label() }}
                    </span>
                  </div>
                </div>

                <!-- Membership Section (Simplified) -->
                <div class="mb-10">
                  <div class="flex items-center gap-2 mb-3">
                    <flux:icon icon="credit-card" class="w-5 h-5 text-gray-500" />
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Membresía Actual</h3>
                  </div>

                  @if ($membership = $member->latestMembership())
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-6">
                      <div class="flex justify-between items-center gap-8">
                        <div>
                          <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Plan</p>
                          <p class="text-lg font-bold text-gray-800">
                            {{ $membership->planType->name }} - {{ $membership->plan->name }}
                          </p>
                        </div>
                        <div class="text-right">
                          <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Periodo</p>
                          <div class="text-lg font-bold text-gray-800 flex items-center gap-1.5">
                            <span>{{ $membership->lastPeriod->start_date->format('d M, Y') }}</span>
                            <span>-></span>
                            <span>{{ $membership->lastPeriod->end_date->format('d M, Y') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="text-center py-9 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                      <p class="text-base font-medium text-gray-600">
                        No tiene una membresía asignada
                      </p>
                    </div>
                  @endif
                </div>

                <!-- Secondary Metadata -->
                <div class="mt-auto grid grid-cols-2 gap-8 pt-8 border-t border-gray-200">
                  <div>
                    <span class="block text-xs text-gray-500 font-bold uppercase tracking-wide mb-1.5">
                      Miembro desde
                    </span>
                    <span class="font-bold text-gray-700 flex items-center gap-2">
                      <flux:icon icon="calendar-days" variant="mini" class="text-gray-500" />
                      {{ $member->created_at->format('d M, Y') }}
                    </span>
                  </div>
                  <div>
                    <span
                      class="block text-xs text-gray-500 font-bold uppercase tracking-wide mb-1.5">Antigüedad</span>
                    <span class="font-bold text-gray-700 flex items-center gap-2">
                      <flux:icon icon="clock" variant="mini" class="text-gray-500" />
                      {{ $member->antiquity }} {{ $member->antiquity === 1 ? 'mes' : 'meses' }}
                    </span>
                  </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-12 flex justify-end">
                  <flux:button wire:click="close">
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
