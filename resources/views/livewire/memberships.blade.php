<x-slot:subtitle>Administra las membresías activas y vencidas de tus socios</x-slot:subtitle>

<div>
    <div class="p-6 pt-4 space-y-6">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Membresías Activas</p>
                        <p class="text-3xl font-bold text-gray-900">18</p>
                    </div>
                    <div class="h-12 w-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Membresías Vencidas</p>
                        <p class="text-3xl font-bold text-gray-900">14</p>
                    </div>
                    <div class="h-12 w-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L5.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Socios</p>
                        <p class="text-3xl font-bold text-gray-900">32</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda con botón -->
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
                <flux:button variant="primary" icon="plus">
                    Nueva Membresía
                </flux:button>
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
