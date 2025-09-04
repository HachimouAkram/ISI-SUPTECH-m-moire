<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>
@if(Auth::user()->role === 'admin')
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

                <!-- Formations Table -->
                <div class="container-fluid pt-4 px-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="bg-secondary rounded h-100 p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="mb-0">Liste des Formations</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ Ajouter</button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="{{ route('formations.index') }}">
                                            <label for="per_page" class="text-white">Afficher</label>
                                            <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto ms-2">
                                                @foreach([5,10, 25, 50, 100] as $size)
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
                                                <th>Formation</th>
                                                <th>Niveau</th>
                                                <th>Durée</th>
                                                <th>Département</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($formations as $formation)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    @php
                                                        $formationNom = $formation->nom;
                                                        $motsIgnorés = ['de', 'des', 'du', 'en', 'à', 'aux', 'et', 'a', 'la', 'le', 'l', 'd'];
                                                        $acronyme = collect(explode(' ', $formationNom))
                                                            ->filter(fn($mot) => !in_array(strtolower($mot), $motsIgnorés))
                                                            ->map(fn($mot) => strtoupper(substr($mot, 0, 1)))
                                                            ->implode('.') . '.';
                                                    @endphp

                                                    <td title="{{ $formationNom }}">
                                                        {{ $acronyme }}
                                                    </td>
                                                    <td>{{ $formation->type_formation }}</td>
                                                    <td>{{ $formation->duree }}</td>
                                                    <td>{{ $formation->domaine }}</td>
                                                    <td class="text-center d-flex justify-content-center gap-2">
                                                        <!-- Bouton Modifier -->
                                                        <button class="btn btn-sm btn-outline-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $formation->id }}"
                                                                title="Modifier">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <!-- Bouton Supprimer -->
                                                        <form method="POST" action="{{ route('formations.destroy', $formation) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Supprimer cette formation ?')"
                                                                    title="Supprimer">
                                                                <i class="bi bi-trash3"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <!-- Modal Modifier -->
                                                <div class="modal fade" id="editModal{{ $formation->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="POST" action="{{ route('formations.update', $formation) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-content bg-dark text-white">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Modifier Formation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @include('pages.admin.formation.form', ['formation' => $formation])
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-primary">Valider</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    <div class="mt-3">
                                        {{ $formations->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Ajouter -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('formations.store') }}">
                            @csrf
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ajouter Formation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    @include('pages.admin.formation.form', ['formation' => null])
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Valider</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @include('sections.admin.footer')
            </div>
            <!-- Content End -->

            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        </div>

        @include('sections.admin.script')
    </body>
@else
    <body>
        <div class="container-fluid position-relative d-flex p-0">
            <!-- Spinner -->
            <div id="spinner"
                class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            @include('sections.admin.barreRateral')

            <!-- Content Start -->
            <div class="content">
                @include('sections.admin.navbar')

                <!-- Classes Table -->
                <div class="container-fluid pt-4 px-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="bg-secondary rounded h-100 p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="mb-0">Liste des Formation</h6>
                                    <a href="{{ route('inscriptions.create') }}" class="btn btn-primary me-2">
                                      <i class="fas fa-plus-circle me-2 text-success"></i>Demande d'admission
                                    </a>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <form method="GET" action="{{ route('classes.index') }}">
                                        <label for="per_page" class="text-white">Afficher</label>
                                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                                                class="form-select form-select-sm d-inline-block w-auto ms-2">
                                            @foreach([5, 10, 25, 50, 100] as $size)
                                                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-white ms-2">éléments</span>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table text-white table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Niveau</th>
                                            <th>Filière</th>
                                            <th>Prix D'Inscription</th>
                                            <th>Prix De Mensuel</th>
                                            <th>Durée</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($classes as $classe)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $classe->formation->type_formation }} {{$classe->niveau}}
                                                </td>
                                                @php
                                                    $formationNom = $classe->formation->nom;
                                                    $motsIgnorés = ['de', 'des', 'du', 'en', 'à', 'aux', 'et', 'a'];
                                                    $acronyme = collect(explode(' ', $formationNom))
                                                        ->filter(fn($mot) => !in_array(strtolower($mot), $motsIgnorés))
                                                        ->map(fn($mot) => strtoupper(substr($mot, 0, 1)))
                                                        ->implode('.') . '.';
                                                @endphp
                                                <td title="{{ $formationNom }}">
                                                            {{ $acronyme }}
                                                            </td>
                                                <td>
                                                    {{ number_format($classe->prix_inscription, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>
                                                    {{ number_format($classe->prix_mensuel, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>{{ $classe->duree }} Mois</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        {{ $classes->appends(request()->query())->links('pagination::bootstrap-5') }}
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
@endif
</html>
