@php
  use \App\Enums\MembershipStatus;
@endphp

<x-slot:subtitle>Gestiona las membresías de tus socios</x-slot:subtitle>

<div class="space-y-6">
  {{-- Membership stats --}}
  @if($this->stats['total'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
      {{-- Total --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
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
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
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
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
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
          placeholder="Buscar por nombre o código..."
          wire:model.live="search"
          class="block w-full pl-10 pr-3 py-2 text-[16px] border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-600"
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
  @if($this->memberships->isEmpty())
    <div class="text-center py-20">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="credit-card" class="mx-auto h-12 w-12" />
      </div>
      @if ($this->statusFilter || $this->search)
        <h3 class="mt-2 font-medium text-gray-900">No hay resultados</h3>
        <p class="mt-1.5 text-sm text-gray-600">No hay membresías que coincidan con tu búsqueda.</p>
      @else
        <h3 class="mt-2 font-medium text-gray-900">No hay membresías registradas</h3>
        <p class="mt-1.5 text-sm text-gray-600">Comienza creando una nueva membresía.</p>
        <div class="mt-6">
          <flux:button variant="primary" icon="plus" wire:click="createMembershipModal">
            Nueva Membresía
          </flux:button>
        </div>
      @endif
    </div>
  @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      @foreach($this->memberships as $membership)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative flex flex-col"
          wire:key="{{ $membership->id }}">
          <!-- Status Badge -->
          <div class="absolute top-6 right-6">
            @php
              $status = $membership->status;
            @endphp
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
              {{ $status == MembershipStatus::ACTIVE ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }}"
            >
              {{ $status->label() }}
            </span>
          </div>

          <div class="p-6 flex-1">
            <!-- Card Header -->
            <div class="flex items-center mb-6">
              <div class="flex items-center space-x-4">
                <button
                  wire:click="$dispatch('show-profile', { member: {{ $membership->member->id }} })"
                  class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center shrink-0 transition cursor-pointer"
                >
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
                </button>

                <div>
                  <h3 class="text-lg font-medium text-gray-900">{{ $membership->member->name }}</h3>
                  <div class="flex items-center gap-0.5">
                    <flux:icon icon="hashtag" variant="mini" class="text-gray-500" />
                    <span class="text-gray-800">{{ $membership->member->code }}</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Membership Info --}}
            <div class="space-y-4">
              <div>
                <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide mb-1">Tipo</p>
                <div class="flex items-center gap-1.5">
                  <flux:icon icon="credit-card" class="w-5 h-5 text-gray-500" />
                  <span class="font-medium text-gray-800">
                    {{ $membership->membershipType->name }}
                  </span>
                </div>
              </div>

              <div>
                <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide mb-1">
                    {{ $membership->status == MembershipStatus::ACTIVE ? 'Vence en ' : 'Venció hace ' }}
                </p>
                <div class="flex items-center gap-1.5">
                  <flux:icon icon="clock" variant="mini" class="text-gray-500" />
                  <span class="font-medium text-gray-800">
                    {{ $membership->expiration_time }}
                  </span>
                </div>
              </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 mt-6">
              <flux:button
                size="sm"
                variant="outline"
                wire:click="$dispatch('open-history-modal', { membership: {{ $membership->id }} })"
              >
                Historial
              </flux:button>

              {{-- @if ($membership->status == MembershipStatus::EXPIRED) --}}
              <flux:button
                size="sm"
                variant="{{ $membership->status == MembershipStatus::EXPIRED ? 'primary' : 'outline' }}"
                wire:click="$dispatch('open-renewal-modal', { membership: {{ $membership->id }} })"
              >
                Renovar
              </flux:button>
              {{-- @endif --}}
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
      {{ $this->memberships->links('pagination.custom') }}
    </div>
  @endif

  <!-- Create Membership Modal -->
  @if($showCreateModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeCreateModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-[650px]" wire:click.stop>

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Nueva Membresía</h3>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="saveMembership">
              <div class="px-6 py-4 space-y-6">
                {{-- Member Search --}}
                <flux:field>
                  <flux:label>Socio</flux:label>

                  @if($selectedMember)
                    {{-- Selected member card --}}
                    <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                      <div class="flex items-center gap-3">
                        <div class="h-14 w-14 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                          @if($selectedMember->photo)
                            <img
                              src="{{ Storage::url($selectedMember->photo) }}"
                              class="h-full w-full object-cover"
                              alt="{{ $selectedMember->name }}"
                            />
                          @else
                            <span class="text-sm font-semibold text-gray-600">
                              {{ $selectedMember->initials() }}
                            </span>
                          @endif
                        </div>
                        <div>
                          <p class="font-medium text-gray-800">{{ $selectedMember->name }}</p>
                          <div class="flex items-center gap-0.5">
                            <flux:icon icon="hashtag" variant="micro" class="text-gray-500" />
                            <span class="text-sm text-gray-700">{{ $selectedMember->code }}</span>
                          </div>
                        </div>
                      </div>
                      <button type="button" wire:click="clearSelectedMember" class="p-1 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                        <flux:icon icon="x-mark" variant="mini" />
                      </button>
                    </div>
                  @else
                    {{-- Search input with dropdown --}}
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                      <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                          </svg>
                        </div>
                        <input
                          type="text"
                          wire:model.live.debounce.300ms="memberSearch"
                          @focus="open = true"
                          @input="open = true"
                          placeholder="Buscar por nombre o código..."
                          autocomplete="off"
                          class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500"
                        />
                      </div>

                      {{-- Results dropdown --}}
                      <div
                        x-show="open && $wire.memberSearch.length > 0"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                      >
                        @forelse($this->memberResults as $result)
                          <button
                            type="button"
                            wire:key="member-result-{{ $result->id }}"
                            wire:click="selectMember({{ $result->id }})"
                            @click="open = false"
                            class="w-full text-left px-3 py-2.5 hover:bg-gray-50 flex items-center gap-3 transition-colors cursor-pointer border-b border-gray-100 last:border-b-0"
                          >
                            <div class="h-14 w-14 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                              @if($result->photo)
                                <img src="{{ Storage::url($result->photo) }}" class="h-full w-full object-cover" alt="{{ $result->name }}" />
                              @else
                                <span class="text-sm font-semibold text-gray-600">
                                  {{ $result->initials() }}
                                </span>
                              @endif
                            </div>
                            <div>
                              <p class="font-medium text-gray-800">{{ $result->name }}</p>
                              <div class="flex items-center gap-0.5">
                                <flux:icon icon="hashtag" variant="micro" class="text-gray-500" />
                                <span class="text-sm text-gray-700">{{ $result->code }}</span>
                              </div>
                            </div>
                          </button>
                        @empty
                          <div class="px-3 py-4 text-sm text-gray-600 text-center">
                            No se encontraron socios
                          </div>
                        @endforelse
                      </div>
                    </div>
                  @endif

                  <flux:error name="member_id" />
                </flux:field>

                {{-- Membership type and duration --}}
                <div class="grid grid-cols-2 gap-4">
                  {{-- Membership type --}}
                  <flux:field>
                    <flux:label>Tipo de Membresía</flux:label>
                    <flux:select wire:model.live="membership_type_id" placeholder="Elige un tipo de membresía">
                      @foreach($membershipTypes as $membershipType)
                        <flux:select.option value="{{ $membershipType->id }}">{{ $membershipType->name }}</flux:select.option>
                      @endforeach
                    </flux:select>
                    <flux:error name="membership_type_id" />
                  </flux:field>

                  {{-- Duration --}}
                  <flux:field>
                    <flux:label>Primer Periodo</flux:label>
                    <flux:select wire:model.live="duration_id" placeholder="Elige la duración del primer periodo">
                      @foreach($durations as $duration)
                        <flux:select.option value="{{ $duration->id }}">{{ $duration->name }} - ${{ $duration->price }}</flux:select.option>
                      @endforeach
                    </flux:select>
                    <flux:error name="duration_id" />
                  </flux:field>
                </div>

                <flux:field>
                  <flux:label>Fecha de Inicio</flux:label>
                  <flux:input type="date" wire:model="start_date" />
                  <flux:error name="start_date" />
                </flux:field>
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
                  Guardar
                </flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif

  <livewire:memberships.renew-membership />

  <livewire:memberships.membership-history />

  <livewire:members.profile />

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div
      class="fixed font-medium top-5 right-5 bg-green-50 text-green-800 border border-green-300 px-6 py-2.5 rounded-lg shadow-lg z-100"
      wire:key="{{ Str::random() }}"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div
      class="fixed font-medium top-5 right-5 bg-red-50 text-red-800 border border-red-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      wire:key="{{ Str::random() }}"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('error') }}
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
