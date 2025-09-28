<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container-fluid p-0">
        <div class="bg-dark text-white rounded p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('dashboard') }}">
                    <button class="btn btn-secondary btn-sm">← Retour</button>
                </a>
            </div>

            <!-- Titre centré avec icône -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-uppercase">
                    <i class="fas fa-cogs text-warning me-2"></i> Paramètres
                </h1>
                <p class="text-muted">Gestion des rôles, permissions et affectations</p>
            </div>

            <!-- Boutons d'action -->
            <div class="row g-4">
                @if(Auth::user()->fonction === 'Directeur')
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-light w-100 text-start p-4 rounded shadow-sm">
                            <i class="fas fa-user-shield fa-2x text-primary me-3"></i>
                            <div>
                                <strong>Gestion des rôles</strong><br>
                                <small>Créer, modifier ou supprimer des rôles</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('user_roles.index') }}" class="btn btn-outline-light w-100 text-start p-4 rounded shadow-sm">
                            <i class="fas fa-user-tag fa-2x text-success me-3"></i>
                            <div>
                                <strong>Affecter des rôles</strong><br>
                                <small>Attribuer un rôle à un utilisateur</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('role_permissions.index') }}" class="btn btn-outline-light w-100 text-start p-4 rounded shadow-sm">
                            <i class="fas fa-key fa-2x text-warning me-3"></i>
                            <div>
                                <strong>Permissions par rôle</strong><br>
                                <small>Voir ou modifier les permissions d’un rôle</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-light w-100 text-start p-4 rounded shadow-sm">
                            <i class="fas fa-lock fa-2x text-danger me-3"></i>
                            <div>
                                <strong>Gestion des permissions</strong><br>
                                <small>Créer, modifier ou supprimer des permissions</small>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
</div>

@include('sections.admin.script')
</body>
</html>
