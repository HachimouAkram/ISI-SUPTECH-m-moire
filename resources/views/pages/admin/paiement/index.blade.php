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
        <!-- Paiement Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                        <div class="text-center mb-4">
                            <a href="{{ route('dashboard') }}">
                                <h3 class="text-primary"><i class="fa fa-book me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h4>Paiement - {{ $type }}</h4>
                        </div>

                        {{-- Résumé --}}
                        <div class="mb-4">
                            <p><strong>Formation :</strong> {{ $formation->nom }}</p>
                            <p><strong>Niveau :</strong> {{ $formation->type_formation }}</p>
                            <p><strong>Montant Total:</strong> {{ number_format($montant, 2) }} FCFA</p>
                        </div>

                        <hr>

                        <h5 class="mb-3 text-center">Choisissez votre mode de paiement</h5>

                        <div class="text-center">
                            <div id="paypal-button-container"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Paiement End -->

    </div>

    @include('sections.admin.script')

    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}&currency=USD"></script>
    <script>
    paypal.Buttons({
        style: { layout: 'vertical', color: 'blue', shape: 'rect', label: 'pay' },

        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '{{ number_format($montant / 550, 2) }}' // Conversion FCFA to USD
                    },
                    description: "Paiement {{ $type }} - {{ $formation->nom }}"
                }],
                application_context: {
                    landing_page: "BILLING",
                    user_action: "PAY_NOW",
                    brand_name: "{{ config('app.name') }}"
                    // return_url supprimé pour AJAX
                    // cancel_url peut rester si tu veux gérer l'annulation
                }
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Envoi au serveur pour enregistrer le paiement
                fetch("{{ route('paypal.success') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        type: '{{ $type }}',
                        inscription_id: '{{ $inscription->id }}'
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if(res.success){
                        // Redirection après succès
                        window.location.href = "{{ route('paiement.choix') }}";
                    } else {
                        alert('Erreur lors de l’enregistrement du paiement');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de la communication avec le serveur');
                });
            });
        },

        onCancel: function(data) {
            window.location.href = "{{ route('paypal.cancel') }}";
        },

        onError: function(err) {
            console.error(err);
            alert('Erreur lors du paiement.');
        }

    }).render('#paypal-button-container');
    </script>

</body>

</html>
