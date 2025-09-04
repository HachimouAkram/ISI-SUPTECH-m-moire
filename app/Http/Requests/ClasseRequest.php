<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClasseRequest extends FormRequest
{
    /**
     * Autorise ou non l'utilisateur à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation des champs du formulaire.
     */
    public function rules(): array
    {
        return [
            'prix_inscription' => ['required', 'numeric', 'min:0'],
            'prix_mensuel' => ['required', 'numeric', 'min:0'],
            'duree' => ['required', 'integer', 'min:1'],
            'niveau' => ['required', 'integer', 'min:1'],
            'formation_id' => ['required', 'exists:formations,id'],
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'prix_inscription.required' => 'Le prix d\'inscription est obligatoire.',
            'prix_inscription.numeric' => 'Le prix d\'inscription doit être un nombre.',
            'prix_inscription.min' => 'Le prix d\'inscription doit être supérieur ou égal à 0.',

            'prix_mensuel.required' => 'Le prix mensuel est obligatoire.',
            'prix_mensuel.numeric' => 'Le prix mensuel doit être un nombre.',
            'prix_mensuel.min' => 'Le prix mensuel doit être supérieur ou égal à 0.',

            'duree.required' => 'La durée est obligatoire.',
            'duree.integer' => 'La durée doit être un nombre entier.',
            'duree.min' => 'La durée doit être d\'au moins 1 mois.',

            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.integer' => 'Le niveau doit être un nombre entier.',
            'niveau.min' => 'Le niveau doit être d\'au moins 1.',

            'formation_id.required' => 'La formation est obligatoire.',
            'formation_id.exists' => 'La formation sélectionnée est invalide.',
        ];
    }
}
