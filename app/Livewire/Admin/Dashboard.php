<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class Dashboard extends Component
{
  public function mount(){
    /*$role=Role::find(1);
    $role->givePermissionTo('admin');
    dump($role);*/
    //$adminRole = Role::create(['name' => 'admin']);
    //$adminRole = Role::create(['name' => 'consultation']);

    /*Permission::create(['name' => 'admin']);
    Permission::create(['name' => 'etudiants.index']);
    Permission::create(['name' => 'etudiants.pdf']);
    Permission::create(['name' => 'clients.create']);
    Permission::create(['name' => 'personnes.maj']);
    Permission::create(['name' => 'pers.index']);
    Permission::create(['name' => 'genres.index']);
    Permission::create(['name' => 'genres.create']);
    Permission::create(['name' => 'genres.edit']);*/
    /*$consnRole=Role::where('name','consultation')->first();
    $permissions = Permission::all();
    $adminRole->syncPermissions($permissions);
    $consnRole->givePermissionTo('admin','clients.create');*/
    //$user = User::find(1);
   // $user = Auth::user();
    //$user->assignRole(roles: ['consultation']);
    //dd($user);
  }  
  public function render()
    {
        return view('livewire.admin.dashboard')
        ->layout('components.layouts.app');
    }
}
