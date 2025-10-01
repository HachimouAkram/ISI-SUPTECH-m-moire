<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container-fluid p-0">
    <div class="bg-dark text-white rounded p-4">
        <!-- Bouton retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('parametre.index') }}">
                <button class="btn btn-secondary btn-sm">← Retour</button>
            </a>
        </div>

        <!-- Titre centré avec icône -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-uppercase">
                <i class="fas fa-user-shield text-success me-2"></i> Rôles et Permissions
            </h1>
            <p class="text-muted">Gestion des rôles et des permissions associés</p>
        </div>

        <!-- Bloc principal -->
        <div class="bg-secondary rounded p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i> Rôles enregistrés
                </h5>
            </div>

            <!-- Tableau des rôles et permissions -->
            <div class="table-responsive">
                <table class="table table-striped table-hover text-white align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Rôle</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $perm)
                                    <span class="badge bg-info">{{ $perm->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a href="{{ route('role_permissions.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun rôle trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $roles->links('pagination::bootstrap-5') }}
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
