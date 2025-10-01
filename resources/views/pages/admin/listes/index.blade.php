{{-- resources/views/pages/admin/listes/index.blade.php --}}
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
                            <h5 class="mb-4"><i class="fas fa-book me-2"></i>Liste des classes</h5>

                            @if(!$programme)
                                <div class="alert alert-warning">
                                    ⚠️ Aucun programme académique actif n'est disponible pour le moment.
                                    Vous ne pouvez pas consulter ou exporter de listes.
                                </div>
                            @else
                                <div class="mb-3">
                                    <p><strong>Programme actif :</strong> Année académique {{ $programme->annee_accademique }}</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table text-white table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Formation</th>
                                                <th>Niveau</th>
                                                <th>Prix inscription</th>
                                                <th>Prix mensuel</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classes as $classe)
                                                <tr>
                                                    <td>{{ $classe->id }}</td>
                                                    <td>{{ $classe->formation->type_formation }} {{ $classe->formation->nom }}</td>
                                                    <td>{{ $classe->niveau }}</td>
                                                    <td>{{ number_format($classe->prix_inscription, 0, ',', ' ') }} FCFA</td>
                                                    <td>{{ number_format($classe->prix_mensuel, 0, ',', ' ') }} FCFA</td>
                                                    <td>
                                                        <a href="{{ route('listes.show', [$classe->id, $programme->id]) }}" class="btn btn-primary btn-sm">
                                                            Voir la liste
                                                        </a>
                                                        @can('exporter_liste_classe_etudiant_pdf')
                                                        <a href="{{ route('listes.exportPdf', [$classe->id, $programme->id]) }}" class="btn btn-danger btn-sm">
                                                            PDF
                                                        </a>
                                                        @endcan
                                                        @can('exporter_liste_classe_etudiant_excel')
                                                        <a href="{{ route('listes.exportExcel', [$classe->id, $programme->id]) }}" class="btn btn-success btn-sm">
                                                            Excel
                                                        </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
