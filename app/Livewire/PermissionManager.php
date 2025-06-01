<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class PermissionManager extends Component
{
    use WithPagination;
	use WithoutUrlPagination;
	public $paginationTheme = 'bootstrap';

    public $newPermissionName = '';
    public $newRoleName = '';

    public $selectedRolePermissions = [];
    public $selectedUserRoles = [];

    public $managingPermissionsForRole = null;
    public $managingRolesForUser = null;

    // Properties for the new user form
    public $newUserName = '';
    public $newUserEmail = '';
    public $newUserPassword = '';
    public $newUserRoles = []; // For storing IDs of roles to assign

    // Define $allPermissions and $allRoles here to ensure they are available throughout the component
    public $allPermissions;
    public $allRoles;

    protected function rules() 
    {
        return [
            'newPermissionName' => 'required|string|min:3|unique:permissions,name',
            'newRoleName' => 'required|string|min:3|unique:roles,name',
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|string|email|max:255|unique:users,email',
            'newUserPassword' => 'required|string|min:8',
            'newUserRoles' => 'nullable|array', // Roles are optional, can be an empty array
            'newUserRoles.*' => 'exists:roles,id', // Each role ID must exist
        ];
    }

    public function mount()
    {
        $this->allPermissions = Permission::orderBy('name')->get();
        $this->allRoles = Role::orderBy('name')->get(); 
    }

    public function createPermission()
    {
        $this->validateOnly('newPermissionName');
        Permission::create(['name' => $this->newPermissionName, 'guard_name' => 'web']);
        $this->newPermissionName = '';
        session()->flash('message_permission', 'Permission created successfully.');
        $this->allPermissions = Permission::orderBy('name')->get(); // Refresh permissions list
    }

    public function createRole()
    {
        $this->validateOnly('newRoleName');
        Role::create(['name' => $this->newRoleName, 'guard_name' => 'web']);
        $this->newRoleName = '';
        session()->flash('message_role', 'Role created successfully.');
        $this->allRoles = Role::orderBy('name')->get(); // Refresh roles list
    }

    public function editRolePermissions(Role $role)
    {
        $this->managingPermissionsForRole = $role;
        $this->selectedRolePermissions = $role->permissions->pluck('id')->toArray();
    }

    public function updateRolePermissions()
    {
        if ($this->managingPermissionsForRole) {
            $permissions = Permission::whereIn('id', $this->selectedRolePermissions)->get();
            $this->managingPermissionsForRole->syncPermissions($permissions);
            session()->flash('message_role_permission', 'Role permissions updated successfully.');
            $this->cancelEditRolePermissions(); // Reset state
        }
    }
    
    public function cancelEditRolePermissions()
    {
        $this->managingPermissionsForRole = null;
        $this->selectedRolePermissions = [];
    }

    public function editUserRoles(User $user)
    {
        $this->managingRolesForUser = $user;
        $this->selectedUserRoles = $user->roles->pluck('id')->toArray();
    }

    public function updateUserRoles()
    {
        if ($this->managingRolesForUser) {
            $roles = Role::whereIn('id', $this->selectedUserRoles)->get();
            $this->managingRolesForUser->syncRoles($roles);
            session()->flash('message_user_role', 'User roles updated successfully.');
            $this->cancelEditUserRoles(); // Reset state
        }
    }
    
    public function cancelEditUserRoles()
    {
        $this->managingRolesForUser = null;
        $this->selectedUserRoles = [];
    }

    // Method to handle user creation
    public function createUser()
    {
        $this->validate([
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|string|email|max:255|unique:users,email',
            'newUserPassword' => 'required|string|min:8',
            'newUserRoles' => 'nullable|array',
            'newUserRoles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $this->newUserName,
            'email' => $this->newUserEmail,
            'password' => bcrypt($this->newUserPassword), // Hash the password
        ]);

        if (!empty($this->newUserRoles)) {
            $rolesToAssign = Role::whereIn('id', $this->newUserRoles)->get();
            if ($rolesToAssign->isNotEmpty()) {
                $user->syncRoles($rolesToAssign);
            }
        }

        session()->flash('message_user_create', 'User created successfully!');

        // Reset form fields
        $this->newUserName = '';
        $this->newUserEmail = '';
        $this->newUserPassword = '';
        $this->newUserRoles = [];
    }
    public function render()
    {
        $permissions = Permission::orderBy('name')->paginate(5, ['*'], 'permissionsPage');
        $roles = Role::with('permissions')->orderBy('name')->paginate(5, ['*'], 'rolesPage');
        $users = User::with('roles')->orderBy('name')->paginate(5, ['*'], 'usersPage');
        // $allPermissions and $allRoles are already loaded in mount() and kept up-to-date

        return view('livewire.permission-manager', [
            'permissions' => $permissions,
            'roles' => $roles,
            'users' => $users,
            // 'allPermissions' => $this->allPermissions, // Already available as public property
            // 'allRoles' => $this->allRoles,          // Already available as public property
        ])->layout('components.layouts.app'); // Assuming you want to use the main app layout
    }
} 
