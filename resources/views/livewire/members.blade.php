<x-slot:subtitle>Gestiona tus socios y su estado</x-slot:subtitle>

<div>
    <!-- Content -->
    <div class="p-6 space-y-6">
        <!-- Search and Filters -->
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex-1 max-w-md">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre..."
                    icon="magnifying-glass"
                />
            </div>
            <div class="flex gap-3">
                <flux:select placeholder="Todos" class="w-32">
                    <flux:select.option value="">Todos</flux:select.option>
                    <flux:select.option value="active">Con membresía</flux:select.option>
                    <flux:select.option value="inactive">Sin membresía</flux:select.option>
                </flux:select>
                <flux:select placeholder="Ordenar por nombre" class="w-40">
                    <flux:select.option value="name">Ordenar por nombre</flux:select.option>
                    <flux:select.option value="date">Ordenar por fecha</flux:select.option>
                    <flux:select.option value="status">Ordenar por estado</flux:select.option>
                </flux:select>
            </div>
        </div>

        <!-- Members Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Member Card 1 - Ana Ramirez -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            AR
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Ana Ramirez</h3>
                            <p class="text-sm text-gray-500">ID: m1</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                        Sin membresía
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sin visitas registradas</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Registrar visita</flux:button>
                    <flux:button variant="primary" size="sm" class="text-xs">Asignar membresía</flux:button>
                </div>
            </div>

            <!-- Member Card 2 - Carlos Duarte -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            CD
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Carlos Duarte</h3>
                            <p class="text-sm text-gray-500">ID: m2</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                        Sin membresía
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sin visitas registradas</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Registrar visita</flux:button>
                    <flux:button variant="primary" size="sm" class="text-xs">Asignar membresía</flux:button>
                </div>
            </div>

            <!-- Member Card 3 - Luz Morales -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            LM
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Luz Morales</h3>
                            <p class="text-sm text-gray-500">ID: m3</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                        Sin membresía
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sin visitas registradas</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Registrar visita</flux:button>
                    <flux:button variant="primary" size="sm" class="text-xs">Asignar membresía</flux:button>
                </div>
            </div>

            <!-- Member Card 4 - Juan Martínez (Con membresía) -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            JM
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Juan Martínez</h3>
                            <p class="text-sm text-gray-500">ID: m4</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        Premium
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Última visita: Hace 2 días</p>
                    <p class="text-sm text-gray-500">Total visitas: 47</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Registrar visita</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Gestionar membresía</flux:button>
                </div>
            </div>

            <!-- Member Card 5 - María Rodríguez (VIP) -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            MR
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">María Rodríguez</h3>
                            <p class="text-sm text-gray-500">ID: m5</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                        VIP
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Última visita: Hoy</p>
                    <p class="text-sm text-gray-500">Total visitas: 152</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Registrar visita</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Gestionar membresía</flux:button>
                </div>
            </div>

            <!-- Member Card 6 - Pedro García (Suspendido) -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow opacity-75">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            PG
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Pedro García</h3>
                            <p class="text-sm text-gray-500">ID: m6</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                        Suspendido
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Última visita: Hace 1 mes</p>
                    <p class="text-sm text-gray-500">Suspendido por falta de pago</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button variant="ghost" size="sm" class="text-xs">Ver</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs">Editar</flux:button>
                    <flux:button variant="ghost" size="sm" class="text-xs" disabled>Registrar visita</flux:button>
                    <flux:button variant="primary" size="sm" class="text-xs">Reactivar</flux:button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center mt-8">
            <div class="flex items-center space-x-2">
                <flux:button variant="ghost" size="sm" icon="chevron-left" disabled>Anterior</flux:button>
                <flux:button variant="primary" size="sm">1</flux:button>
                <flux:button variant="ghost" size="sm">2</flux:button>
                <flux:button variant="ghost" size="sm">3</flux:button>
                <flux:button variant="ghost" size="sm">...</flux:button>
                <flux:button variant="ghost" size="sm">15</flux:button>
                <flux:button variant="ghost" size="sm" icon="chevron-right">Siguiente</flux:button>
            </div>
        </div>
    </div>
</div>
