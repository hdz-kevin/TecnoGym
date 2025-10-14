<x-slot:subtitle>Gestiona tus socios y su estado</x-slot:subtitle>

<div>
  <!-- Content -->
  <div class="p-6 pt-4 space-y-6">
    <!-- Search and Filters -->
    <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
      <div class="flex-1 max-w-md">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre..." icon="magnifying-glass" />
      </div>
      <div class="flex gap-3">
        <flux:select placeholder="Todos" class="w-32">
          <flux:select.option value="">Todos</flux:select.option>
          <flux:select.option value="active">Con membresía</flux:select.option>
          <flux:select.option value="inactive">Sin membresía</flux:select.option>
        </flux:select>
        <flux:select placeholder="Ordenar por nombre" class="w-40">
          <flux:select.option value="name">Ordenar por nombre</flux:select.option>
          <flux:select.option value="date">Ordenar por fecha</flux:select.option>
          <flux:select.option value="status">Ordenar por estado</flux:select.option>
        </flux:select>
        <flux:button class="cursor-pointer" variant="primary" icon="plus" wire:click="createMemberModal">
          Nuevo socio
        </flux:button>
      </div>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach ($members as $member)
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div
                class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                AR
              </div>
              <div>
                <h3 class="font-semibold text-gray-900 text-[18px]">{{ $member->name }}</h3>
                <p class="text-sm text-gray-500">ID: {{ $member->id }}</p>
              </div>
            </div>
            {{-- <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                            Sin membresía
                        </span> --}}
            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
              Premium
            </span>
            {{-- <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                            VIP
                        </span> --}}
          </div>

          <div class="mb-4">
            <p class="text-sm text-gray-500">Sin visitas registradas</p>
          </div>
          {{-- <div class="mb-4">
                        <p class="text-sm text-gray-500">Última visita: Hace 2 días</p>
                        <p class="text-sm text-gray-500">Total visitas: 47</p>
                    </div> --}}

          <div class="flex flex-wrap gap-2">
            <flux:button variant="ghost" size="sm" class="text-sm border">Ver</flux:button>
            <flux:button variant="ghost" size="sm" class="text-sm border" wire:click="updateMemberModal({{ $member->id }})">Editar</flux:button>
            <flux:button variant="ghost" size="sm" class="text-sm border">Registrar visita</flux:button>
            <flux:button variant="ghost" size="sm" class="text-sm border">Asignar membresía</flux:button>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-center mt-8">
      <div class="flex items-center space-x-2">
        <flux:button variant="ghost" size="sm" icon="chevron-left" disabled>Anterior</flux:button>
        <flux:button variant="primary" size="sm">1</flux:button>
        <flux:button variant="ghost" size="sm">2</flux:button>
        <flux:button variant="ghost" size="sm">3</flux:button>
        <flux:button variant="ghost" size="sm">...</flux:button>
        <flux:button variant="ghost" size="sm">15</flux:button>
        <flux:button variant="ghost" size="sm" icon="chevron-right">Siguiente</flux:button>
      </div>
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
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold leading-6 text-gray-900">
                  Nuevo Socio
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <!-- Form -->
              <form wire:submit.prevent="saveMember" class="space-y-4">
                <!-- Name -->
                <div>
                  <flux:field>
                    <flux:label for="name">Nombre completo *</flux:label>
                    <flux:input wire:model="name" id="name" placeholder="Ej: Alfonso Gómez" />
                    <flux:error name="name" />
                  </flux:field>
                </div>

                <!-- Gender -->
                <div>
                  <flux:field>
                    <flux:label for="gender">Género *</flux:label>
                    <flux:select wire:model="gender" id="gender" placeholder="Seleccionar género">
                      <flux:select.option value="M">Masculino</flux:select.option>
                      <flux:select.option value="F">Femenino</flux:select.option>
                    </flux:select>
                    <flux:error name="gender" />
                  </flux:field>
                </div>

                <!-- Replace the date input with three separate selects -->
                <div>
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
              </form>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <flux:button wire:click="saveMember" variant="primary" class="w-full sm:ml-3 sm:w-auto" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $updatingMember ? "Guardar Cambios" : "Crear Socio" }}</span>
                <span wire:loading>{{ $updatingMember ? "Guardando..." : "Creando..." }}</span>
              </flux:button>
              <flux:button wire:click="closeModal" variant="ghost" class="mt-3 w-full sm:mt-0 sm:w-auto">
                Cancelar
              </flux:button>
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
</div>
