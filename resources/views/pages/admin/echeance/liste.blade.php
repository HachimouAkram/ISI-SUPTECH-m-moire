<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Chargement...</span>
            </div>
        </div>

        @include('sections.admin.barreRateral')

        <!-- Content Start -->
        <div class="content">
            @include('sections.admin.navbar')

            <!-- Échéances Table -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="alert alert-warning text-center">
                                    💰 Montant total restant à payer :
                                    <strong>{{ number_format($montant_restant, 0, ',', ' ') }} FCFA</strong>
                                </div>
                                @can('paiement_en_ligne')
                                @if (!empty($mois_echeances) && $montant_restant > 0)
                                    <a href="{{ route('paiement.choix') }}"
                                    class="btn btn-success btn-sm shadow-sm d-flex align-items-center justify-content-center"
                                    style="gap: 5px;">
                                        <i class="fa fa-credit-card"></i> Payer
                                    </a>
                                @endif
                                @endcan
                            </div>
                            @if(isset($message))
                                <div class="alert alert-info">{{ $message }}</div>
                            @elseif(count($mois_echeances) > 0)
                                <div class="table-responsive">
                                    <table class="table text-white">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date Échéance</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mois_echeances as $index => $mois)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>5 {{ ucfirst($mois) }}</td>
                                                    <td>{{ number_format($mensualite, 0, ',', ' ') }} FCFA</td>
                                                    <td>
                                                        <span class="badge bg-warning text-dark">Non payé</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    Toutes les mensualités ont été payées 🎉
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('sections.admin.footer')
        </div>
        <!-- Content End -->

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    @include('sections.admin.script')
</body>
</html>
