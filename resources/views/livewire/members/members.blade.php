@php
  use App\Enums\MemberStatus;
@endphp

<x-slot:subtitle>Gestiona los socios de tu gimnasio</x-slot:subtitle>

<div class="p-1 space-y-6">
  {{-- Membership stats --}}
  @if($this->stats['total'] > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
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
        <input type="text" placeholder="Buscar por nombre o código..."
          wire:model.live="search"
          class="block w-full pl-10 pr-3 py-[7px] text-[16px] border border-gray-300 shadow-sm rounded-lg focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 bg-white placeholder-gray-600" />
      </div>
    </div>
    <div>
      <flux:button variant="primary" icon="plus" wire:click="createMemberModal">
        Nuevo Socio
      </flux:button>
    </div>
  </div>

  @if($this->members->isEmpty())
    <div class="text-center py-20">
      <div class="text-gray-400 mb-3">
        <flux:icon icon="users" class="mx-auto h-12 w-12" />
      </div>
      @if ($this->statusFilter || $this->search)
        <h3 class="mt-2 font-medium text-gray-900">No hay resultados</h3>
        <p class="mt-1.5 text-sm text-gray-600">No hay socios que coincidan con tu búsqueda.</p>
      @else
        <h3 class="mt-2 font-medium text-gray-900">No hay socios registrados</h3>
        <p class="mt-1.5 text-sm text-gray-600">Comienza registrando un nuevo socio.</p>
        <div class="mt-6">
          <flux:button variant="primary" icon="plus" wire:click="createMemberModal">
            Nuevo Socio
          </flux:button>
        </div>
      @endif
    </div>
  @else
    {{-- Members List --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
      @foreach ($this->members as $member)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative">
          <!-- Status Badge -->
          <div class="absolute top-4 right-4">
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
              {{ $member->status == MemberStatus::ACTIVE ? 'bg-green-100 text-green-800' :
                ($member->status == MemberStatus::EXPIRED ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
              {{ $member->status->label() }}
            </span>
          </div>

          <div class="p-6">
            <div class="space-y-3">
              <!-- Card Header -->
              <div class="flex items-center space-x-3.5">
                <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border border-gray-200 transition-all">
                  @if ($member->photo)
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
                </div>
              </div>

              <!-- Card Body -->
              <div class="space-y-4 py-2.5">
                {{-- Code --}}
                <div>
                  <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide mb-1">Código</p>
                  <div class="flex items-center gap-0.5">
                    <flux:icon icon="hashtag" variant="mini" class="text-gray-500" />
                    <span class="font-medium text-gray-800">{{ $member->code }}</span>
                  </div>
                </div>

                {{-- Membership --}}
                <div>
                  <p class="text-xs font-semibold uppercase text-gray-500 tracking-wide mb-1">Membresía</p>
                  <div class="flex items-center gap-2">
                    <flux:icon icon="credit-card" class="w-5 h-5 text-gray-500" />
                    <span class="font-medium text-gray-800">
                        {{ $member->latestMembership()?->membershipType->name ?? 'Ninguna' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-end gap-3">
                <flux:button size="sm" variant="outline" wire:click="editMemberModal({{ $member->id }})">
                  Editar
                </flux:button>
                <flux:button size="sm" variant="primary" wire:click="$dispatch('show-profile', { member: {{ $member->id }} })">
                  Ver
                </flux:button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      {{ $this->members->links('pagination.custom') }}
    </div>
  @endif

  <!-- Create/Edit Form Modal -->
  @if ($showFormModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
            wire:click.stop>

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                {{ $editingMember ? 'Editar Socio' : 'Nuevo Socio' }}
              </h3>
            </div>

            <form wire:submit.prevent="saveMember">
              <!-- Modal Body -->
              <div class="px-6 py-4 space-y-5">
                <!-- Name -->
                <flux:field>
                  <flux:label for="name">Nombre completo</flux:label>
                  <flux:input wire:model="name" id="name" placeholder="Ej: Alfonso Gómez" />
                  <flux:error name="name" />
                </flux:field>

                <!-- Gender -->
                <flux:field>
                  <flux:label for="gender">Género</flux:label>
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
                <flux:field
                  x-data="webcamPhoto()"
                  x-init="init()"
                  x-on:close-modal.window="stopCamera()"
                >
                  <flux:label>Foto</flux:label>

                  {{-- Tab Switcher --}}
                  <div class="flex gap-1 bg-gray-100 rounded-lg p-1 mt-1 w-fit">
                    <button type="button"
                      @click="mode = 'upload'; stopCamera()"
                      :class="mode === 'upload' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                      class="px-3 py-1.5 text-sm font-medium rounded-md transition-all flex items-center gap-1.5"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                      Subir archivo
                    </button>

                    <button type="button"
                      @click="mode = 'webcam'; startCamera()"
                      :class="mode === 'webcam' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                      class="px-3 py-1.5 text-sm font-medium rounded-md transition-all flex items-center gap-1.5"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                      Webcam
                    </button>
                  </div>

                  {{-- ══════════ UPLOAD MODE ══════════ --}}
                  <div x-show="mode === 'upload'" class="mt-4">
                    @if ($existing_photo || $photo)
                      <!-- Photo Preview -->
                      <div class="flex items-center space-x-4">
                        <div class="shrink-0">
                          <div class="h-36 w-40 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            @if ($photo)
                              <img src="{{ $photo->temporaryUrl() }}" alt="Vista previa" class="h-full w-full object-cover">
                            @elseif ($existing_photo)
                              <img src="{{ Storage::url($existing_photo) }}" alt="Vista previa" class="h-full w-full object-cover">
                            @endif
                          </div>
                        </div>
                        <div class="flex-1 space-y-2">
                          <div class="flex items-center space-x-3">
                            <label for="photo" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50">
                              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                              Elegir foto
                            </label>
                            <button type="button" wire:click="removePhoto" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                              Quitar
                            </button>
                          </div>
                          <p class="text-sm text-gray-500">JPG, JPEG, PNG, WEBP, ...</p>
                        </div>
                      </div>
                    @else
                      <div class="flex gap-3 items-center">
                        <label for="photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                          Elegir Foto
                        </label>
                        <p class="text-sm text-gray-600">JPG, JPEG, PNG, WEBP, ...</p>
                      </div>
                    @endif

                    <!-- Hidden File Input -->
                    <input type="file" id="photo" wire:model="photo" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" />

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
                  </div>

                  {{-- ══════════ WEBCAM MODE ══════════ --}}
                  <div x-show="mode === 'webcam'" class="mt-3 space-y-3">

                    {{-- Error de cámara --}}
                    <div x-show="cameraError" class="flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      <span x-text="cameraError"></span>
                    </div>

                    {{-- Vista de la cámara + preview capturada --}}
                    <div class="relative rounded-lg overflow-hidden bg-gray-800 aspect-video w-full">
                      {{-- Video stream --}}
                      <video x-ref="video" autoplay playsinline muted
                        x-show="!captured"
                        class="w-full h-full object-cover"
                      ></video>

                      {{-- Foto capturada --}}
                      <img x-show="captured" :src="capturedSrc"
                        class="w-full h-full object-cover"
                        alt="Foto capturada"
                      />

                      {{-- Canvas oculto para captura --}}
                      <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>

                    {{-- Controles --}}
                    <div class="flex gap-2">
                      {{-- Capturar --}}
                      <button type="button"
                        @click="capture()"
                        x-show="streaming && !captured"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                        Capturar
                      </button>

                      {{-- Reintentar --}}
                      <button type="button"
                        @click="retake()"
                        x-show="captured"
                        class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reintentar
                      </button>

                      {{-- Usar foto --}}
                      <button type="button"
                        @click="usePhoto()"
                        x-show="captured && !uploading"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Cargar
                      </button>

                      {{-- Subiendo --}}
                      <div x-show="uploading" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-700">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Subiendo...
                      </div>
                    </div>

                    {{-- Foto usada con éxito --}}
                    <div x-show="photoUploaded" class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      Foto cargada correctamente.
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
    <div
      class="fixed font-medium top-5 right-5 bg-green-50 text-green-800 border border-green-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
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

  <livewire:members.profile />
</div>

@push('scripts')
<script>
function webcamPhoto() {
  return {
    // State
    mode: 'upload',
    stream: null,
    streaming: false,
    captured: false,
    capturedSrc: '',
    uploading: false,
    photoUploaded: false,
    cameraError: '',

    init() {
      // Stop camera when the Livewire component re-renders
      this.$watch('mode', (val) => {
        if (val !== 'webcam') this.stopCamera();
      });
    },

    async startCamera() {
      this.cameraError = '';
      this.captured = false;
      this.capturedSrc = '';
      this.photoUploaded = false;

      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        this.cameraError = 'Tu navegador no soporta acceso a la cámara.';
        return;
      }

      try {
        this.stream = await navigator.mediaDevices.getUserMedia({
          video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' },
          audio: false
        });
        this.$refs.video.srcObject = this.stream;
        await this.$refs.video.play();
        this.streaming = true;
      } catch (err) {
        if (err.name === 'NotAllowedError') {
          this.cameraError = 'Permiso denegado. Permite el acceso a la cámara en tu navegador.';
        } else if (err.name === 'NotFoundError') {
          this.cameraError = 'No se encontró ninguna cámara conectada.';
        } else {
          this.cameraError = 'No se pudo acceder a la cámara: ' + err.message;
        }
        this.streaming = false;
      }
    },

    stopCamera() {
      if (this.stream) {
        this.stream.getTracks().forEach(t => t.stop());
        this.stream = null;
      }
      this.streaming = false;
    },

    capture() {
      const video = this.$refs.video;
      const canvas = this.$refs.canvas;

      canvas.width  = video.videoWidth  || 640;
      canvas.height = video.videoHeight || 480;
      console.log(canvas.width);
      console.log(canvas.height);

      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      this.capturedSrc = canvas.toDataURL('image/jpeg', 0.92);
      this.captured = true;
      this.stopCamera();
    },

    retake() {
      this.captured = false;
      this.capturedSrc = '';
      this.photoUploaded = false;
      this.startCamera();
    },

    usePhoto() {
      this.uploading = true;

      const canvas = this.$refs.canvas;
      canvas.toBlob((blob) => {
        const file = new File([blob], 'webcam-capture.jpg', { type: 'image/jpeg' });

        // Use Livewire's built-in upload mechanism
        @this.upload('photo', file,
          // success
          () => {
            this.uploading = false;
            this.photoUploaded = true;
          },
          // error
          (err) => {
            this.uploading = false;
            this.cameraError = 'Error al subir la foto: ' + (err?.message ?? 'inténtalo de nuevo');
          }
        );
      }, 'image/jpeg', 0.92);
    },

  };
}
</script>
@endpush
