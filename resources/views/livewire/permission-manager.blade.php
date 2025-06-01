<div>

  <div class="container-fluid mt-4">
    <div class="row">
      <!-- Permissions Section -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Manage Permissions</h4>
          </div>
          <div class="card-body">
            @if (session()->has('message_permission'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('message_permission') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <form wire:submit.prevent="createPermission" class="mb-3">
              <div class="mb-3">
                <label for="newPermissionName" class="form-label">New Permission Name:</label>
                <input type="text" id="newPermissionName" class="form-control" wire:model.defer="newPermissionName">
                @error('newPermissionName') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Create Permission</button>
            </form>
            <h5>All Permissions:</h5>
            @if($permissions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            {{-- <th scope="col">Actions</th> --}} {{-- Uncomment if you plan to add actions --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $index => $permission)
                        <tr>
                            <th scope="row">{{ $permissions->firstItem() + $index }}</th>
                            <td>{{ $permission->name }}</td>
                            {{-- <td> --}}
                                {{-- Add actions like edit/delete here if needed --}}
                                {{-- Example: <button class="btn btn-sm btn-outline-danger">Delete</button> --}}
                            {{-- </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted">No permissions found.</p>
            @endif
            <div class="mt-3">
              {{ $permissions->links() }}
            </div>
          </div>
        </div>
      </div>

      <!-- Roles Section -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Manage Roles</h4>
          </div>
          <div class="card-body">
            @if (session()->has('message_debug'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              DEBUG: {{ session('message_debug') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session()->has('message_debug_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              DEBUG ERROR: {{ session('message_debug_error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session()->has('message_role'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('message_role') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session()->has('message_role_permission'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('message_role_permission') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if ($managingPermissionsForRole)
            <h5>Assign Permissions to Role: <span class="text-info">{{ $managingPermissionsForRole->name }}</span></h5>
            <form wire:submit.prevent="updateRolePermissions">
              <div class="mb-3">
                @foreach ($allPermissions as $permission)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" wire:model.defer="selectedRolePermissions" value="{{ $permission->id }}" id="perm_{{ $permission->id }}_{{ $managingPermissionsForRole->id }}">
                  <label class="form-check-label" for="perm_{{ $permission->id }}_{{ $managingPermissionsForRole->id }}">
                    {{ $permission->name }}
                  </label>
                </div>
                @endforeach
              </div>
              <button type="submit" class="btn btn-primary me-2"><i class="fas fa-save me-2"></i>Save Permissions</button>
              <button type="button" wire:click="cancelEditRolePermissions" class="btn btn-secondary"><i class="fas fa-times-circle me-2"></i>Cancel</button>
            </form>
            @else
            <form wire:submit.prevent="createRole" class="mb-3">
              <div class="mb-3">
                <label for="newRoleName" class="form-label">New Role Name:</label>
                <input type="text" id="newRoleName" class="form-control" wire:model.defer="newRoleName">
                @error('newRoleName') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Create Role</button>
            </form>
            <h5>All Roles:</h5>
            <ul class="list-group">
              @forelse ($roles as $role)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $role->name }}</strong>
                  <br>
                  <small class="text-muted">Permissions: {{ $role->permissions->pluck('name')->implode(', ') ?: 'No permissions' }}</small>
                </div>
                @php
                $isAdmin = Auth::check() && Auth::user()->hasRole('admin');
                @endphp
                <button wire:click="editRolePermissions({{ $role->id }})" class="btn btn-outline-secondary btn-sm" @if(!$isAdmin) disabled @endif>
                  <i class="fas fa-edit me-1"></i>Manage Permissions
                </button>
              </li>
              @empty
              <li class="list-group-item text-muted">No roles found.</li>
              @endforelse
            </ul>
            <div class="mt-3">
              {{ $roles->links() }}
            </div>
            @endif
          </div>
        </div>
      </div>
    </div> <!-- end row -->

    <!-- Create User Section -->
    <div class="row mt-4">
      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Create New User</h4>
          </div>
          <div class="card-body">
            @if (session()->has('message_user_create'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('message_user_create') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <form wire:submit.prevent="createUser">
              <div class="mb-3">
                <label for="newUserName" class="form-label">Name:</label>
                <input type="text" id="newUserName" class="form-control" wire:model.defer="newUserName">
                @error('newUserName') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="newUserEmail" class="form-label">Email:</label>
                <input type="email" id="newUserEmail" class="form-control" wire:model.defer="newUserEmail">
                @error('newUserEmail') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="newUserPassword" class="form-label">Password:</label>
                <input type="password" id="newUserPassword" class="form-control" wire:model.defer="newUserPassword">
                @error('newUserPassword') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3" wire:ignore>
                <label for="newUserRoles" class="form-label">Assign Roles:</label>
                <select id="newUserRoles" class="form-select" wire:model.defer="newUserRoles" multiple>
                  @if(isset($allRoles) && $allRoles->count() > 0)
                  @foreach ($allRoles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                  @else
                  <option value="" disabled>No roles available</option>
                  @endif
                </select>
                @error('newUserRoles') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Create User</button>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- end row for create user -->
  </div> <!-- end container-fluid -->
</div>
@script()
<script>
  $(document).ready(function(){
    function loadJavascript() {
      $('#newUserRoles').select2().on('change', function() {
        $wire.set('newUserRoles', $(this).val());
      });
    }
      loadJavascript();// Initialise au chargement de la page
      Livewire.hook('morphed',()=>{
       loadJavascript();// Réinitialise après un DOM morph (submit, update)
    })

  
  })
</script>
@endscript
