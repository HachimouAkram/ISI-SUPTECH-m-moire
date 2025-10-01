<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body class="bg-dark text-white">

<div class="container-fluid py-5">
    <div class="bg-dark text-white rounded p-4 shadow-lg">

        <!-- Bouton retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-key me-2 text-success"></i> Permissions pour le rôle :
                <span class="text-info">{{ $role->name }}</span>
            </h5>
            <a href="{{ route('role_permissions.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Formulaire de permissions -->
        <form action="{{ route('role_permissions.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Liste sous forme de tableau sombre -->
            <div class="bg-secondary rounded p-3 shadow-sm">
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-3 mb-3">
                            <div class="form-check bg-dark rounded p-2 h-100">
                                <input class="form-check-input" type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="perm{{ $permission->id }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="perm{{ $permission->id }}">
                                    <i class="fas fa-lock me-1 text-info"></i> {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
                <a href="{{ route('role_permissions.index') }}" class="btn btn-secondary ms-2 px-4">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@include('sections.admin.footer')
@include('sections.admin.script')
</body>
</html>
