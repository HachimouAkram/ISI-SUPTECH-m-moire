<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Liste des rôles avec leurs permissions
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('pages.admin.role_permissions.index', compact('roles'));
    }

    // Formulaire pour éditer les permissions d’un rôle
    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('pages.admin.role_permissions.edit', compact('role', 'permissions'));
    }

    // Mettre à jour les permissions
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        $role->syncPermissions($request->permissions ?? []); // Sync toutes les permissions
        return back()->with('success', 'Permissions mises à jour pour le rôle ' . $role->name);
    }
}
