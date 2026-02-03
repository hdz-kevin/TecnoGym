<x-slot:subtitle>Gestiona los precios de los planes y visitas</x-slot:subtitle>

<div>
  <div class="p-6 pt-4 space-y-12">
    <section class="mb-15">
      <h2 class="text-2xl font-semibold text-gray-900 w-fit border-b border-gray-300">Planes</h2>
      <livewire:plans />
    </section>

    <flux:separator />

    <section class="mt-15">
      <h2 class="text-2xl font-semibold text-gray-900 border-b border-gray-300 w-fit">Visitas</h2>
      <livewire:visit-types />
    </section>
  </div>

  <!-- Flash Messages -->
  @if (session()->has('message'))
    <div
      wire:key="{{ Str::random() }}"
      class="fixed font-medium top-5 right-5 bg-green-50 text-green-800 border border-green-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('message') }}
    </div>
  @endif

  @if (session()->has('error'))
    <div
      wire:key="{{ Str::random() }}"
      class="fixed font-medium top-5 right-5 bg-red-50 text-red-800 border border-red-300 px-6 py-2.5 rounded-lg shadow-lg z-50"
      x-data="{ show: true }"
      x-show="show"
      x-init="setTimeout(() => show = false, 3 * 1000)"
    >
      {{ session('error') }}
    </div>
  @endif
</div>
