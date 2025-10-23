@php
  use App\Enums\MembershipStatus;
@endphp

<x-slot:subtitle>Administra las membresías activas y vencidas de tus socios</x-slot:subtitle>

<div class="p-6 pt-3 space-y-6">
  <!-- Statistics -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Membresías</p>
          <p class="text-3xl font-bold text-gray-900">{{ $memberships->count() }}</p>
        </div>
        <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center">
          <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Membresías Activas</p>
          <p class="text-3xl font-bold text-gray-900">{{ $activeCount }}</p>
        </div>
        <div class="h-12 w-12 bg-green-50 rounded-xl flex items-center justify-center">
          <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Membresías Vencidas</p>
          <p class="text-3xl font-bold text-gray-900">{{ $expiredCount }}</p>
        </div>
        <div class="h-12 w-12 bg-red-50 rounded-xl flex items-center justify-center">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters and Search -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      {{-- <flux:input placeholder="Buscar por nombre..." class="w-full !placeholder-gray-700" icon="magnifying-glass" /> --}}
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input type="text" placeholder="Buscar por nombre..."
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500" />
      </div>
    </div>
    <div class="flex gap-3">
      <flux:select placeholder="Estado" class="min-w-40">
        <flux:select.option value="all">Todos los estados</flux:select.option>
        <flux:select.option value="active">Activas</flux:select.option>
        <flux:select.option value="expired">Vencidas</flux:select.option>
      </flux:select>
      <flux:select placeholder="Tipo" class="min-w-40">
        <flux:select.option value="all">Todos los tipos</flux:select.option>
        <flux:select.option value="general">General</flux:select.option>
        <flux:select.option value="estudiante">Estudiante</flux:select.option>
      </flux:select>
      <flux:button variant="primary" icon="plus" wire:click="createMembership">
        Nueva Membresía
      </flux:button>
    </div>
  </div>

  <!-- Memberships grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($memberships as $membership)
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
        <div class="absolute top-4 right-4">
          <span
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
              {{ $membership->status === MembershipStatus::ACTIVE ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
          >
            {{ $membership->status->label() }}
          </span>
        </div>

        <div class="p-6">
          <div class="space-y-4">
            <div class="flex items-center space-x-4">
              <div class="h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                <span class="text-lg font-semibold text-gray-900">
                  {{ $membership->member->initials() }}
                </span>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900">
                  {{ $membership->member->name }}
                </h3>
                <p class="text-gray-700">
                  {{ $membership->membershipType->name }} • {{ $membership->period->name }}
                </p>
              </div>
            </div>

            <!-- Period Information -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Inicio</span>
                <span class="text-sm font-medium text-gray-900">{{ $membership->start_date->format('d M Y') }}</span>
              </div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Vencimiento</span>
                <span class="text-sm font-medium text-gray-900">{{ $membership->end_date->format('d M Y') }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Estado</span>
                @php
                  $days = $membership->daysUntilExpiration();
                @endphp

                @if ($membership->status == MembershipStatus::ACTIVE)
                  <span class="text-sm font-medium text-green-600">
                    {{ $days == 1 ? '1 día restante' : $days.' días restantes' }}
                  </span>
                @else
                  <span class="text-sm font-medium text-red-600">
                    @if ($days == 0)
                      Vencida hoy
                    @else
                      Vencida hace {{ $days }} @choice('día|días', $days)
                    @endif
                  </span>
                @endif
              </div>
            </div>

            <!-- Price and Actions -->
            <div class="flex items-center justify-between">
              <p class="text-2xl font-bold text-gray-900">${{ $membership->price }}</p>
              <div class="flex gap-2">
                <flux:button size="sm" variant="outline">
                  Historial
                </flux:button>
                @if ($membership->status == MembershipStatus::ACTIVE)
                  <flux:button size="sm" variant="primary">
                    Editar
                  </flux:button>
                @else
                  <flux:button size="sm" variant="primary">
                    Renovar
                  </flux:button>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-8">
    <div class="flex items-center space-x-2">
      <flux:button size="sm" variant="outline">Anterior</flux:button>
      <span class="text-sm text-gray-500 px-4">Página 1 de 3</span>
      <flux:button size="sm" variant="outline">Siguiente</flux:button>
    </div>
  </div>

  <!-- Membership Modal -->
  @if ($showMembershipModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeMembershipModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingMembership ? 'Editar Membresía' : 'Nueva Membresía' }}
              </h3>
              <button wire:click="closeMembershipModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form wire:submit.prevent="saveMembership">
              <!-- Modal Body -->
              <div class="px-6 py-4 space-y-4">
                <!-- Member Selection -->
                <flux:field>
                  <flux:label for="memberId">Socio *</flux:label>
                  <flux:select wire:model="memberId" id="memberId" placeholder="Seleccionar socio">
                    @foreach ($members as $member)
                      <flux:select.option value="{{ $member->id }}">{{ $member->name }}</flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="memberId" />
                </flux:field>

                <!-- Membership Type Selection -->
                <flux:field>
                  <flux:label for="membershipTypeId">Tipo de Membresía *</flux:label>
                  <flux:select wire:model.live="membershipTypeId" id="membershipTypeId" placeholder="Seleccionar tipo">
                    @foreach ($membershipTypes as $type)
                      <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="membershipTypeId" />
                </flux:field>

                <!-- Period Selection -->
                <flux:field>
                  <flux:label for="periodId">Período *</flux:label>
                  <flux:select wire:model="periodId" id="periodId" placeholder="Seleccionar período" :disabled="empty($availablePeriods)">
                    @foreach ($availablePeriods as $period)
                      <flux:select.option value="{{ $period->id }}">
                        {{ $period->name }} - ${{ number_format($period->price) }}
                        ({{ $period->duration_value }} {{ $period->duration_unit->label($period->duration_value) }})
                      </flux:select.option>
                    @endforeach
                  </flux:select>
                  <flux:error name="periodId" />
                  @if (empty($availablePeriods) && $membershipTypeId)
                    <p class="text-sm text-amber-600 mt-1">No hay períodos disponibles para este tipo de membresía.</p>
                  @endif
                </flux:field>

                <!-- Start Date -->
                <flux:field>
                  <flux:label for="startDate">Fecha de Inicio *</flux:label>
                  <flux:input wire:model="startDate" id="startDate" type="date" />
                  <flux:error name="startDate" />
                </flux:field>
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeMembershipModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingMembership ? 'Actualizar' : 'Crear' }}</flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  {{-- @if (session()->has('message'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
      x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data="{ show: true }"
      x-show="show" x-init="setTimeout(() => show = false, 5000)">
      {{ session('error') }}
    </div>
  @endif --}}

</div>
