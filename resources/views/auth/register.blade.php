<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sign Up Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}" class="">
                                <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h3>Insciptions</h3>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Nom -->
                            <div class="form-floating mb-3">
                                <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required autofocus
                                    class="form-control @error('nom') is-invalid @enderror" placeholder="Nom">
                                <label for="nom">Nom</label>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="form-floating mb-3">
                                <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                                    class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom">
                                <label for="prenom">Prénom</label>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                <label for="email">Adresse Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <!-- Téléphone -->
                            <div class="form-floating mb-3">
                                <input id="telephone" type="tel" name="telephone"
                                    value="{{ old('telephone') }}" required
                                    class="form-control" placeholder="Téléphone">
                                <label for="telephone">Téléphone</label>
                            </div>

                            <!-- Sexe -->
                            <div class="mb-3">
                                <select id="sexe" name="sexe" required
                                    class="form-select @error('sexe') is-invalid @enderror">
                                    <option value="" disabled {{ old('sexe') ? '' : 'selected' }}>Sexe</option>
                                    <option value="Homme" {{ old('sexe') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="Femme" {{ old('sexe') == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    <option value="Autre" {{ old('sexe') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('sexe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de naissance -->
                            <div class="form-floating mb-3">
                                <input id="date_naissance" type="date" name="date_naissance" value="{{ old('date_naissance') }}" required
                                    class="form-control @error('date_naissance') is-invalid @enderror" placeholder="Date de naissance">
                                <label for="date_naissance">Date de naissance</label>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input id="password" type="password" name="password" required
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe">
                                <label for="password">Mot de passe</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-floating mb-4">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    class="form-control" placeholder="Confirmer le mot de passe">
                                <label for="password_confirmation">Confirmer le mot de passe</label>
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">S'inscrire</button>

                            <p class="text-center mb-0 text-white">
                                Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>

    <!-- JavaScript Libraries -->
    @include('sections.admin.script')
</body>

</html>
