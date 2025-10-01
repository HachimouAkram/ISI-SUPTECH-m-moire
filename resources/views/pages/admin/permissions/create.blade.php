<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container py-5">
    <div class="bg-secondary rounded p-4 text-white shadow">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Ajouter une permission</h4>
            <a href="{{ route('parametre.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom de la permission</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-0" placeholder="Ex: gérer_utilisateurs" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Ajouter
                </button>
                <a href="{{ route('permissions.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@include('sections.admin.script')
</body>
</html>
