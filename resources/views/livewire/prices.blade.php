<x-slot:subtitle>Gestióna los precios de los planes y visitas</x-slot:subtitle>

<div>
    <div class="p-6 pt-4 space-y-12">
        <!-- Plans Section -->
        <section>
            <div class="">
                <h2 class="text-2xl font-semibold text-gray-900 w-fit border-b border-gray-300">Planes</h2>
                {{-- <p class="mt-1 text-base text-gray-500">Tipos de planes disponibles</p> --}}
            </div>
            <div class="-mx-6">
                <livewire:plans />
            </div>
        </section>

        <flux:separator />

        <!-- Visit Types Section -->
        <section>
            <div class="">
                <h2 class="text-2xl font-semibold text-gray-900 border-b border-gray-300 w-fit">Visitas</h2>
                {{-- <p class="mt-1 text-base text-gray-500">Tipos de visitas disponibles</p> --}}
            </div>
            <livewire:visit-types />
        </section>
    </div>
</div>
