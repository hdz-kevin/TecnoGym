<x-slot:title>Dashboard</x-slot:title>
<x-slot:subtitle>Resumen y cortes de caja</x-slot:subtitle>

<div class="p-4 space-y-8">
  @php
    $sections = [
      [
        'label' => 'Hoy',
        'data'  => $this->today,
      ],
      [
        'label' => 'Esta semana',
        'data'  => $this->thisWeek,
      ],
      [
        'label' => 'Este mes',
        'data'  => $this->thisMonth,
      ],
    ];
  @endphp

  @foreach ($sections as $section)
    <div>
      {{-- Section heading --}}
      <p class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3.5">
        {{ $section['label'] }}
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Visitas --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 transition-all hover:shadow-md">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <flux:icon icon="arrow-right-end-on-rectangle" class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="font-medium text-gray-700">Visitas</p>
              <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $section['data']['visits_count'] }}</p>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[15px] font-medium text-gray-700">Ingresos</span>
            <span class="text-xl font-semibold text-green-700">
              ${{ number_format($section['data']['visits_income']) }}
            </span>
          </div>
        </div>

        {{-- Membresías nuevas --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 transition-all hover:shadow-md">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <flux:icon icon="user-plus" class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="font-medium text-gray-700">Membresías nuevas</p>
              <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $section['data']['new_count'] }}</p>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[15px] font-medium text-gray-700">Ingresos</span>
            <span class="text-xl font-semibold text-green-700">
              ${{ number_format($section['data']['new_income']) }}
            </span>
          </div>
        </div>

        {{-- Renovaciones --}}
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 transition-all hover:shadow-md">
          <div class="flex items-center">
            <div class="p-2 bg-amber-100 rounded-lg">
              <flux:icon icon="arrow-path" class="w-6 h-6 text-amber-600" />
            </div>
            <div class="ml-4">
              <p class="font-medium text-gray-700">Renovaciones</p>
              <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $section['data']['renewals_count'] }}</p>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[15px] font-medium text-gray-700">Ingresos</span>
            <span class="text-xl font-semibold text-green-700">
              ${{ number_format($section['data']['renewals_income']) }}
            </span>
          </div>
        </div>

      </div>

      {{-- Total de la sección --}}
      <div class="mt-5 flex justify-end">
        <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2">
          <span class="text-gray-800 font-medium">Total: </span>
          <span class="text-lg font-bold text-gray-800">
            ${{ number_format(
              $section['data']['visits_income'] +
              $section['data']['new_income'] +
              $section['data']['renewals_income']
            ) }}
          </span>
        </div>
      </div>

    </div>
  @endforeach
</div>
