<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
{
    /**
     * Autoriser cette requête (à adapter selon la logique métier).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour les paiements.
     */
    public function rules(): array
    {
        return [
            'montant' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'mode_paiement' => ['required', 'in:Espèces,Carte,Virement,Mobile'],
            'type_paiement' => ['required', 'in:Inscription,Mensualité'],
            'user_id' => ['required', 'exists:users,id'],
            'inscription_id' => ['required', 'exists:inscriptions,id'],
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur ou égal à 0.',

            'date.required' => 'La date de paiement est obligatoire.',
            'date.date' => 'La date de paiement doit être une date valide.',

            'mode_paiement.required' => 'Le mode de paiement est obligatoire.',
            'mode_paiement.in' => 'Le mode de paiement doit être "Espèces", "Carte", "Virement" ou "Mobile".',

            'type_paiement.required' => 'Le type de paiement est obligatoire.',
            'type_paiement.in' => 'Le type de paiement doit être "Inscription" ou "Mensualité".',

            'user_id.required' => 'L\'utilisateur est obligatoire.',
            'user_id.exists' => 'L\'utilisateur sélectionné est invalide.',

            'inscription_id.required' => 'L\'inscription est obligatoire.',
            'inscription_id.exists' => 'L\'inscription sélectionnée est invalide.',
        ];
    }
}
