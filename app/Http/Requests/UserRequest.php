<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation des champs pour la création d'un utilisateur.
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:admin,formateur,etudiant'],
            'telephone' => ['required', 'string', 'max:20'],
            'sexe' => ['required', 'in:Homme,Femme,Autre'],
            'date_naissance' => ['required', 'date'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Messages personnalisés en cas d'erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 100 caractères.',

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 100 caractères.',

            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',

            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle doit être admin, formateur ou étudiant.',

            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 20 caractères.',

            'sexe.required' => 'Le sexe est obligatoire.',
            'sexe.in' => 'Le sexe doit être Homme, Femme ou Autre.',

            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.date' => 'La date de naissance doit être une date valide.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
