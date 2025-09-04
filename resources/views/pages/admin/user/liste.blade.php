<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

<body>
<div class="container-fluid position-relative d-flex p-0">
    <!-- Spinner -->
    <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @include('sections.admin.barreRateral')

    <!-- Content Start -->
    <div class="content">
        @include('sections.admin.navbar')

        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-12">
                    <div class="bg-secondary rounded h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0">Liste des Étudiants</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <form method="GET" action="{{ route('users.index') }}">
                                    <label for="per_page" class="text-white">Afficher</label>
                                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto ms-2">
                                        @foreach([5, 10, 25, 50, 100] as $size)
                                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-white ms-2">éléments</span>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-white table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Étudiant</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->nom }} {{ $user->prenom }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->telephone }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $user->id }}"
                                                    title="Voir le profil">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Voir Profil -->
                                    <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Profil de {{ $user->nom }} {{ $user->prenom }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Nom complet</dt>
                                                        <dd class="col-sm-8">{{ $user->nom }} {{ $user->prenom }}</dd>

                                                        <dt class="col-sm-4">Email</dt>
                                                        <dd class="col-sm-8">{{ $user->email }}</dd>

                                                        <dt class="col-sm-4">Téléphone</dt>
                                                        <dd class="col-sm-8">{{ $user->telephone ?? 'Non renseigné' }}</dd>

                                                        <dt class="col-sm-4">Date de naissance</dt>
                                                        <dd class="col-sm-8">{{ $user->date_naissance ?? 'Non renseignée' }}</dd>

                                                        <dt class="col-sm-4">Adresse</dt>
                                                        <dd class="col-sm-8">{{ $user->adresse ?? 'Non renseignée' }}</dd>

                                                        <dt class="col-sm-4">Rôle</dt>
                                                        <dd class="col-sm-8">{{ ucfirst($user->role) }}</dd>
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $etudiants->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('sections.admin.footer')
    </div>
    <!-- Content End -->

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

@include('sections.admin.script')
</body>
</html>
