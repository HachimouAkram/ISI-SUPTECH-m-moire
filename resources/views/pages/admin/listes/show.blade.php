{{-- resources/views/pages/admin/listes/show.blade.php --}}
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
                            <h5 class="mb-4"><i class="fas fa-users me-2"></i>Étudiants - {{ $classe->formation->type_formation }} {{ $classe->niveau }} en {{ $classe->formation->nom ?? '' }}</h5>

                            <div class="mb-3">
                                <p><strong>Programme actif :</strong> Année académique {{ $programme->annee_accademique }}</p>
                            </div>

                            @if($etudiants->isEmpty())
                                <div class="alert alert-info">
                                    Aucun étudiant inscrit dans cette classe pour ce programme académique.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table text-white table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($etudiants as $index => $etudiant)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $etudiant->user->nom ?? '-' }}</td>
                                                    <td>{{ $etudiant->user->prenom ?? '-' }}</td>
                                                    <td>{{ $etudiant->user->email ?? '-' }}</td>
                                                    <td>{{ $etudiant->user->telephone ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('listes.index') }}" class="btn btn-secondary">⬅ Retour à la liste des classes</a>
                                    @can('exporter_liste_classe_etudiant_pdf')
                                    <a href="{{ route('listes.exportPdf', [$classe->id, $programme->id]) }}" class="btn btn-danger">
                                        Export PDF
                                    </a>
                                    @endcan
                                    @can('exporter_liste_classe_etudiant_excel')
                                    <a href="{{ route('listes.exportExcel', [$classe->id, $programme->id]) }}" class="btn btn-success">
                                        Export Excel
                                    </a>
                                    @endcan
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
