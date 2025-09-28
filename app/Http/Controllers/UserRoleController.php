<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    // Afficher la liste des utilisateurs avec leurs rôles
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('pages.admin.user_roles.index', compact('users', 'roles'));
    }

    // Afficher le formulaire pour assigner un rôle
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('pages.admin.user_roles.edit', compact('user', 'roles'));
    }

    // Mettre à jour le rôle de l’utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->syncRoles($request->roles); // remplace tous les rôles actuels par les nouveaux
        return redirect()->route('user_roles.index')->with('success', 'Rôle(s) mis à jour avec succès');
    }
}
