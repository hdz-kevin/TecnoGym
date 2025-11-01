@php
  use Illuminate\Support\Facades\Storage;
@endphp

<div>
  @if ($show && $member)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="close">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all max-w-3xl w-full"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">Perfil del socio</h3>
              <button wire:click="close" class="text-gray-500 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6">
              <!-- Member Photo and Basic Info -->
              <div class="flex items-center space-x-6 mb-6">
                <div
                  class="h-65 w-75 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden border border-gray-200">
                  @if ($member->photo)
                    <img src="{{ Storage::url('member-photos/' . $member->photo) }}" alt="{{ $member->name }}"
                      class="h-full w-full object-cover">
                  @else
                    <span class="text-3xl font-semibold text-gray-600">{{ $this->memberInitials }}</span>
                  @endif
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $member->name }}</h2>
                  <p class=" text-gray-600">ID: {{ $member->id }}</p>
                  {{-- <p class="text-sm text-gray-600">Género:
                    @if ($member->gender === 'male' || $member->gender === 'M')
                      Masculino
                    @elseif($member->gender === 'female' || $member->gender === 'F')
                      Femenino
                    @else
                      -
                    @endif
                  </p> --}}
                </div>
              </div>

              <!-- Member Details Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                  <h4 class="font-semibold text-gray-700 mb-3">Información Personal</h4>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Fecha de nacimiento:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '-' }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Edad:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ $this->memberAge ? $this->memberAge . ' años' : '-' }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Sexo:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ $member->gender === 'M' ? 'Masculino' : 'Femenino' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Membership Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                  <h4 class="font-semibold text-gray-700 mb-3">Estado de Membresía</h4>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Membresías totales:</span>
                      <span class="text-sm font-medium text-gray-900">{{ $member->memberships->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Tipo actual:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ $this->activeMembership?->membershipType?->name ?? 'Sin membresía' }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Estado:</span>
                      @if ($this->activeMembership)
                        @php
                          $isActive =
                              $this->activeMembership->end_date && $this->activeMembership->end_date->isFuture();
                        @endphp
                        <span class="text-sm font-medium {{ $isActive ? 'text-green-600' : 'text-red-600' }}">
                          {{ $isActive ? 'Activa' : 'Vencida' }}
                        </span>
                      @else
                        <span class="text-sm font-medium text-gray-600">Sin membresía</span>
                      @endif
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-700">Vencimiento:</span>
                      <span class="text-sm font-medium text-gray-900">
                        {{ $this->activeMembership?->end_date?->format('d/m/Y') ?? '-' }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Activity Section -->
              {{-- <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Actividad</h4>
                <div class="grid grid-cols-2 gap-4">
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">0</p>
                    <p class="text-sm text-gray-600">Visitas totales</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">-</p>
                    <p class="text-sm text-gray-600">Última visita</p>
                  </div>
                </div>
              </div> --}}
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
              <div class="flex justify-end gap-4">
                <flux:button variant="outline">
                  Editar perfil
                </flux:button>
                <flux:button wire:click="close" variant="primary">
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
