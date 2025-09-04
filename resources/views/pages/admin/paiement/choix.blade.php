<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
    <title>Choisir le type de paiement</title>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Bouton Retour vers le dashboard -->
        <div class="position-absolute top-0 start-0 m-3">
            <a href="{{ route('dashboard') }}" class="btn btn-light shadow-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <!-- Choix du paiement Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3 text-center">
                        <h3 class="text-primary mb-4">
                            <i class="fa fa-credit-card me-2"></i>Choisir de mode paiement
                        </h3>

                        <div class="row g-4">
                        @if(!$inscriptionPayee)
                            <!-- Boutons pour payer l'inscription -->
                            <div class="col-md-6 offset-md-3">
                                <a href="{{ route('paiement.page', ['type' => 'Inscription']) }}"
                                class="btn btn-success w-100 py-3 mb-2">
                                    💳 Payer votre Inscription avec PayPal
                                </a>
                                <a href="{{ route('stripe.create', ['type' => 'Inscription']) }}"
                                class="btn btn-primary w-100 py-3">
                                    💳 Payer votre Inscription Avec Strip
                                </a>
                            </div>
                        @else
                            <!-- Boutons pour payer la mensualité -->
                            <div class="col-md-6 offset-md-3">
                                <a href="{{ route('paiement.page', ['type' => 'Mensualité']) }}"
                                class="btn btn-success w-100 py-3 mb-2">
                                    💳 Payer vos Mensualités avec PayPal
                                </a>
                                <a href="{{ route('stripe.create', ['type' => 'Mensualité']) }}"
                                class="btn btn-primary w-100 py-3">
                                    💳 Payer vos Mensualité avec Stripe
                                </a>
                            </div>
                        @endif
                    </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- Choix du paiement End -->

    </div>

    @include('sections.admin.script')
</body>

</html>
