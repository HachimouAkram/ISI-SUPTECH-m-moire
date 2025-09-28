<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container py-5">
    <div class="bg-secondary rounded p-4 text-white">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('parametre.index') }}">
                <button class="btn btn-secondary btn-sm">← Retour</button>
            </a>
        </div>
        <h4>Ajouter une permission</h4>

        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom de la permission</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

@include('sections.admin.script')
</body>
</html>
