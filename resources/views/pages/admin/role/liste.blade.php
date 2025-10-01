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

        <!-- Titre centré -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-uppercase">
                <i class="fas fa-user-shield text-primary me-2"></i> Gestion des Rôles
            </h1>
            <p class="text-muted">Liste et gestion des rôles disponibles dans le système</p>
        </div>

        <!-- Bloc principal -->
        <div class="bg-secondary rounded p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i> Rôles enregistrés
                </h5>
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter
                </button>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-striped table-hover text-white align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td class="text-center">
                                <!-- Permissions -->
                                <a href="{{ route('roles.permissions.edit', $role->id) }}"
                                class="btn btn-sm btn-info me-1"
                                title="Gérer les permissions">
                                    <i class="fas fa-key"></i>
                                </a>

                                <!-- Modifier Modal Trigger -->
                                <button class="btn btn-sm btn-warning me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRoleModal{{ $role->id }}"
                                        title="Modifier le rôle">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Supprimer -->
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce rôle ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Supprimer le rôle">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Édition -->
                        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier le rôle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <input type="text" name="nom" class="form-control" value="{{ $role->name }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucun rôle trouvé</td>
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

<!-- Modal Ajout -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" name="nom" class="form-control" placeholder="Nom du rôle" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('sections.admin.script')
</body>
</html>
