@if ($paginator->hasPages())
  <div class="flex justify-center mt-9">
    <div class="flex items-center space-x-4">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <flux:button size="sm" variant="outline" disabled>Anterior</flux:button>
      @else
        <flux:button size="sm" variant="outline" wire:click="previousPage" wire:loading.attr="disabled">Anterior
        </flux:button>
      @endif

      {{-- Pagination Elements --}}
      <span class="text-sm text-gray-500 px-4">
        Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
      </span>

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <flux:button size="sm" variant="outline" wire:click="nextPage" wire:loading.attr="disabled">Siguiente
        </flux:button>
      @else
        <flux:button size="sm" variant="outline" disabled>Siguiente</flux:button>
      @endif
    </div>
  </div>
@endif
