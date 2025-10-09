<x-layouts.app.sidebar :title="$title ?? null" :subtitle="$subtitle ?? null" :actions="$actions ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
