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
            <div class="bg-secondary rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Permissions pour le rôle : {{ $role->name }}</h5>
                    <a href="{{ route('role_permissions.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
                </div>

                <form action="{{ route('role_permissions.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-3 mb-3">
                                <div class="form-check bg-dark rounded p-2">
                                    <input class="form-check-input" type="checkbox"
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

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <a href="{{ route('role_permissions.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
        @include('sections.admin.footer')
    </div>
</div>
@include('sections.admin.script')
</body>
</html>
