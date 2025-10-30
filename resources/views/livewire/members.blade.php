<x-slot:subtitle>Gestiona tus socios y su estado</x-slot:subtitle>

<div class="p-6 pt-4 space-y-6">
  <!-- Search and Filters -->
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex-1 max-w-3xl">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre..."
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-500" />
      </div>
    </div>
    <div class="flex gap-3">
      <flux:select placeholder="Todos" class="min-w-40">
        <flux:select.option value="">Todos</flux:select.option>
        <flux:select.option value="active">Con membresía</flux:select.option>
        <flux:select.option value="inactive">Sin membresía</flux:select.option>
      </flux:select>
      <flux:select placeholder="Ordenar por nombre" class="min-w-40">
        <flux:select.option value="name">Ordenar por nombre</flux:select.option>
        <flux:select.option value="date">Ordenar por fecha</flux:select.option>
        <flux:select.option value="status">Ordenar por estado</flux:select.option>
      </flux:select>
      <flux:button variant="primary" icon="plus" wire:click="createMemberModal">
        Nuevo Socio
      </flux:button>
    </div>
  </div>

  <!-- Members Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($members as $member)
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
        <!-- Status Badge -->
        <div class="absolute top-4 right-4">
          @if ($member->memberships->count() > 0)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
              {{ $member->memberships->last()->membershipType->name }}
            </span>
          @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
              Sin membresía
            </span>
          @endif
        </div>

        <div class="p-6">
          <div class="space-y-4">
            <!-- Member Info -->
            <div class="flex items-center space-x-4">
              <div class="h-18 w-18 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border border-gray-200">
                  <img
                    class="h-full w-full object-cover"
                    src="{{ asset('storage/member-photos/' . ($member->photo ?? 'default.png')) }}"
                    alt="{{ $member->name }}"
                  >
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $member->name }}</h3>
                <p class="text-sm text-gray-600">ID: {{ $member->id }}</p>
              </div>
            </div>

            <!-- Member Details -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Estado</span>
                <span class="text-sm font-medium text-gray-900">
                  @if ($member->memberships->count() > 0)
                    Con membresía activa
                  @else
                    Sin membresía
                  @endif
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Visitas</span>
                <span class="text-sm font-medium text-gray-900">Sin registros</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
              <div class="flex gap-2">
                <flux:button size="sm" variant="outline">
                  Ver
                </flux:button>
                <flux:button size="sm" variant="outline" wire:click="updateMemberModal({{ $member->id }})">
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
  <div class="flex justify-center mt-8">
    <div class="flex items-center space-x-2">
      <flux:button size="sm" variant="outline">Anterior</flux:button>
      <span class="text-sm text-gray-500 px-4">Página 1 de 3</span>
      <flux:button size="sm" variant="outline">Siguiente</flux:button>
    </div>
  </div>
  <!-- Create/Edit Form Modal -->
  @if ($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            wire:click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $updatingMember ? 'Editar Socio' : 'Nuevo Socio' }}
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
                    <flux:select.option value="M">Masculino</flux:select.option>
                    <flux:select.option value="F">Femenino</flux:select.option>
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
              </div>

              <!-- Modal Footer -->
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $updatingMember ? "Actualizar" : "Crear" }}</flux:button>
              </div>
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
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

</div>
