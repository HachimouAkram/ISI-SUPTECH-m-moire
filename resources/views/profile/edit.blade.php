<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
    <title>Profile - ISI Suptech</title>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Bouton Retour -->
        <div class="position-absolute top-0 start-0 m-3">
            <a href="{{ route('dashboard') }}" class="btn btn-light shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <!-- Profile Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                        <!-- Photo et nom -->
                        <div class="text-center mb-4">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                     alt="Photo de profil"
                                     class="rounded-circle mb-3"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <i class="fa fa-user-circle text-primary mb-3" style="font-size: 120px;"></i>
                            @endif
                            <h5>{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h5>
                            <p class="text-muted">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="mb-4 p-3 bg-white rounded shadow-sm">
                            <h5 class="mb-3">Informations personnelles</h5>
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <!-- Changer le mot de passe -->
                        <div class="mb-4 p-3 bg-white rounded shadow-sm">
                            <h5 class="mb-3">Changer le mot de passe</h5>
                            @include('profile.partials.update-password-form')
                        </div>

                        <!-- Supprimer le compte -->
                        <div class="mb-4 p-3 bg-white rounded shadow-sm">
                            <h5 class="mb-3 text-danger">Supprimer le compte</h5>

                            <div class="text-center mb-3">
                                <p class="text-muted">
                                    Une fois votre compte supprimé, toutes vos données et ressources seront définitivement supprimées.
                                </p>
                            </div>

                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="w-100 mt-3">
                                Supprimer mon compte
                            </x-danger-button>

                            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                                    @csrf
                                    @method('delete')

                                    <h5 class="text-danger mb-2">Confirmer la suppression</h5>
                                    <p class="text-muted mb-3">
                                        Entrez votre mot de passe pour confirmer la suppression permanente de votre compte.
                                    </p>

                                    <div class="mb-3">
                                        <x-input-label for="password" value="Mot de passe" class="sr-only" />
                                        <x-text-input
                                            id="password"
                                            name="password"
                                            type="password"
                                            class="mt-1 block w-100"
                                            placeholder="Mot de passe"
                                        />
                                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <x-secondary-button x-on:click="$dispatch('close')" class="me-2">
                                            Annuler
                                        </x-secondary-button>
                                        <x-danger-button>
                                            Supprimer
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Profile End -->

    </div>

    @include('sections.admin.script')

    <script>
        // Masquer le spinner après chargement
        window.addEventListener('load', function() {
            document.getElementById('spinner').classList.remove('show');
        });
    </script>

</body>
</html>
