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
                            <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Liste des Étudiants</h6>
                            @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur')
                            <!-- Bouton Ajouter Étudiant -->
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addEtudiantModal">
                                <i class="bi bi-person-plus"></i> Ajouter Étudiant
                            </button>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <form method="GET" action="{{ route('users.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm me-2"
                                    placeholder="Rechercher nom ou prénom" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-primary">Rechercher</button>
                            </form>
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
                                            <!-- Bouton Voir Profil -->
                                            <button class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $user->id }}"
                                                    title="Voir le profil">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if(Auth::user()->fonction === 'Tresorier' || Auth::user()->fonction === 'Directeur')
                                                <!-- Bouton Payer -->
                                                <button class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paiementModal{{ $user->id }}"
                                                        title="Payer">
                                                    <i class="bi bi-cash-coin"></i>
                                                </button>
                                            @endif
                                            <!-- Bouton Inscrire -->
                                            @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur')
                                            <button class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#inscriptionModal{{ $user->id }}"
                                                    title="Inscrire l'étudiant">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal Ajouter Étudiant -->
                                    <div class="modal fade" id="addEtudiantModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ajouter un nouvel étudiant</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <form method="POST" action="{{ route('users.store.etudiant') }}">
                                                    @csrf
                                                    <input type="hidden" name="role" value="etudiant">
                                                    <input type="hidden" name="password" value="passer123">
                                                    <input type="hidden" name="must_change_password" value="1">

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="nom" class="form-label">Nom</label>
                                                            <input type="text" name="nom" id="nom" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="prenom" class="form-label">Prénom</label>
                                                            <input type="text" name="prenom" id="prenom" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" name="email" id="email" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="telephone" class="form-label">Téléphone</label>
                                                            <input type="text" name="telephone" id="telephone" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="sexe" class="form-label">Sexe</label>
                                                            <select name="sexe" id="sexe" class="form-select" required>
                                                                <option value="">-- Sélectionner --</option>
                                                                <option value="Homme">Masculin</option>
                                                                <option value="Femme">Feminin</option>
                                                                <option value="Autre">Autre</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="date_naissance" class="form-label">Date de naissance</label>
                                                            <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-check-circle"></i> Enregistrer
                                                        </button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

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

                                    <!-- Modal Paiement -->
                                    <div class="modal fade" id="paiementModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Paiement pour {{ $user->nom }} {{ $user->prenom }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <form method="POST" action="{{ route('paiement.espece.store', $user->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @php
                                                            $inscription = $user->inscriptions()->where('statut','Valider')->latest()->first();
                                                            $classe = $inscription ? $inscription->classe : null;

                                                            $frais_inscription = $classe ? $classe->prix_inscription : 0;
                                                            $mensualite = $classe ? $classe->prix_mensuel : 0;
                                                            $duree = $classe ? $classe->duree : 0;

                                                            if ($inscription && $classe) {
                                                                $total_a_payer = $frais_inscription + $mensualite * ($duree - 1);
                                                                $total_paye = $inscription->paiements()->sum('montant');
                                                                $montant_restant = $total_a_payer - $total_paye;

                                                                $inscriptionPayee = $inscription->paiements()->where('type_paiement','Inscription')->exists();
                                                                $typePaiement = $inscriptionPayee ? 'Mensualité' : 'Inscription';
                                                            }
                                                        @endphp


                                                        @if(!$inscription)
                                                            <p class="text-danger">⚠️ Cet étudiant n’a pas encore d’inscription validée.</p>
                                                        @elseif($montant_restant <= 0)
                                                            <p class="text-success">✅ Toutes les mensualités ont déjà été réglées.</p>
                                                        @else
                                                            <p>
                                                                ⚠️ Le paiement sera enregistré pour :
                                                                <strong>{{ $typePaiement }}</strong>
                                                            </p>
                                                            <button type="submit" class="btn btn-success">Valider le paiement</button>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Inscription -->
                                    <div class="modal fade" id="inscriptionModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Inscription de {{ $user->nom }} {{ $user->prenom }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <form action="{{ route('inscription.store.by.admin') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">

                                                    <div class="modal-body">
                                                        @php
                                                            $inscriptionEnCours = isset($inscriptionsEnCours[$user->id]) && $inscriptionsEnCours[$user->id]->count() > 0;
                                                        @endphp

                                                        @if(!$inscriptionEnCours)
                                                            <div class="mb-3">
                                                                <label for="formation_{{ $user->id }}" class="form-label">Formation</label>
                                                                <select class="form-select formation-select"
                                                                        data-user="{{ $user->id }}"
                                                                        id="formation_{{ $user->id }}">
                                                                    <option value="">-- Sélectionner une formation --</option>
                                                                    @foreach($formations as $formation)
                                                                        <option value="{{ $formation->id }}">{{ $formation->type_formation }} en {{ $formation->nom }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="classe_{{ $user->id }}" class="form-label">Classe</label>
                                                                <select name="classe_id" id="classe_{{ $user->id }}" class="form-select" required>
                                                                    <option value="">-- Sélectionner une classe --</option>
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="programme_accademique_id" value="{{ $programmeActif->id ?? '' }}">
                                                        @else
                                                            <div class="alert mt-2" style="background-color: #191C24; color: #fcfcfa;">
                                                                <h5 class="fw-bold" style="color: #dec106;">⚠️ Inscription en cours détectée</h5>
                                                                <p>
                                                                    Cet étudiant a déjà une inscription en cours.
                                                                    Merci de finaliser ou d’annuler celle-ci avant de lancer une nouvelle inscription.
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="modal-footer">
                                                        @if(!$inscriptionEnCours)
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="bi bi-check-circle"></i> Valider l'inscription
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>

                                                </form>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formation-select').forEach(select => {
        select.addEventListener('change', function () {
            const formationId = this.value;
            const userId = this.dataset.user;
            const classeSelect = document.getElementById(`classe_${userId}`);

            classeSelect.innerHTML = '<option value="">Chargement...</option>';

            fetch(`/formations/${formationId}/classes`)
                .then(res => res.json())
                .then(data => {
                    classeSelect.innerHTML = '<option value="">-- Sélectionner une classe --</option>';
                    data.forEach(classe => {
                        const option = document.createElement('option');
                        option.value = classe.id;
                        option.textContent = `${classe.formation?.type_formation || ''} ${classe.niveau}`;
                        classeSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    classeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        });
    });
});
</script>
</html>
