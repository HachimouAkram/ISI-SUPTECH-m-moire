<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
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

        <!-- Create Admin Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-user-shield me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h3>Créer un administrateur</h3>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.store') }}">
                            @csrf

                            <!-- Nom -->
                            <div class="form-floating mb-3">
                                <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required
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
                            <div class="form-floating mb-3">
                                <input id="telephone" type="tel" name="telephone" value="{{ old('telephone') }}" required
                                    class="form-control @error('telephone') is-invalid @enderror" placeholder="Téléphone">
                                <label for="telephone">Téléphone</label>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sexe -->
                            <div class="mb-3">
                                <select id="sexe" name="sexe" required class="form-select @error('sexe') is-invalid @enderror">
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
                                <input id="date_naissance" type="date" name="date_naissance"
                                    value="{{ old('date_naissance') }}" required
                                    class="form-control @error('date_naissance') is-invalid @enderror"
                                    placeholder="Date de naissance">
                                <label for="date_naissance">Date de naissance</label>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fonction -->
                            <div class="mb-3">
                                <select id="fonction" name="fonction" required class="form-select @error('fonction') is-invalid @enderror">
                                    <option value="" disabled {{ old('fonction') ? '' : 'selected' }}>Fonction</option>
                                    <option value="Directeur" {{ old('fonction') == 'Directeur' ? 'selected' : '' }}>Directeur</option>
                                    <option value="Secretaire" {{ old('fonction') == 'Secretaire' ? 'selected' : '' }}>Secrétaire</option>
                                    <option value="Tresorier" {{ old('fonction') == 'Tresorier' ? 'selected' : '' }}>Trésorier</option>
                                </select>
                                @error('fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Créer Admin</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Admin End -->
    </div>

    @include('sections.admin.script')
</body>

</html>
