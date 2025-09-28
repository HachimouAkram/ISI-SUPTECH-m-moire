<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Liste des rôles
    public function index()
    {
        $roles = Role::paginate(10);
        return view('pages.admin.role.liste', compact('roles'));
    }

    // Créer un rôle
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->nom]);
        return back()->with('success', 'Rôle créé');
    }

    // Modifier un rôle
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        $role->name = $request->nom;
        $role->save();
        return back()->with('success', 'Rôle modifié');
    }

    // Supprimer un rôle
    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();
        return back()->with('success', 'Rôle supprimé');
    }

    // Afficher la page d’assignation des permissions
    public function editPermissions($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('pages.admin.role.permissions', compact('role', 'permissions'));
    }

    // Mettre à jour les permissions d’un rôle
    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findById($id);
        $role->syncPermissions($request->permissions); // Sync toutes les permissions
        return back()->with('success', 'Permissions mises à jour');
    }
}
