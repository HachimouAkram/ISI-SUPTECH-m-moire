<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
    <title>Confirmation Paiement</title>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Bouton Retour -->
        <div class="position-absolute top-0 start-0 m-3">
            <a href="{{ route('dashboard') }}" class="btn btn-light shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-md-8 col-lg-6">
                    <div class="bg-secondary rounded p-5 text-center">

                        <h3 class="text-primary mb-4"><i class="fa fa-check-circle me-2"></i>Paiement en cours</h3>
                        <p>Votre paiement Orange Money a été initié avec la référence :</p>
                        <h5 class="fw-bold">{{ $reference }}</h5>
                        <p class="mt-3">Il sera confirmé dès réception du retour d’Orange Money.</p>

                        <a href="{{ route('dashboard') }}" class="btn btn-success mt-4">Retour au Dashboard</a>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('sections.admin.script')
</body>

</html>
