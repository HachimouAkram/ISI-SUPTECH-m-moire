{{-- resources/views/pages/admin/programmeAccademique/index.blade.php --}}
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
                        <h5 class="mb-4"><i class="fas fa-book me-2"></i>Programmes Académiques</h5>

                        <a href="{{ route('programme.create') }}" class="btn btn-primary mb-3">➕ Ajouter un programme</a>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table text-white table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Année académique</th>
                                    <th>Ouverture inscription</th>
                                    <th>Fermeture inscription</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($programmes as $index => $programme)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $programme->annee_accademique }}</td>
                                        <td>{{ \Carbon\Carbon::parse($programme->date_ouverture_inscription)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($programme->date_fermeture_inscription)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($programme->etat)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('programme.edit', $programme->id) }}" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                            <a href="{{ route('programme.toggle', $programme->id) }}" class="btn btn-info btn-sm">
                                                {{ $programme->etat ? 'Désactiver' : 'Activer' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

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
