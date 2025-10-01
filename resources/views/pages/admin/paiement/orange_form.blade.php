<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
    <title>Paiement - {{ $type }}</title>
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
            <a href="{{ route('paiement.choix') }}" class="btn btn-light shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <!-- Paiement Orange Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                        <div class="text-center mb-4">
                            <a href="{{ route('dashboard') }}">
                                <h3 class="text-primary"><i class="fa fa-book me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h4>Paiement Orange Money - {{ $type }}</h4>
                        </div>

                        {{-- Résumé --}}
                        <div class="mb-4">
                            <p><strong>Formation :</strong> {{ $formation->nom }}</p>
                            <p><strong>Niveau :</strong> {{ $formation->type_formation }}</p>
                            <p><strong>Montant Total:</strong> {{ number_format($montant, 2) }} FCFA</p>
                        </div>

                        <hr>

                        <h5 class="mb-3 text-center">Saisissez votre numéro de téléphone</h5>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('orange.create', ['type' => $type]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="phone" id="phone" class="form-control text-center"
                                    placeholder="Ex: 22177xxxxxxx" required minlength="8" maxlength="15">
                            </div>

                            <button type="submit" class="btn btn-warning w-100 py-3">
                                💳 Payer avec Orange Money
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Paiement Orange End -->

    </div>

    @include('sections.admin.script')
</body>

</html>
