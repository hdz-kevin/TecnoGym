@php
use Illuminate\Support\Facades\Storage;
use \App\Enums\MembershipStatus;
@endphp

<x-slot:subtitle>Gestiona las membresías de tus socios</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  <!-- Stats Summary -->
  @if($stats['total'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div
        class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 cursor-pointer transition-all hover:shadow-md"
        wire:click="filterByStatus(null)"
      >
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <flux:icon icon="credit-card" class="w-6 h-6 text-blue-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
          </div>
        </div>
      </div>

      <div
        class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 cursor-pointer transition-all hover:shadow-md"
        wire:click="filterByStatus({{ MembershipStatus::ACTIVE }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <flux:icon icon="check" class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">Activas</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
          </div>
        </div>
      </div>

      <div
        class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 cursor-pointer transition-all hover:shadow-md"
        wire:click="filterByStatus({{ MembershipStatus::EXPIRED }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <flux:icon icon="clock" class="w-6 h-6 text-red-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">Vencidas</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['expired'] }}</p>
          </div>
        </div>
      </div>

      <div
        class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 cursor-pointer transition-all hover:shadow-md"
        wire:click="filterByStatus({{ MembershipStatus::PENDING }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <flux:icon icon="exclamation-triangle" class="w-6 h-6 text-yellow-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">Pendientes</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
        <input type="text" placeholder="Buscar por socio..."
          class="block w-full pl-10 pr-3 py-2 text-[16px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500" />
      </div>
    </div>
    <div class="">
      {{-- <flux:select placeholder="Todos" class="min-w-40">
        <flux:select.option value="">Todos</flux:select.option>
        <flux:select.option value="active">Activas</flux:select.option>
        <flux:select.option value="expired">Vencidas</flux:select.option>
        <flux:select.option value="pending">Pendientes</flux:select.option>
      </flux:select> --}}
      <flux:button variant="primary" icon="plus" wire:click="createMembership">
        Nueva Membresía
      </flux:button>
    </div>
  </div>

  <!-- Memberships List -->
  <div class="space-y-4">
    @forelse($memberships as $membership)
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow" wire:key="{{ $membership->id }}">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <!-- Left: Member and Plan Info -->
            <div class="flex items-center space-x-4">
              <div class="h-18 w-18 bg-gray-100 rounded-full flex items-center justify-center">
                @if($membership->member->photo)
                  <img src="{{ Storage::url('member-photos/' . $membership->member->photo) }}"
                       alt="{{ $membership->member->name }}"
                       class="h-full w-full rounded-full object-cover">
                @else
                  <span class="text-lg font-semibold text-gray-600">
                    {{ $membership->member->initials() }}
                  </span>
                @endif
              </div>

              <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $membership->member->name }}</h3>
                <p class="text-gray-700">
                  {{ $membership->plan->name }} • {{ $membership->planType->name }} - ${{ number_format($membership->plan->price) }}
                </p>
              </div>
            </div>

            <!-- Right: Status Badge -->
            <div class="text-right">
              @php
                $status = $membership->getStatus();
              @endphp
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
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

          <!-- Bottom: Period Summary and Actions -->
          <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                @if($membership->last_period)
                  <span class="flex items-center gap-1.5">
                    <flux:icon icon="calendar-days" variant="mini" class="text-gray-500" />
                    Último período: {{ $membership->last_period->start_date->format('d/m/Y') }} - {{ $membership->last_period->end_date->format('d/m/Y') }}
                  </span>
                  <span class="flex items-center gap-1.5 mt-5">
                    <flux:icon icon="banknotes" variant="mini" class="text-gray-500" />
                    Total pagado: ${{ number_format($membership->total_paid) }} ({{ $membership->periods_count }} {{ $membership->periods_count == 1 ? 'período' : 'períodos' }})
                  </span>
                @else
                  <span class="flex items-center gap-1.5">
                    <flux:icon icon="exclamation-triangle" variant="mini" class="text-gray-500" />
                    Sin períodos registrados
                  </span>
                @endif
              </div>

              <div class="flex items-center gap-2">
                <flux:button size="sm" variant="outline" icon="chart-bar" wire:click="showHistory({{ $membership->id }})">
                  Ver Historial
                </flux:button>
                <flux:button size="sm" variant="primary" icon="plus">
                  Nuevo Período
                </flux:button>
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
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeCreateModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg" wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Nue va Membresía</h3>
              <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form wire:submit="saveMembership">
              <!-- Modal Body -->
              <div class="px-6 py-4 space-y-4">
                <!-- Member Selection -->
                <flux:field>
                  <flux:label>Socio *</flux:label>
                  <flux:select wire:model="member_id" placeholder="Seleccionar socio">
                    @foreach($members as $member)
                      <flux:select.option value="{{ $member->id }}">{{ $member->name }}</flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="member_id" />
                </flux:field>

                <!-- Plan Selection -->
                @if($availablePlans && count($availablePlans) > 0)
                  <flux:field>
                    <flux:label>Plan *</flux:label>
                    @foreach($availablePlans as $typeName => $plans)
                      <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $typeName }}</h4>
                        <div class="space-y-1">
                          @foreach($plans as $plan)
                            <label class="flex items-center space-x-3 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                              <input type="radio" wire:model="plan_id" value="{{ $plan->id }}" class="text-blue-600">
                              <div class="flex-1">
                                <div class="flex items-center justify-between">
                                  <span class="font-medium">{{ $plan->name }}</span>
                                  <span class="text-lg font-bold text-blue-600">${{ number_format($plan->price) }}</span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $plan->formatted_duration }}</p>
                              </div>
                            </label>
                          @endforeach
                        </div>
                      </div>
                    @endforeach
                    <flux:error name="plan_id" />
                  </flux:field>
                @endif
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeCreateModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">Crear Membresía</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- History Modal -->
  @if($showHistoryModal && $selectedMembership)
    <div class="fixed inset-0 mb-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeHistoryModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-6xl" wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $selectedMembership->member->name }} - Historial de Membresía
              </h3>
              <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6">
              <!-- Membership Info -->
              <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-gray-600">Socio</p>
                    <p class="font-medium">
                      {{ $selectedMembership->member->name }}
                    </p>
                  </div>
                  <div>
                    <p class=" text-gray-600">Plan</p>
                    <p class="font-medium">
                      {{ $selectedMembership->plan->name }} • {{ $selectedMembership->plan->planType->name }}
                    </p>
                  </div>
                  <div>
                    <p class="text-gray-600">Estado</p>
                  @php
                    $status = $selectedMembership->getStatus();
                  @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded text-sm font-medium
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
              </div>

              <!-- Period History -->
              <div>
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-lg font-medium text-gray-900">Historial de Períodos</h4>
                  <flux:button size="sm" variant="primary">
                    + Nuevo Período
                  </flux:button>
                </div>

                @if($selectedMembership->periods->count() > 0)
                  <div class="max-h-96 overflow-y-auto scroll-smooth p-1">
                    <div class="space-y-3">
                      @foreach($selectedMembership->periods as $period)
                      <div class="flex items-center justify-between p-4 border rounded-lg {{ $period->status->value === 'completed' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
                        <div class="flex items-center space-x-4">
                          <div class="flex-shrink-0">
                            @if($period->status->value === 'completed')
                              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                              </div>
                            @else
                              <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                              </div>
                            @endif
                          </div>
                          <div>
                            <p class="font-medium text-gray-900">{{ $period->formatted_period }}</p>
                            <p class="text-sm text-gray-600">{{ $period->status->label() }}</p>
                          </div>
                        </div>

                        <div class="text-right">
                          <p class="font-bold text-lg">${{ number_format($period->price_paid) }}</p>
                        </div>
                      </div>
                    @endforeach
                    </div>
                  </div>

                  <!-- Summary -->
                  <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-lg font-semibold">
                      <span>Total pagado:</span>
                      <span>${{ number_format($selectedMembership->periods->sum('price_paid')) }}</span>
                    </div>
                  </div>
                @else
                  <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                      <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                      </svg>
                    </div>
                    <p class="text-gray-500">No hay periodos registrados para esta membresía</p>
                  </div>
                @endif
              </div>
            </div>
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
</div>
