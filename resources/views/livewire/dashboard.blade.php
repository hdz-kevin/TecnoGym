<x-slot:title>Dashboard</x-slot:title>
<x-slot:subtitle>Corte de caja diario, semanal y mensual</x-slot:subtitle>

<div class="p-1 space-y-6">
  {{-- Period Tabs --}}
  <div class="flex gap-1 bg-gray-100 rounded-lg p-1 w-fit">
    @foreach ($periods as $key => $label)
      <button
        wire:click="setPeriod('{{ $key }}')"
        class="px-4 py-2 text-sm font-medium rounded-md transition-all
          {{ $activePeriod === $key
              ? 'bg-white text-gray-900 shadow-sm'
              : 'text-gray-600 hover:text-gray-900 hover:cursor-pointer' }}"
      >
        {{ $label }}
      </button>
    @endforeach
  </div>

  {{-- Summary Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    {{-- Visits --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200 transition-all hover:shadow-md">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <flux:icon icon="arrow-right-end-on-rectangle" class="w-6 h-6 text-blue-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-800">Visitas</p>
            <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $this->visits->count() }}</p>
          </div>
        </div>
      <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
        <span class="text-[15px] font-medium text-gray-600">Ingresos</span>
        <span class="text-xl font-semibold text-green-700">
          ${{ number_format($this->visits->sum('price_paid')) }}
        </span>
      </div>
    </div>

    {{-- New Memberships --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200 transition-all hover:shadow-md">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <flux:icon icon="user-plus" class="w-6 h-6 text-green-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-800">Membresías nuevas</p>
            <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $this->newMemberships->count() }}</p>
          </div>
        </div>
      <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
        <span class="text-[15px] font-medium text-gray-600">Ingresos</span>
        <span class="text-xl font-semibold text-green-700">
          ${{ number_format($this->newMemberships->sum('price_paid')) }}
        </span>
      </div>
    </div>

    {{-- Renewals --}}
    <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200 transition-all hover:shadow-md">
        <div class="flex items-center">
          <div class="p-2 bg-amber-100 rounded-lg">
            <flux:icon icon="arrow-path" class="w-6 h-6 text-amber-600" />
          </div>
          <div class="ml-4">
            <p class="font-medium text-gray-800">Renovaciones</p>
            <p class="text-2xl mt-0.5 font-bold text-gray-800">{{ $this->renewals->count() }}</p>
          </div>
        </div>
      <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
        <span class="text-[15px] font-medium text-gray-600">Ingresos</span>
        <span class="text-xl font-semibold text-green-700">
          ${{ number_format($this->renewals->sum('price_paid')) }}
        </span>
      </div>
    </div>
  </div>

  {{-- Total --}}
  <div class="flex justify-end">
    <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-5 py-2.5">
      <span class="text-gray-800 font-medium">Total: </span>
      <span class="text-lg font-semibold text-gray-800">
        ${{
          number_format(
            $this->visits->sum('price_paid') +
            $this->newMemberships->sum('price_paid') +
            $this->renewals->sum('price_paid')
          )
        }}
      </span>
    </div>
  </div>

  {{-- ================= Detail Tables ================= --}}

  {{-- Visitas Table --}}
  <div class="mt-10">
    <p class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3.5">Visitas</p>

    @if ($this->visits->isEmpty())
      <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
        <flux:icon icon="arrow-right-end-on-rectangle" class="mx-auto h-8 w-8 text-gray-400" />
        <p class="mt-2.5 text-gray-500">No hay visitas registradas</p>
      </div>
    @else
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Fecha y Hora</th>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Tipo de Visita</th>
                <th class="px-6 py-3 text-right font-medium text-gray-700">Ingreso</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach ($this->visits as $visit)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-medium text-gray-800">{{ $visit->formatted_visit_at }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-900">{{ $visit->visitType->name }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <span class="font-medium text-gray-800">${{ number_format($visit->price_paid) }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>

  {{-- New Memberships Table --}}
  <div class="mt-10">
    <p class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3.5">Membresías nuevas</p>

    @if ($this->newMemberships->isEmpty())
      <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
        <flux:icon icon="user-plus" class="mx-auto h-8 w-8 text-gray-400" />
        <p class="mt-2.5 text-gray-500">No se han registrado nuevas membresías</p>
      </div>
    @else
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full table-fixed divide-y divide-gray-200">
            <colgroup>
              <col class="w-[40%]" />
              <col class="w-[25%]" />
              <col class="w-[20%]" />
              <col class="w-[15%]" />
            </colgroup>
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Socio</th>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Tipo</th>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Duración</th>
                <th class="px-6 py-3 text-right font-medium text-gray-700">Ingreso</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach ($this->newMemberships as $period)
                <tr>
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                      <button
                        wire:click="$dispatch('show-profile', { member: {{ $period->membership->member->id }} })"
                        class="h-13 w-13 bg-gray-100 rounded-full flex items-center justify-center shrink-0 hover:ring-2 hover:ring-gray-400 transition cursor-pointer"
                      >
                        @if($photo = $period->membership->member->photo)
                          <img
                            src="{{ Storage::url($photo) }}"
                            class="h-full w-full rounded-full object-cover" alt="member-photo"
                          />
                        @else
                          <span class="text-sm font-semibold text-gray-600">{{ $period->membership->member->initials() }}</span>
                        @endif
                      </button>
                      <div class="min-w-0">
                        <div class="font-medium text-gray-800 truncate">{{ $period->membership->member->name }}</div>
                        <div class="text-sm text-gray-600 mt-0.5"># {{ $period->membership->member->code }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-900">{{ $period->membership->membershipType->name }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-900">{{ $period->duration->formatted }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <span class="font-medium text-gray-800">${{ number_format($period->price_paid) }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>

  {{-- Renewals Table --}}
  <div class="mt-10">
    <p class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-3.5">Renovaciones</p>

    @if ($this->renewals->isEmpty())
      <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
        <flux:icon icon="arrow-path" class="mx-auto h-8 w-8 text-gray-400" />
        <p class="mt-2.5 text-gray-500">No se han renovado membresías</p>
      </div>
    @else
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full table-fixed divide-y divide-gray-200">
            <colgroup>
              <col class="w-[40%]" />
              <col class="w-[25%]" />
              <col class="w-[20%]" />
              <col class="w-[15%]" />
            </colgroup>
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Socio</th>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Tipo</th>
                <th class="px-6 py-3 text-left font-medium text-gray-700">Duración</th>
                <th class="px-6 py-3 text-right font-medium text-gray-700">Ingreso</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach ($this->renewals as $period)
                <tr>
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                      <button
                        wire:click="$dispatch('show-profile', { member: {{ $period->membership->member->id }} })"
                        class="h-13 w-13 bg-gray-100 rounded-full flex items-center justify-center shrink-0 hover:ring-2 hover:ring-gray-400 transition cursor-pointer"
                      >
                        @if($photo = $period->membership->member->photo)
                          <img src="{{ Storage::url($photo) }}" class="h-full w-full rounded-full object-cover" alt="member-photo" />
                        @else
                          <span class="text-sm font-semibold text-gray-600">{{ $period->membership->member->initials() }}</span>
                        @endif
                      </button>
                      <div class="min-w-0">
                        <div class="font-medium text-gray-800 truncate">{{ $period->membership->member->name }}</div>
                        <div class="text-sm mt-0.5 text-gray-600"># {{ $period->membership->member->code }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-900">{{ $period->membership->membershipType->name }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-900">{{ $period->duration->formatted }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <span class="font-medium text-gray-800">${{ number_format($period->price_paid) }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>

  <livewire:members.profile />

</div>
