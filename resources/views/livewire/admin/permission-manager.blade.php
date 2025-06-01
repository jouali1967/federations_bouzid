<div>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Gestion des Rôles et Permissions</h1>
            </div>
        </div>

        @if (session()->has(\'message\'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session(\'message\') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has(\'error\'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session(\'error\') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Roles List -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rôles</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @if($roles && $roles->count() > 0)
                                @foreach ($roles as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center 
                                        {{ $selectedRole && $selectedRole->id == $role->id ? \'active\' : \'\' }}"
                                        wire:click="selectRole({{ $role->id }})" 
                                        style="cursor: pointer;">
                                        {{ $role->name }}
                                        <span class="badge bg-primary rounded-pill">{{ $role->permissions_count }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item">Aucun rôle trouvé.</li>
                            @endif
                        </ul>
                    </div>
                    {{-- Future: Add Role button --}}
                </div>
            </div>

            <!-- Permissions for Selected Role -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Permissions pour le rôle : 
                            <span class="fw-bold">{{ $selectedRole ? $selectedRole->name : \'N/A\' }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($selectedRole)
                            @if($permissions && $permissions->count() > 0)
                                <div class="row">
                                @php $half = ceil($permissions->count() / 2); @endphp
                                <div class="col-md-6">
                                    @foreach ($permissions->take($half) as $permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox"
                                                   value="{{ $permission->name }}"
                                                   id="perm_{{ $permission->id }}"
                                                   wire:click="togglePermission(\'{{ $permission->name }}\')"
                                                   {{ in_array($permission->name, $rolePermissions) ? \'checked\' : \'\' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                     @foreach ($permissions->skip($half) as $permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox"
                                                   value="{{ $permission->name }}"
                                                   id="perm_{{ $permission->id }}"
                                                   wire:click="togglePermission(\'{{ $permission->name }}\')"
                                                   {{ in_array($permission->name, $rolePermissions) ? \'checked\' : \'\' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                </div>
                            @else
                                <p>Aucune permission définie dans le système.</p>
                            @endif
                        @else
                            <p class="text-muted">Veuillez sélectionner un rôle pour voir ses permissions.</p>
                        @endif
                    </div>
                     {{-- Future: Add Permission button --}}
                </div>
            </div>
        </div>
    </div>
</div>
