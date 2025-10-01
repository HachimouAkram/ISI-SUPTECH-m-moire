<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body class="bg-dark text-white">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Permissions pour le rôle : {{ $role->name }}</h4>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
    </div>

    <div class="card bg-secondary shadow rounded">
        <div class="card-body">
            <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-3 mb-3">
                            <div class="form-check bg-dark rounded p-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       id="perm{{ $permission->id }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="perm{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@include('sections.admin.script')
</body>
</html>
