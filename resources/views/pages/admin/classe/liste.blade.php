<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

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
                            <h6 class="mb-0"><i class="fas fa-school me-2"></i>Liste des Classes</h6>
                            @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur')
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addModal">➕ Ajouter</button>
                            @endif
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
                                    <th>Prix (Inscription & Mensuel)</th>
                                    <th>Durée</th>
                                    @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur')
                                        <th class="text-center">Actions</th>
                                    @endif
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
                                            $motsIgnorés = ['de', 'des', 'du', 'en', 'à', 'aux', 'et', 'a', 'la', 'le', 'l', 'd'];
                                            $acronyme = collect(explode(' ', $formationNom))
                                                ->filter(fn($mot) => !in_array(strtolower($mot), $motsIgnorés))
                                                ->map(fn($mot) => strtoupper(substr($mot, 0, 1)))
                                                ->implode('.') . '.';
                                        @endphp
                                        <td title="{{ $formationNom }}">
                                                    {{ $acronyme }}
                                                    </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">
                                                {{ number_format($classe->prix_inscription, 0, ',', ' ') }} FCFA
                                            </span>
                                            <span class="mx-1">&</span>
                                            <span class="badge bg-success fs-6">
                                                {{ number_format($classe->prix_mensuel, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                        <td>{{ $classe->duree }} Mois</td>
                                        @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur')
                                            <td class="text-center d-flex justify-content-center gap-2">
                                                @can('modifier_classes')
                                                <button class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $classe->id }}"
                                                        title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @endcan

                                                <form method="POST" action="{{ route('classes.destroy', $classe) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Voulez-vous vraiment supprimer cette classe ?')"
                                                            title="Supprimer">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Modal Modifier -->
                                    <div class="modal fade" id="editModal{{ $classe->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('classes.update', $classe) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content bg-dark text-white">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modifier Classe</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Fermer"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('pages.admin.classe.form', ['classe' => $classe])
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

        <!-- Modal Ajouter -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('classes.store') }}">
                    @csrf
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter Classe</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            @include('pages.admin.classe.form', ['classe' => null])
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
</html>
