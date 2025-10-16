<x-slot:subtitle>Administra las membresías activas y vencidas de tus socios</x-slot:subtitle>

<div>
    <div class="p-6 pt-4 space-y-6">
        <!-- Header con botón -->
        <div class="flex justify-end">
            <flux:button variant="primary" icon="plus">
                Nueva Membresía
            </flux:button>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="flex-1">
                <flux:input placeholder="Buscar por nombre..." class="w-full" />
            </div>
            <div class="flex gap-3">
                <flux:select placeholder="Estado" class="min-w-40">
                    <flux:select.option value="all">Todos los estados</flux:select.option>
                    <flux:select.option value="active">Activas</flux:select.option>
                    <flux:select.option value="expired">Vencidas</flux:select.option>
                </flux:select>
                <flux:select placeholder="Tipo" class="min-w-40">
                    <flux:select.option value="all">Todos los tipos</flux:select.option>
                    <flux:select.option value="general">General</flux:select.option>
                    <flux:select.option value="estudiante">Estudiante</flux:select.option>
                </flux:select>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Membresías Activas</p>
                        <p class="text-2xl font-bold text-gray-900">18</p>
                    </div>
                    <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <div class="h-3 w-3 bg-green-500 rounded-full"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Membresías Vencidas</p>
                        <p class="text-2xl font-bold text-gray-900">14</p>
                    </div>
                    <div class="h-8 w-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <div class="h-3 w-3 bg-red-500 rounded-full"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Socios</p>
                        <p class="text-2xl font-bold text-gray-900">32</p>
                    </div>
                    <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <div class="h-3 w-3 bg-blue-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de membresías en grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6">

            <!-- Membresía activa -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Activa
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Info del socio -->
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">JD</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">Juan Domínguez</h3>
                                <p class="text-sm text-gray-600">General • Mensual</p>
                            </div>
                        </div>

                        <!-- Información de período -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">15 Sep 2025</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Vencimiento</span>
                                <span class="text-sm font-medium text-gray-900">15 Oct 2025</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-green-600">15 días restantes</span>
                            </div>
                        </div>

                        <!-- Precio y acciones -->
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$400</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="primary">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membresía vencida -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Vencida
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Info del socio -->
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">MP</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">María Pérez</h3>
                                <p class="text-sm text-gray-600">Estudiante • Mensual</p>
                            </div>
                        </div>

                        <!-- Información de período -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">10 Ago 2025</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Venció</span>
                                <span class="text-sm font-medium text-gray-900">10 Sep 2025</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-red-600">Vencida hace 35 días</span>
                            </div>
                        </div>

                        <!-- Precio y acciones -->
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$350</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="danger">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membresía activa 2 -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Activa
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">LG</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">Luis García</h3>
                                <p class="text-sm text-gray-600">General • Semestral</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">01 Jul 2025</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Vencimiento</span>
                                <span class="text-sm font-medium text-gray-900">01 Ene 2026</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-green-600">78 días restantes</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$2,000</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="primary">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membresía activa 3 -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Activa
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">AR</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">Ana Rodríguez</h3>
                                <p class="text-sm text-gray-600">Estudiante • Mensual</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">01 Oct 2025</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Vencimiento</span>
                                <span class="text-sm font-medium text-gray-900">01 Nov 2025</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-green-600">17 días restantes</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$350</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="primary">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membresía vencida 2 -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Vencida
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">CT</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">Carlos Torres</h3>
                                <p class="text-sm text-gray-600">General • Anual</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">01 Ene 2024</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Venció</span>
                                <span class="text-sm font-medium text-gray-900">01 Ene 2025</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-red-600">Vencida hace 287 días</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$4,000</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="danger">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membresía activa 4 -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm relative">
                <!-- Badge de estado -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Activa
                    </span>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-gray-50">SM</span>
                            </div>
                            <div>
                                <h3 class="text-[19px] font-medium text-gray-900">Sofia Martín</h3>
                                <p class="text-sm text-gray-600">General • Trimestral</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Inicio</span>
                                <span class="text-sm font-medium text-gray-900">15 Ago 2025</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Vencimiento</span>
                                <span class="text-sm font-medium text-gray-900">15 Nov 2025</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Estado</span>
                                <span class="text-sm font-semibold text-green-600">31 días restantes</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-gray-900">$1,100</p>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="outline">
                                    Historial
                                </flux:button>
                                <flux:button size="sm" variant="primary">
                                    Renovar
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Paginación -->
        <div class="flex justify-center mt-8">
            <div class="flex items-center space-x-2">
                <flux:button size="sm" variant="outline">Anterior</flux:button>
                <span class="text-sm text-gray-500 px-4">Página 1 de 3</span>
                <flux:button size="sm" variant="outline">Siguiente</flux:button>
            </div>
        </div>

    </div>
