@php
  use Illuminate\Support\Facades\Storage;
  use \App\Enums\MembershipStatus;
@endphp

<x-slot:subtitle>Gestiona las membresías de tus socios</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  {{-- Membership stats --}}
  @if($this->stats['total'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      {{-- Total --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border cursor-pointer transition-all hover:shadow-md
          {{ $this->statusFilter === null ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter(null)"
      >
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <flux:icon icon="credit-card" class="w-6 h-6 text-blue-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['total'] }}</p>
          </div>
        </div>
      </div>

      {{-- Active --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border cursor-pointer transition-all hover:shadow-md
          {{ $this->statusFilter === MembershipStatus::ACTIVE ? 'border-green-300 ring-1 ring-green-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MembershipStatus::ACTIVE->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <flux:icon icon="check" class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MembershipStatus::ACTIVE->label() }}s</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['active'] }}</p>
          </div>
        </div>
      </div>

      {{-- Expired --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border cursor-pointer transition-all hover:shadow-md
          {{ $this->statusFilter === MembershipStatus::EXPIRED ? 'border-red-300 ring-1 ring-red-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MembershipStatus::EXPIRED->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <flux:icon icon="clock" class="w-6 h-6 text-red-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MembershipStatus::EXPIRED->label() }}s</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['expired'] }}</p>
          </div>
        </div>
      </div>

      {{-- Not Started --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border cursor-pointer transition-all hover:shadow-md
          {{ $this->statusFilter === MembershipStatus::NOT_STARTED ? 'border-yellow-300 ring-1 ring-yellow-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MembershipStatus::NOT_STARTED->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <flux:icon icon="exclamation-triangle" class="w-6 h-6 text-yellow-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MembershipStatus::NOT_STARTED->label() }}s</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['not_started'] }}</p>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Search and Actions -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input
          type="text"
          placeholder="Buscar por socio..."
          wire:model.live="search"
          class="block w-full pl-10 pr-3 py-2 text-[16px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500"
        />
      </div>
    </div>

    <div>
      <flux:button variant="primary" icon="plus" wire:click="createMembershipModal">
        Nueva Membresía
      </flux:button>
    </div>
  </div>

  <!-- Memberships List -->
  <div class="space-y-4">
    @forelse($this->memberships as $membership)
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow" wire:key="{{ $membership->id }}">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <!-- Left: Member and Plan Info -->
            <div class="flex items-center space-x-4">
              <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center">
                @if($membership->member->photo)
                  <img
                    src="{{ Storage::url($membership->member->photo) }}"
                    class="h-full w-full rounded-full object-cover"
                    alt="{{ $membership->member->name }}"
                  />
                @else
                  <span class="text-lg font-semibold text-gray-600">
                    {{ $membership->member->initials() }}
                  </span>
                @endif
              </div>

              <div>
                <div class="flex items-center gap-2">
                  <h3 class="text-lg font-bold text-gray-800">{{ $membership->member->name }}</h3>
                  <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $membership->planType->name }}
                  </span>
                </div>
                <div class="mt-1 flex items-center gap-2.5 text-gray-800">
                  {{ $membership->plan->name }}
                  <span class="font-medium text-gray-900">${{ $membership->plan->price }}</span>
                </div>
              </div>

            </div>

            <!-- Right: Status Badge -->
            <div class="text-right">
              @php
                $status = $membership->status;
              @endphp
              <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                {{
                  $status == MembershipStatus::ACTIVE ? "bg-green-100 text-green-800"
                  : ($status == MembershipStatus::EXPIRED ? "bg-red-100 text-red-800"
                  : "bg-yellow-100 text-yellow-800")
                }}"
              >
                {{ $status->label() }}
              </span>
            </div>
          </div>

          <!-- Buttons: Period Summary and Actions -->
          <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                @if($membership->last_period)
                  <span class="flex items-center gap-1.5">
                    <flux:icon icon="calendar-days" variant="mini" class="text-gray-500" />
                    {{ $membership->status == MembershipStatus::ACTIVE ? 'Periodo actual:' : 'Último periodo:' }}
                    {{ $membership->last_period->start_date->format('d/m/Y') }} - {{ $membership->last_period->end_date->format('d/m/Y') }}
                  </span>
                  <span class="flex items-center gap-1.5 mt-5">
                    <flux:icon icon="banknotes" variant="mini" class="text-gray-500" />
                    {{ $membership->periods->count() }} periodos pagados
                  </span>
                @else
                  <span class="flex items-center gap-1.5">
                    <flux:icon icon="exclamation-triangle" variant="mini" class="text-gray-500" />
                    Sin periodos registrados
                  </span>
                @endif
              </div>

              <div class="flex items-center gap-2">
                <flux:button
                  size="sm"
                  variant="outline"
                  icon="chart-bar"
                  wire:click="$dispatch('open-history-modal', { membership: {{ $membership->id }} })"
                >
                  Ver Historial
                </flux:button>

                @if ($membership->status == MembershipStatus::EXPIRED || $membership->status == MembershipStatus::NOT_STARTED)
                  <flux:button
                    size="sm"
                    variant="primary"
                    icon="plus"
                    wire:click="$dispatch('open-add-period-modal', { membership: {{ $membership->id }} })"
                  >
                    Nuevo Periodo
                  </flux:button>
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
          <flux:icon icon="credit-card" class="mx-auto h-12 w-12" />
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay membresías</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva membresía para tus socios.</p>
        <div class="mt-6">
          <flux:button variant="primary" icon="plus" wire:click="createMembership">
            Nueva Membresía
          </flux:button>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Create Membership Modal -->
  @if($showCreateModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeCreateModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-xl" wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Nueva Membresía</h3>
              <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="saveMembership">
              <div class="px-6 py-4 space-y-6">
                {{-- Member  --}}
                <flux:field>
                  <flux:label>Socio</flux:label>
                  <flux:select wire:model.live="member_id" placeholder="Selecciona un socio">
                    @foreach($members as $member)
                      <flux:select.option value="{{ $member->id }}">{{ $member->name }}</flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="member_id" />
                </flux:field>

                {{-- Plan type and period --}}
                <div class="space-y-4">
                  <flux:label class="mb-4!">Plan</flux:label>

                  <div class="grid grid-cols-2 gap-4">
                    {{-- Plan type --}}
                    <flux:field>
                      <flux:label class="text-sm text-gray-700">Tipo</flux:label>
                      <flux:select wire:model.live="plan_type_id" placeholder="Selecciona un tipo">
                        @foreach($planTypes as $planType)
                          <flux:select.option value="{{ $planType->id }}">{{ $planType->name }}</flux:select.option>
                        @endforeach
                      </flux:select>
                      <flux:error name="plan_type_id" />
                    </flux:field>

                    {{-- Period --}}
                    <flux:field>
                      <flux:label class="text-sm text-gray-700">Periodo</flux:label>
                      <flux:select wire:model.live="plan_id" placeholder="Selecciona un periodo">
                        @foreach($this->availablePlans as $plan)
                          <flux:select.option value="{{ $plan->id }}">{{ $plan->name }}</flux:select.option>
                        @endforeach
                      </flux:select>
                      <flux:error name="plan_id" />
                    </flux:field>
                  </div>
                </div>

              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeCreateModal">
                  Cancelar
                </flux:button>
                <flux:button
                  type="submit"
                  variant="primary"
                >
                  Crear Membresía
                </flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      {{ session('message') }}
    </div>
  @endif

  <script>
    document.addEventListener('livewire:init', () => {
      // Disable scroll when history modal opens
      Livewire.on('disable-scroll', () => {
        document.body.classList.add('overflow-hidden');
      });

      // Enable scroll when history modal closes
      Livewire.on('enable-scroll', () => {
        document.body.classList.remove('overflow-hidden');
      });
    });
  </script>

  <livewire:add-period />

  <livewire:membership-history />
</div>
