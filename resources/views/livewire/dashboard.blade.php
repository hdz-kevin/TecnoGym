<x-slot:title>Dashboard</x-slot:title>
<x-slot:subtitle>Resumen y cortes de caja</x-slot:subtitle>

<div class="p-6 pt-4 space-y-8">
  @php
    $periods = [
      ['label' => 'Hoy',         'data' => $this->today],
      ['label' => 'Esta semana', 'data' => $this->thisWeek],
      ['label' => 'Este mes',    'data' => $this->thisMonth],
    ];
  @endphp

  @foreach ($periods as $period)
    <section>
      {{-- Encabezado del período --}}
      <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">
        {{ $period['label'] }}
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Visitas --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-5 py-4">
          <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50">
              <flux:icon icon="arrow-right-end-on-rectangle" class="w-4 h-4 text-blue-600" />
            </span>
            <span class="text-sm font-medium text-gray-600">Visitas</span>
          </div>
          <div class="flex items-end justify-between">
            <div>
              <div class="text-2xl font-bold text-gray-800 leading-none">
                {{ $period['data']['visits_count'] }}
              </div>
              <div class="text-xs text-gray-500 mt-1">entradas</div>
            </div>
            <div class="text-right">
              <div class="text-xl font-semibold text-green-700 leading-none">
                ${{ number_format($period['data']['visits_income']) }}
              </div>
              <div class="text-xs text-gray-500 mt-1">recaudado</div>
            </div>
          </div>
        </div>

        {{-- Nuevas membresías --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-5 py-4">
          <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-purple-50">
              <flux:icon icon="user-plus" class="w-4 h-4 text-purple-600" />
            </span>
            <span class="text-sm font-medium text-gray-600">Membresías nuevas</span>
          </div>
          <div class="flex items-end justify-between">
            <div>
              <div class="text-2xl font-bold text-gray-800 leading-none">
                {{ $period['data']['new_count'] }}
              </div>
              <div class="text-xs text-gray-500 mt-1">altas</div>
            </div>
            <div class="text-right">
              <div class="text-xl font-semibold text-green-700 leading-none">
                ${{ number_format($period['data']['new_income']) }}
              </div>
              <div class="text-xs text-gray-500 mt-1">recaudado</div>
            </div>
          </div>
        </div>

        {{-- Renovaciones --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-5 py-4">
          <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-50">
              <flux:icon icon="arrow-path" class="w-4 h-4 text-amber-600" />
            </span>
            <span class="text-sm font-medium text-gray-600">Renovaciones</span>
          </div>
          <div class="flex items-end justify-between">
            <div>
              <div class="text-2xl font-bold text-gray-800 leading-none">
                {{ $period['data']['renewals_count'] }}
              </div>
              <div class="text-xs text-gray-500 mt-1">renovaciones</div>
            </div>
            <div class="text-right">
              <div class="text-xl font-semibold text-green-700 leading-none">
                ${{ number_format($period['data']['renewals_income']) }}
              </div>
              <div class="text-xs text-gray-500 mt-1">recaudado</div>
            </div>
          </div>
        </div>

      </div>

      {{-- Total del período --}}
      <div class="mt-3 flex justify-end">
        <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm">
          <span class="text-gray-500">Total {{ $period['label'] }}:</span>
          <span class="font-bold text-gray-800">
            ${{ number_format(
              $period['data']['visits_income'] +
              $period['data']['new_income'] +
              $period['data']['renewals_income']
            ) }}
          </span>
        </div>
      </div>

    </section>
  @endforeach

</div>
