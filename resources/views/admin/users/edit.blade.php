<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Usuario: ') . $user->name }}
            </h2>
            <a href="{{ route('admin.users.all') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Información Básica</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre Completo *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                           required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                           required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estado de Cuenta -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Estado de Cuenta</h3>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="font-medium text-gray-700">Cuenta Activa</span>
                                        <p class="text-sm text-gray-500">Si está desactivada, el usuario no podrá acceder al sistema</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Roles Asignados</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($roles as $role)
                                    <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->name }}"
                                               {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <span class="font-medium text-gray-700">{{ $role->name }}</span>
                                            <p class="text-xs text-gray-500">{{ $role->permissions->count() }} permisos</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            @error('roles')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información Adicional -->
                        <div class="mb-8">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Usuario registrado el:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        @if ($user->email_verified_at)
                                            <p class="text-sm text-blue-700 mt-1">
                                                <strong>Email verificado el:</strong> {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                            </p>
                                        @else
                                            <p class="text-sm text-blue-700 mt-1">
                                                <strong>Email:</strong> No verificado
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.users.all') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>