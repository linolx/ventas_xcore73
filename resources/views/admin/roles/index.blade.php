<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Roles y Permisos') }}
            </h2>
            <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Crear Nuevo Rol
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($roles->isEmpty())
                        <p class="text-lg">No hay roles creados en este momento.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($roles as $role)
                                <div class="border rounded-lg p-5 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $role->name }}</h3>
                                        @if (in_array($role->name, ['Admin', 'User']))
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Sistema</span>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Permisos:</strong> {{ $role->permissions->count() }}
                                        </p>
                                        
                                        @if ($role->permissions->count() > 0)
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach ($role->permissions->take(5) as $permission)
                                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                                @if ($role->permissions->count() > 5)
                                                    <span class="text-xs text-gray-500 px-2 py-1">
                                                        +{{ $role->permissions->count() - 5 }} más
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex gap-2 mt-4">
                                        <a href="{{ route('admin.roles.permissions', $role) }}" 
                                           class="flex-1 text-center bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-3 rounded">
                                            Permisos
                                        </a>

                                        @if (!in_array($role->name, ['Admin', 'User']))
                                            <a href="{{ route('admin.roles.edit', $role) }}" 
                                               class="flex-1 text-center bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-2 px-3 rounded">
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este rol?');"
                                                  class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>