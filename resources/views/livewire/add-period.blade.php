<div>
  @if ($showModal)
    <div class="fixed inset-0 m-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50" wire:click="closeModal">
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg"
            wire:click.stop>

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-medium text-gray-900">
                Nuevo Periodo
              </h3>
              <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            {{-- Modal Body --}}
            <form wire:submit.prevent="save">
              <div class="px-6 py-6 space-y-6">

                <div class="space-y-4">
                  {{-- Start Date --}}
                  <flux:field>
                    <flux:label>Fecha de Inicio</flux:label>
                    <flux:input type="date" wire:model.live="start_date" />
                    <flux:error name="start_date" />
                  </flux:field>

                  {{-- Calculated End Date --}}
                  @if ($this->endDate)
                    <div class="text-sm text-gray-700 bg-gray-100 p-3 rounded-md mt-2">
                      El periodo terminará el: <span class="font-medium text-gray-900">{{ $this->endDate->format('d/m/Y') }}</span>
                    </div>
                  @endif
                </div>
              </div>

              {{-- Modal Footer --}}
              <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50">
                <flux:button variant="ghost" wire:click="closeModal">
                  Cancelar
                </flux:button>
                <flux:button type="submit" variant="primary">
                  Confirmar
                </flux:button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
