<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
  <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
      <x-app-logo />
    </a>

    <flux:navlist variant="outline">
      <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
          {{ __('Dashboard') }}
        </flux:navlist.item>

        <flux:navlist.item icon="credit-card" :href="route('memberships.index')" :current="request()->routeIs('memberships.index')" wire:navigate>
          {{ __('Membresías') }}
        </flux:navlist.item>

        <flux:navlist.item icon="users" :href="route('members.index')" :current="request()->routeIs('members.index')" wire:navigate>
          {{ __('Socios') }}
        </flux:navlist.item>

        <flux:navlist.item icon="calendar-days" :href="route('visits.index')" :current="request()->routeIs('visits.index')" wire:navigate>
          {{ __('Visitas') }}
        </flux:navlist.item>

        <flux:navlist.item icon="qr-code" :href="route('verify-membership')" :current="request()->routeIs('verify-membership')" wire:navigate>
          {{ __('Verificar') }}
        </flux:navlist.item>
      </flux:navlist.group>

      <flux:navlist.group :heading="__('Configuración')" class="grid">
        <flux:navlist.item icon="currency-dollar" :href="route('prices.index')" :current="request()->routeIs('prices.index')" wire:navigate>
          {{ __('Precios') }}
        </flux:navlist.item>
      </flux:navlist.group>
    </flux:navlist>
  </flux:sidebar>

  <!-- Mobile User Menu -->
  <flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
  </flux:header>

  {{-- Top Header --}}
  <section class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
        <p class="text-base text-gray-600 mt-0.5">{{ $subtitle ?? 'Resumen general de tu gimnasio' }}</p>
      </div>
      @isset($actions)
        <div class="flex items-center gap-3">
          {{ $actions }}
        </div>
      @endisset
    </div>
  </section>

  {{ $slot }}

  @fluxScripts

  @stack('scripts')
</body>

</html>
