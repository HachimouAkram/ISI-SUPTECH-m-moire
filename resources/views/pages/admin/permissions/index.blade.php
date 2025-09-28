<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container-fluid p-0">
    <div class="bg-dark text-white rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('parametre.index') }}">
                <button class="btn btn-secondary btn-sm">← Retour</button>
            </a>
        </div>
        <!-- Titre centré avec icône -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-uppercase">
                <i class="fas fa-lock text-warning me-2"></i> Toutes les Permissions
            </h1>
            <p class="text-muted">Liste des permissions disponibles dans le système</p>
        </div>

        <!-- Bloc principal -->
        <div class="bg-secondary rounded p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i> Permissions enregistrées
                </h5>
                <a href="{{ route('permissions.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter
                </a>
            </div>

            <!-- Tableau des permissions -->
            <div class="table-responsive">
                <table class="table table-striped table-hover text-white align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom de la permission</th>
                            <th>Guard</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette permission ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune permission trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $permissions->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
    <!-- Bouton retour haut -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

</div>

@include('sections.admin.script')
</body>
</html>
