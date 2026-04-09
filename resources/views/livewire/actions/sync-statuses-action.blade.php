<div
  x-data="syncStatusesData()"
  @statuses-synced.window="showNotification($event.detail.count)"
>
  {{-- Sync Button --}}
  <button
    wire:click="sync"
    wire:loading.attr="disabled"
    class="border border-gray-300 group w-full flex items-center justify-center gap-2 p-3 rounded-lg text-sm font-medium text-gray-800
      bg-gray-50 hover:bg-gray-100 transition-all duration-150 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
  >
    <span wire:loading.remove wire:target="sync">
      <flux:icon icon="arrow-path" class="w-5 h-5 shrink-0" />
    </span>
    <span wire:loading wire:target="sync">
      <flux:icon icon="arrow-path" class="w-5 h-5 shrink-0 animate-spin" />
    </span>

    <span wire:loading.remove wire:target="sync">Actualizar</span>
    <span wire:loading wire:target="sync">...</span>
  </button>

  {{-- Notification Toast --}}
  <div
    x-show="notification !== null"
    class="fixed top-6 right-5 z-50"
    style="display: none;"
  >
    {{-- Success toast --}}
    <div
      x-show="notification && notification.type === 'success'"
      class="flex items-start gap-3 px-5 py-3 rounded-lg shadow-lg font-medium max-w-sm bg-green-50 text-green-800 border border-green-300"
    >
      <span x-text="notification && notification.message" class="leading-snug"></span>
    </div>

    {{-- Error toast --}}
    <div
      x-show="notification && notification.type === 'error'"
      class="flex items-start gap-3 px-5 py-3 rounded-lg shadow-lg font-medium max-w-sm bg-red-50 text-red-800 border border-red-300"
    >
      <flux:icon icon="x-circle" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
      <span x-text="notification && notification.message" class="text-sm leading-snug"></span>
    </div>
  </div>
</div>

<script>
  function syncStatusesData() {
    return {
      notification: null,

      showNotification(count) {
        this.notification = {
          type: 'success',
          message: count > 0
            ? (count == 1 ? 'Una membresía fue actualizada' : count + ' membresías fueron actualizadas')
            : 'Todo al día, sin cambios necesarios.',
        };

        setTimeout(() => { this.notification = null; }, 4000);
      },
    };
  }
</script>
