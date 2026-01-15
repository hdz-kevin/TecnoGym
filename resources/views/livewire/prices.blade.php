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
</div>
