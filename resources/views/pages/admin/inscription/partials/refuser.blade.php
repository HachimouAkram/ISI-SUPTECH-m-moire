<div class="table-responsive">
    <table class="table text-white table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Formation</th>
                <th>Niveau</th>
                <th>Date d'inscription</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscriptions as $inscription)
                @php
                    $formationNom = $inscription->classe->formation->nom;
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
                    <td>{{ $inscription->classe->formation->type_formation }} {{ $inscription->classe->niveau }}</td>
                    <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <!-- Bouton Voir -->
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetails{{ $inscription->id }}" title="Voir">
                            <i class="bi bi-eye"></i>
                        </button>
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

<!-- Modals en dehors de la table -->
@foreach($inscriptions as $inscription)
    <div class="modal fade" id="modalDetails{{ $inscription->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $inscription->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $inscription->id }}">Détails de l'inscription</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <h6><strong>Informations de l Etudiant :</strong></h6>
                    <ul>
                        <li>Nom               : {{ $inscription->user->nom }}</li>
                        <li>Prénom            : {{ $inscription->user->prenom }}</li>
                        <li>Email             : {{ $inscription->user->email }}</li>
                        <li>Téléphone         : {{ $inscription->user->telephone }}</li>
                        <li>Date de naissance : {{ $inscription->user->date_naissance }}</li>
                        <li>Sexe              : {{ $inscription->user->sexe }}</li>
                        <!-- Autres infos utiles -->
                    </ul>

                    <h6 class="mt-4"><strong>Documents fourni :</strong></h6>
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
