<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInscriptionRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // À adapter selon votre logique d'autorisation
    }

    /**
     * Règles de validation à appliquer à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'statut' => ['required', 'in:Encours,Valider,Terminer'],
            'classe_id' => ['required', 'exists:classes,id'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.required' => 'La date d\'inscription est obligatoire.',
            'date.date' => 'La date d\'inscription doit être une date valide.',

            'statut.required' => 'Le statut de l\'inscription est obligatoire.',
            'statut.in' => 'Le statut doit être soit "Encours", "Valider" ou "Terminer".',

            'classe_id.required' => 'La classe est obligatoire.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',

            'user_id.required' => 'L\'utilisateur est obligatoire.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
        ];
    }
}
