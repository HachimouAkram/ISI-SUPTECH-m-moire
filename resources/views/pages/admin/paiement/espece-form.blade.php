@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
        Paiement en espèces pour {{ $user->nom }} {{ $user->prenom }}
    </h2>

    <form action="{{ route('paiement.espece.store', $user->id) }}" method="POST">
        @csrf

        @if(!$inscriptionPayee)
            <div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded-xl">
                ⚠️ L'inscription n'est pas encore payée. Ce paiement inclura les frais d'inscription.
            </div>
        @endif

        <div class="mb-4">
            <label for="nb_mois" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                Nombre de mois à payer
            </label>
            <input type="number" name="nb_mois" id="nb_mois"
                   class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                   placeholder="Ex: 2">
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl">
            Enregistrer le paiement
        </button>
    </form>
</div>
@endsection
