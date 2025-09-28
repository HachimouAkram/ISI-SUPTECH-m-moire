<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
<div class="container-fluid position-relative d-flex p-0">

    <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @include('sections.admin.barreRateral')

    <div class="content">
        @include('sections.admin.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0"><i class="fas fa-user-shield me-2"></i>Modifier les rôles de {{ $user->nom }} {{ $user->prenom }}</h6>
                            <a href="{{ route('user_roles.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
                        </div>

                        <form action="{{ route('user_roles.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-3 mb-3">
                                        <div class="form-check bg-dark rounded p-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="roles[]"
                                                   value="{{ $role->name }}"
                                                   id="role{{ $role->id }}"
                                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                            <label class="form-check-label text-white" for="role{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                <a href="{{ route('user_roles.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @include('sections.admin.footer')
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

@include('sections.admin.script')
</body>
</html>
