<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormationRequest extends FormRequest
{
    /**
     * Autoriser ou non cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation du formulaire de création de formation.
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'duree' => ['required', 'in:3 ans,2 ans'],
            'type_formation' => ['required', 'in:Master,Licence,BTS'],
            'domaine' => ['required', 'in:Genie informatique,Réseau informatique,Gestion'],
        ];
    }

    /**
     * Messages d’erreur personnalisés pour la validation.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la formation est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 150 caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',

            'duree.required' => 'La durée de la formation est obligatoire.',
            'duree.in' => 'La durée doit être soit "3 ans" soit "2 ans".',

            'type_formation.required' => 'Le type de formation est obligatoire.',
            'type_formation.in' => 'Le type de formation doit être "Master", "Licence" ou "BTS".',

            'domaine.required' => 'Le domaine de la formation est obligatoire.',
            'domaine.in' => 'Le domaine doit être "Genie informatique", "Réseau informatique" ou "Gestion".',
        ];
    }
}
