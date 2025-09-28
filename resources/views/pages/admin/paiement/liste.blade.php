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
                        <h5 class="mb-4"><i class="fas fa-money-bill-wave me-2"></i>Tous les Paiements</h5>

                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <select name="classe_id" class="form-select">
                                    <option value="">-- Classe --</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" @selected(request('classe_id') == $classe->id)>
                                            {{ $classe->formation->nom ?? '' }} - Niveau {{ $classe->niveau }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="annee_accademique" class="form-select">
                                    <option value="">-- Année académique --</option>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee }}" @selected(request('annee_accademique') == $annee)>{{ $annee }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="nom_prenom" class="form-control" placeholder="Nom ou prénom" value="{{ request('nom_prenom') }}">
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>

                        @if($paiements->count() == 0)
                            <div class="alert alert-info">Aucun paiement trouvé.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table text-white table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Étudiant</th>
                                            <th>Classe / Formation</th>
                                            <th>Année académique</th>
                                            <th>Type de paiement</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paiements as $index => $paiement)
                                            <tr>
                                                <td>{{ $index + 1 + ($paiements->currentPage()-1) * $paiements->perPage() }}</td>
                                                <td>{{ $paiement->inscription->user->nom }} {{ $paiement->inscription->user->prenom }}</td>
                                                <td>{{ $paiement->inscription->classe->formation->type_formation ?? '-' }} {{ $paiement->inscription->classe->niveau ?? '-' }} en {{ $paiement->inscription->classe->formation->nom ?? '-' }} </td>
                                                <td>{{ $paiement->inscription->programmeAccademique->annee_accademique ?? '-' }}</td>
                                                <td>{{ $paiement->type_paiement }}</td>
                                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                                <td>{{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    {{ $paiements->appends(request()->all())->links('pagination::bootstrap-5') }}
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
