<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Début du Spinner de chargement -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Chargement...</span>
            </div>
        </div>
        <!-- Fin du Spinner -->

        <!-- Début du formulaire de réinitialisation du mot de passe -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <!-- En-tête avec logo et titre -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>ISI Suptech</h3>
                            </a>
                            <h3>Réinitialiser le mot de passe</h3>
                        </div>

                        <!-- Formulaire -->
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Jeton de sécurité (token) pour valider la réinitialisation -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Champ : Adresse email -->
                            <div class="form-floating mb-3">
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $request->email) }}"
                                    required
                                    autofocus
                                    placeholder="exemple@email.com">
                                <label for="email">Adresse email</label>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Champ : Nouveau mot de passe -->
                            <div class="form-floating mb-3">
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Nouveau mot de passe">
                                <label for="password">Nouveau mot de passe</label>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Champ : Confirmation du mot de passe -->
                            <div class="form-floating mb-4">
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Confirmer le mot de passe">
                                <label for="password_confirmation">Confirmer le mot de passe</label>
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Bouton de soumission -->
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Réinitialiser</button>

                            <!-- Lien retour connexion -->
                            <p class="text-center mb-0">
                                <a href="{{ route('login') }}">Retour à la connexion</a>
                            </p>
                        </form>
                        <!-- Fin du formulaire -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin du formulaire de réinitialisation -->
    </div>

    <!-- Scripts JavaScript globaux -->
    @include('sections.admin.script')
</body>

</html>
