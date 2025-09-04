<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Autorisation de la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation des champs du document.
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'chemin_fichier' => ['required', 'string', 'max:255'],
            'inscription_id' => ['required', 'exists:inscriptions,id'],
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du document est obligatoire.',
            'nom.string' => 'Le nom du document doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne doit pas dépasser 255 caractères.',

            'chemin_fichier.required' => 'Le chemin du fichier est obligatoire.',
            'chemin_fichier.string' => 'Le chemin du fichier doit être une chaîne de caractères.',
            'chemin_fichier.max' => 'Le chemin du fichier ne doit pas dépasser 255 caractères.',

            'inscription_id.required' => 'L\'inscription associée est obligatoire.',
            'inscription_id.exists' => 'L\'inscription sélectionnée est invalide.',
        ];
    }
}
