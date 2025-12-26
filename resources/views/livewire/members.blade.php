@php
  use App\Enums\MemberStatus;
@endphp

<x-slot:subtitle>Gestiona tus socios y su estado</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  {{-- Membership stats --}}
  @if($this->stats['total'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      {{-- Total --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
          {{ $this->statusFilter === null ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter(null)"
      >
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <flux:icon icon="users" class="w-6 h-6 text-blue-600" />
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
          {{ $this->statusFilter === MemberStatus::ACTIVE ? 'border-green-300 ring-1 ring-green-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MemberStatus::ACTIVE->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <flux:icon icon="check" class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MemberStatus::ACTIVE->label() }}s</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['active'] }}</p>
          </div>
        </div>
      </div>

      {{-- Expired --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
          {{ $this->statusFilter === MemberStatus::EXPIRED ? 'border-red-300 ring-1 ring-red-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MemberStatus::EXPIRED->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <flux:icon icon="clock" class="w-6 h-6 text-red-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MemberStatus::EXPIRED->label() }}s</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['expired'] }}</p>
          </div>
        </div>
      </div>

      {{-- No Membership --}}
      <div
        class="bg-white rounded-lg p-6 shadow-sm border transition-all hover:shadow-md
          {{ $this->statusFilter === MemberStatus::NO_MEMBERSHIP ? 'border-yellow-300 ring-1 ring-yellow-200' : 'border-gray-200' }}"
        wire:click="setStatusFilter({{ MemberStatus::NO_MEMBERSHIP->value }})"
      >
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <flux:icon icon="exclamation-triangle" class="w-6 h-6 text-yellow-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-500">{{ MemberStatus::NO_MEMBERSHIP->label() }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $this->stats['no_membership'] }}</p>
          </div>
        </div>
      </div>
    </div>
  @endif
  <!-- Search and Filters -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input type="text" placeholder="Buscar por nombre..."
          wire:model.live="search"
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-600" />
      </div>
    </div>
    <div>
      <flux:button variant="primary" icon="plus" wire:click="createMemberModal">
        Nuevo Socio
      </flux:button>
    </div>
  </div>

  <!-- Members Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($this->members as $member)
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
        <!-- Status Badge -->
        <div class="absolute top-4 right-4">
          <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
            {{ $member->status == MemberStatus::ACTIVE ? 'bg-green-100 text-green-800' :
              ($member->status == MemberStatus::EXPIRED ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
            {{ $member->status->label() }}
          </span>
        </div>

        <div class="p-6">
          <div class="space-y-4">
            <!-- Member Info -->
            <div class="flex items-center space-x-4">
              <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border border-gray-200
                cursor-pointer transition-all" wire:click="$dispatch('show-member-profile', { memberId: {{ $member->id }} })">
                @if($member->photo)
                  <img
                    src="{{ Storage::url($member->photo) }}"
                    class="h-full w-full object-cover"
                    alt="{{ $member->name }}"
                  />
                @else
                  <span class="text-xl font-semibold text-gray-700">{{ $member->initials() }}</span>
                @endif
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $member->name }}</h3>
                <p class="text-sm text-gray-900">#ID: {{ $member->id }}</p>
              </div>
            </div>

            <!-- Member Details -->
            <div class="space-y-5 py-2.5">
              @php
                  $latestMembership = $member->latestMembership();
              @endphp

              <div class="grid grid-cols-2 gap-4">
                  {{-- Plan Info --}}
                  <div>
                      <div class="flex items-center gap-1.5 mb-1">
                        <flux:icon icon="credit-card" variant="mini" class="text-gray-400" />
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Plan actual</span>
                      </div>
                      <p class="font-medium text-gray-900">
                          {{ $latestMembership?->planName ?? 'Sin plan' }}
                      </p>
                  </div>

                  {{-- Expiration Info --}}
                  <div>
                      <div class="flex items-center gap-1.5 mb-1">
                        <flux:icon icon="calendar" variant="mini" class="text-gray-400" />
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Vencimiento</span>
                      </div>
                      <p class="font-medium text-gray-900">
                          {{ $latestMembership?->lastPeriod->end_date->format('d M Y') ?? '--/--/----' }}
                      </p>
                  </div>
              </div>

              {{-- Member Since --}}
              <div class="flex items-center gap-1.5 text-sm font-medium text-gray-600">
                 <flux:icon icon="calendar-days" variant="mini" class="text-gray-500" />
                 <span>Miembro desde {{ $member->created_at->format('d M Y') }}</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
              <div class="flex gap-2">
                <flux:button size="sm" variant="outline" wire:click="editMemberModal({{ $member->id }})">
                  Editar
                </flux:button>
              </div>
              <div class="flex gap-2">
                <flux:button size="sm" variant="primary">
                  Asignar membresía
                </flux:button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Pagination -->
  <div class="mt-8">
    {{ $this->members->links('pagination.members') }}
  </div>

  <!-- Create/Edit Form Modal -->
  @if ($showFormModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl"
            wire:click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingMember ? 'Editar Socio' : 'Nuevo Socio' }}
              </h3>
              <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form wire:submit.prevent="saveMember">
              <!-- Modal Body -->
              <div class="px-6 py-4 space-y-4">
                <!-- Name -->
                <flux:field>
                  <flux:label for="name">Nombre completo *</flux:label>
                  <flux:input wire:model="name" id="name" placeholder="Ej: Alfonso Gómez" />
                  <flux:error name="name" />
                </flux:field>

                <!-- Gender -->
                <flux:field>
                  <flux:label for="gender">Género *</flux:label>
                  <flux:select wire:model="gender" id="gender" placeholder="Seleccionar género">
                    <flux:select.option value="male">Masculino</flux:select.option>
                    <flux:select.option value="female">Femenino</flux:select.option>
                  </flux:select>
                  <flux:error name="gender" />
                </flux:field>

                <!-- Date of Birth -->
                <flux:field>
                  <flux:label>Fecha de nacimiento</flux:label>
                  <div class="grid grid-cols-3 gap-2">
                    <!-- Day -->
                    <flux:select wire:model="birth_day" placeholder="Día">
                      @for($i = 1; $i <= 31; $i++)
                        <flux:select.option value="{{ $i }}">{{ $i }}</flux:select.option>
                      @endfor
                    </flux:select>

                    <!-- Month -->
                    <flux:select wire:model="birth_month" placeholder="Mes">
                      <flux:select.option value="01">Enero</flux:select.option>
                      <flux:select.option value="02">Febrero</flux:select.option>
                      <flux:select.option value="03">Marzo</flux:select.option>
                      <flux:select.option value="04">Abril</flux:select.option>
                      <flux:select.option value="05">Mayo</flux:select.option>
                      <flux:select.option value="06">Junio</flux:select.option>
                      <flux:select.option value="07">Julio</flux:select.option>
                      <flux:select.option value="08">Agosto</flux:select.option>
                      <flux:select.option value="09">Septiembre</flux:select.option>
                      <flux:select.option value="10">Octubre</flux:select.option>
                      <flux:select.option value="11">Noviembre</flux:select.option>
                      <flux:select.option value="12">Diciembre</flux:select.option>
                    </flux:select>

                    <!-- Year -->
                    <flux:select wire:model="birth_year" placeholder="Año">
                      @for($year = date('Y'); $year >= 1930; $year--)
                        <flux:select.option value="{{ $year }}">{{ $year }}</flux:select.option>
                      @endfor
                    </flux:select>
                  </div>
                  <flux:error name="birth_date" />
                </flux:field>

                <!-- Photo -->
                <flux:field>
                  <flux:label for="photo">Foto</flux:label>

                  <!-- Preview and Upload Container -->
                  <div class="mt-1">
                    @if($existing_photo || $photo)
                      <!-- Photo Preview -->
                      <div class="flex items-center space-x-4">
                        <div class="shrink-0">
                          <div class="h-36 w-40 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            @if($photo)
                              <img src="{{ $photo->temporaryUrl() }}" alt="Vista previa" class="h-full w-full object-cover">
                              @elseif ($existing_photo)
                              <img src="{{ Storage::url($existing_photo) }}" alt="Vista previa" class="h-full w-full object-cover">
                            @endif
                          </div>
                        </div>

                        <div class="flex-1">
                          <div class="flex items-center space-x-3">
                            <!-- Change Photo Button -->
                            <label for="photo" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                              </svg>
                              Cambiar foto
                            </label>

                            <!-- Remove Photo Button -->
                            <button type="button" wire:click="removePhoto" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none">
                              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                              </svg>
                              Quitar
                            </button>
                          </div>

                          <p class="text-sm text-gray-500 mt-1">JPG, JPEG, PNG hasta 2MB</p>
                        </div>
                      </div>
                    @else
                      <!-- Upload Button -->
                      <div class="flex gap-3 items-center">
                        <label for="photo" class="cursor-pointer inline-flex items-center px-6 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                          </svg>
                          Subir foto
                        </label>
                        <p class="text-sm text-gray-500">JPG, JPEG, PNG hasta 2MB</p>
                      </div>
                    @endif

                    <!-- Hidden File Input -->
                    <input
                      type="file"
                      id="photo"
                      wire:model="photo"
                      accept="image/jpeg,image/jpg,image/png,image/webp"
                      class="hidden"
                    />
                  </div>

                  <!-- Loading State -->
                  <div wire:loading wire:target="photo" class="mt-2">
                    <div class="flex items-center text-sm text-blue-600">
                      <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Subiendo imagen...
                    </div>
                  </div>

                  <flux:error name="photo" />
                </flux:field>

              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingMember ? "Guardar cambios" : "Guardar" }}</flux:button>
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

  @if (session()->has('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" x-data="{ show: true }"
      x-show="show" x-init="setTimeout(() => show = false, 5000)">
      {{ session('error') }}
    </div>
  @endif

  <livewire:member-profile />

</div>
