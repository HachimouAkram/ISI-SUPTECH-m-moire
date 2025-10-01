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
                <i class="fas fa-users text-info me-2"></i> Utilisateurs et Rôles
            </h1>
            <p class="text-muted">Liste des utilisateurs et leurs rôles dans le système</p>
        </div>

        <!-- Bloc principal -->
        <div class="bg-secondary rounded p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i> Utilisateurs enregistrés
                </h5>
            </div>

            <!-- Tableau des utilisateurs -->
            <div class="table-responsive">
                <table class="table table-striped table-hover text-white align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->nom }} {{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a href="{{ route('user_roles.edit', $user->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun utilisateur trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
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
