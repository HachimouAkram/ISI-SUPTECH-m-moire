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

            <!-- Formations Table -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-4">Liste des mes inscriptions </h6>
                                <a href="{{ route('documents.create') }}">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ Ajouter vos documents</button>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <form method="GET" action="{{ route('inscriptions.mes') }}">

                                        <label for="per_page" class="text-white">Afficher</label>
                                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto ms-2">
                                            @foreach([10, 25, 50, 100] as $size)
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
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Formation</th>
                                            <th>Niveau</th>
                                            <th>Année Académique</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inscriptions as $inscription)
                                            @php
                                                $formationNom = $inscription->classe->formation->nom ?? '';
                                                $motsIgnorés = ['de', 'des', 'du', 'en', 'à', 'aux', 'et', 'a'];
                                                $acronyme = collect(explode(' ', $formationNom))
                                                    ->filter(fn($mot) => !in_array(strtolower($mot), $motsIgnorés))
                                                    ->map(fn($mot) => strtoupper(substr($mot, 0, 1)))
                                                    ->implode('.') . '.';
                                            @endphp

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $inscription->user->nom }}</td>
                                                <td>{{ $inscription->user->prenom }}</td>
                                                <td title="{{ $formationNom }}">{{ $acronyme }}</td>
                                                <td>{{ $inscription->classe->formation->type_formation ?? '' }} {{ $inscription->classe->niveau ?? '' }}</td>
                                                <td>{{ optional($inscription->programmeAccademique)->annee_accademique ?? 'Non défini' }}</td>
                                                <td>{{ $inscription->statut }}</td>
                                                <td class="text-center">
                                                    <!-- Voir détails -->
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetails{{ $inscription->id }}" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    @if($inscription->statut === 'Encours')
                                                        <!-- Modifier inscription -->
                                                        <a href="{{ route('mes.documents.edit') }}" class="btn btn-sm btn-warning" title="Modifier mes documents">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $inscriptions->links('pagination::bootstrap-5') }}
                                </div>
                            </div>

                            <!-- Modals -->
                            @foreach($inscriptions as $inscription)
                                <div class="modal fade" id="modalDetails{{ $inscription->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $inscription->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content bg-dark text-white">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel{{ $inscription->id }}">Détails de l'inscription</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6><strong>Informations de l'étudiant :</strong></h6>
                                                <ul>
                                                    <li>Nom               : {{ $inscription->user->nom }}</li>
                                                    <li>Prénom            : {{ $inscription->user->prenom }}</li>
                                                    <li>Email             : {{ $inscription->user->email }}</li>
                                                    <li>Téléphone         : {{ $inscription->user->telephone }}</li>
                                                    <li>Date de naissance : {{ $inscription->user->date_naissance }}</li>
                                                    <li>Sexe              : {{ $inscription->user->sexe }}</li>
                                                </ul>

                                                <h6 class="mt-4"><strong>Documents fournis :</strong></h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered text-white">
                                                        <thead>
                                                            <tr>
                                                                <th>Nom du document</th>
                                                                <th>Chemin</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($inscription->documents as $document)
                                                                <tr>
                                                                    <td>{{ $document->nom }}</td>
                                                                    <td>{{ $document->chemin_fichier }}</td>
                                                                    <td>
                                                                        <a href="{{ asset('storage/' . $document->chemin_fichier) }}" target="_blank" class="btn btn-sm btn-outline-light">
                                                                            Ouvrir
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr><td colspan="3" class="text-center">Aucun document disponible</td></tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
