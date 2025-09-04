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
                <span class="sr-only">Chargement...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Create Programme Académique Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-book me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h3>Créer un Programme Académique</h3>
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

                        <form method="POST" action="{{ route('programme_academique.store') }}">
                            @csrf

                            <!-- Année Académique -->
                            <div class="form-floating mb-3">
                                <input type="text" name="annee_accademique" id="annee_accademique"
                                    value="{{ old('annee_accademique') }}" required
                                    class="form-control @error('annee_accademique') is-invalid @enderror"
                                    placeholder="2025-2026">
                                <label for="annee_accademique">Année Académique</label>
                                @error('annee_accademique')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date d'ouverture -->
                            <div class="form-floating mb-3">
                                <input type="date" name="date_ouverture_inscription" id="date_ouverture_inscription"
                                    value="{{ old('date_ouverture_inscription') }}" required
                                    class="form-control @error('date_ouverture_inscription') is-invalid @enderror"
                                    placeholder="Date d'ouverture">
                                <label for="date_ouverture_inscription">Date d'ouverture</label>
                                @error('date_ouverture_inscription')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date de fermeture -->
                            <div class="form-floating mb-3">
                                <input type="date" name="date_fermeture_inscription" id="date_fermeture_inscription"
                                    value="{{ old('date_fermeture_inscription') }}" required
                                    class="form-control @error('date_fermeture_inscription') is-invalid @enderror"
                                    placeholder="Date de fermeture">
                                <label for="date_fermeture_inscription">Date de fermeture</label>
                                @error('date_fermeture_inscription')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- État -->
                            <input type="hidden" name="etat" value="0">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="etat" id="etat" value="1"
                                    {{ old('etat') ? 'checked' : '' }}>
                                <label class="form-check-label" for="etat">Actif</label>
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Créer Programme</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Programme Académique End -->
    </div>

    @include('sections.admin.script')
</body>

</html>
