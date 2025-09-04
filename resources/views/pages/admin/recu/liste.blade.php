<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        @include('sections.admin.barreRateral')

        <div class="content">
            @include('sections.admin.navbar')

            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h5 class="mb-4">💌 Mes Reçus</h5>

                            @if($recus->count() == 0)
                                <div class="alert alert-info">Aucun reçu trouvé.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table text-white table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Étudiant</th>
                                                <th>Formation</th>
                                                <th>Type de paiement</th>
                                                <th>Montant</th>
                                                <th>Mois payé</th>
                                                <th>Date émission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recus as $index => $recu)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $recu->paiement->inscription->user->nom }}</td>
                                                    <td>{{ $recu->paiement->inscription->classe->formation->type_formation ?? '-' }} {{ $recu->paiement->inscription->classe->niveau ?? '-' }} en {{ $recu->paiement->inscription->classe->formation->nom ?? '-' }}</td>
                                                    <td>{{ $recu->paiement->type_paiement }}</td>
                                                    <td>{{ number_format($recu->paiement->montant, 0, ',', ' ') }} FCFA</td>
                                                    <td>{{ $recu->mois_du_paiement }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($recu->date_emission)->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        {{ $recus->links('pagination::bootstrap-5') }} <!-- Pagination -->
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('sections.admin.footer')
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    @include('sections.admin.script')
</body>
</html>
