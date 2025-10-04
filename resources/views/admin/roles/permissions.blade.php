<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permisos del Rol: ') . $role->name }}
            </h2>
            <a href="{{ route('admin.roles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('admin.roles.updatePermissions', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <p class="text-sm text-blue-700">
                                    Selecciona los permisos que deseas asignar al rol <strong>{{ $role->name }}</strong>.
                                    Los permisos controlan las acciones que los usuarios con este rol pueden realizar.
                                </p>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Permisos Disponibles</h3>
                                <div class="text-sm text-gray-600">
                                    <strong>Seleccionados:</strong> <span id="selected-count">{{ count($rolePermissions) }}</span>
                                </div>
                            </div>
                            
                            @foreach ($allPermissions as $group => $perms)
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-gray-700 capitalize">
                                            {{ ucfirst($group) }}
                                        </h4>
                                        <label class="flex items-center space-x-2 text-sm text-blue-600 cursor-pointer">
                                            <input type="checkbox" 
                                                   class="select-all rounded border-gray-300"
                                                   data-group="{{ $group }}">
                                            <span>Seleccionar todos</span>
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach ($perms as $permission)
                                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded group-checkbox" data-group="{{ $group }}">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}"
                                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                                       class="permission-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.roles.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Guardar Permisos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Contar permisos seleccionados
        function updateCount() {
            const count = document.querySelectorAll('.permission-checkbox:checked').length;
            document.getElementById('selected-count').textContent = count;
        }

        // Seleccionar/deseleccionar todos los permisos de un grupo
        document.querySelectorAll('.select-all').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const group = this.dataset.group;
                const groupCheckboxes = document.querySelectorAll(`.group-checkbox[data-group="${group}"] .permission-checkbox`);
                groupCheckboxes.forEach(cb => cb.checked = this.checked);
                updateCount();
            });
        });

        // Actualizar contador cuando se cambia un checkbox
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateCount);
        });
    </script>
</x-app-layout>