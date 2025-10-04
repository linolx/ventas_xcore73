<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Módulos de Gestión</h3>
                    
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                ➔ Gestión de Usuarios (Pendientes de Activación)
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-500 hover:text-gray-700">
                                ➔ Gestión de Roles y Permisos (Fase 3)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>