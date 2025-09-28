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
         <!-- welcome.blade.php -->
        <div class="content">
            @include('sections.admin.navbar')

            <div class="container-fluid p-4">
                <div class="card bg-dark text-white shadow-lg">
                    <div class="card-body">
                        <!-- Étape 1 : Présentation -->
                        <h2 class="mb-3">👋 Bienvenue {{ Auth::user()->prenom }} !</h2>
                        <p class="lead">
                            Nous sommes ravis de vous accueillir dans notre établissement. En vous inscrivant, vous accédez à un parcours académique structuré, des formations certifiantes, et un accompagnement personnalisé.
                        </p>
                        <p>
                            Avant de commencer, veuillez vérifier vos informations personnelles. Elles seront utilisées pour compléter votre dossier d'inscription.
                        </p>

                        <!-- Étape 2 : Vérification du profil -->
                        <div class="alert alert-info mt-4">
                            <strong>📋 Vos informations actuelles :</strong><br>
                            Nom : {{ Auth::user()->nom }}<br>
                            Prénom : {{ Auth::user()->prenom }}<br>
                            Email : {{ Auth::user()->email }}<br>
                            Téléphone : {{ Auth::user()->telephone }}<br>
                            Date de naissance : {{ Auth::user()->date_naissance }}<br>
                            Sexe : {{ Auth::user()->sexe }}<br>
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                    alt="Photo de profil"
                                    class="img-thumbnail mt-2"
                                    width="120">
                            @else
                                <p class="text-warning mt-2">⚠️ Vous n'avez pas encore ajouté de photo. Ce n'est pas obligatoire, mais recommandé.</p>
                            @endif
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil-square"></i> Modifier mon profil
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">

                            @if($inscriptionEnCours)
                                <div class="alert mt-4" style="background-color: #191C24; color: #fcfcfa;">
                                    <h5 class="fw-bold" style="color: #dec106;">⚠️ Inscription en cours détectée</h5>
                                    <p>
                                        Vous avez déjà une inscription en cours. Merci de patienter jusqu'à ce que votre inscription soit validée ou refusée avant d’en faire une nouvelle.
                                    </p>
                                    <p>
                                        Toute inscription validée implique le paiement intégral des frais pour l'année scolaire concernée afin de pouvoir effectuer une nouvelle inscription.
                                        <br><br>
                                        Si vous souhaitez annuler votre inscription en cours, veuillez contacter l'administration avant que celle-ci ne soit considérée comme engagée.
                                        <br><br>
                                        Passé un délai d’un mois après le début de la formation sans paiement, l'inscription sera automatiquement considérée comme une dette. Dans ce cas, vous serez redevable des frais d’inscription ainsi que d’un premier mois de mensualité.
                                        <br><br>
                                        Au-delà de ce délai, les mensualités dues augmenteront en fonction du nombre de mois écoulés.
                                        <br><br>
                                        L’accès à l’établissement sera strictement réservé aux étudiants à jour dans leurs paiements. De plus, aucune nouvelle inscription ne pourra être effectuée tant que les frais antérieurs ne seront pas régularisés.
                                    </p>
                                </div>
                            @elseif($resteAPayer > 0)
                                <div class="alert mt-4" style="background-color: #191C24; color: #fcfcfa;">
                                    <h5 class="fw-bold" style="color: #dec106;">⚠️ Dettes détectées</h5>
                                    <p>
                                        Vous devez encore <strong>{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</strong>
                                        sur votre dernière inscription validée.<br>
                                        Veuillez régulariser votre situation avant de procéder à une nouvelle inscription.
                                        <br><br>
                                        Toute inscription validée implique le paiement intégral des frais pour l'année scolaire concernée afin de pouvoir effectuer une nouvelle inscription.
                                        <br><br>
                                        Si vous souhaitez annuler votre inscription en cours, veuillez contacter l'administration.
                                        <br><br>
                                    </p>
                                </div>
                            @else
                                <!-- Bouton Suivant + formulaire -->
                                <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#etapeFormation">
                                    <i class="bi bi-arrow-right-circle"></i> Suivant
                                </button>

                                <div class="collapse mt-5" id="etapeFormation">
                                    <form action="{{ route('inscriptions.store') }}" method="POST">
                                        @csrf
                                        <!-- Champs du formulaire ici -->
                                    </form>
                                </div>
                            @endif

                        </div>


                        <!-- Étape 3 : Choix de formation et classe -->
                        <div class="collapse mt-5" id="etapeFormation">
                            <h4 class="mb-3">🎓 Choisissez votre formation et classe</h4>
                            <form action="{{ route('inscriptions.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">

                                <div class="mb-3">
                                    <label for="formation_id" class="form-label">Formation</label>
                                    <select id="formation_id" class="form-select" required>
                                        <option value="">-- Choisir une formation --</option>
                                        @foreach($formations as $formation)
                                            <option value="{{ $formation->id }}">{{ $formation->type_formation }} en {{ $formation->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="classe_id" class="form-label">Classe</label>
                                    <select name="classe_id" id="classe_id" class="form-select" required>
                                        <option value="">-- Sélectionner une classe --</option>
                                        <!-- Les options seront chargées dynamiquement -->
                                    </select>
                                </div>
                                <div id="classe-details" class="alert alert-secondary d-none mt-3">
                                    <p><strong>💰 Prix inscription :</strong> <span id="prix_inscription"></span> FCFA</p>
                                    <p><strong>📅 Prix mensuel :</strong> <span id="prix_mensuel"></span> FCFA</p>
                                    <p><strong>⏳ Durée :</strong> <span id="duree_mois"></span> mois</p>
                                    <hr>
                                    <p class="fw-bold text-success">
                                        🧮 Total estimé : <span id="prix_total"></span> FCFA
                                    </p>
                                </div>

                                <input type="hidden" name="programme_accademique_id" value="{{ $programmeActif->id }}">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Valider la demande
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('sections.admin.footer')
        </div>
        <!-- Content End -->

        <!-- Scripts -->
        @section('scripts')
        @endsection
    @include('sections.admin.script')
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formationSelect = document.getElementById('formation_id');
    const classeSelect = document.getElementById('classe_id');

    const detailsContainer = document.getElementById('classe-details');
    const prixInscription = document.getElementById('prix_inscription');
    const prixMensuel = document.getElementById('prix_mensuel');
    const dureeMois = document.getElementById('duree_mois');
    const prixTotal = document.getElementById('prix_total');

    let classesData = []; // On stocke toutes les classes pour retrouver les détails plus tard

    formationSelect.addEventListener('change', function () {
        const formationId = this.value;
        classeSelect.innerHTML = '<option value="">Chargement...</option>';
        detailsContainer.classList.add('d-none'); // Cacher les détails à chaque changement

        fetch(`/formations/${formationId}/classes`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                classesData = data; // Stocker les classes reçues
                classeSelect.innerHTML = '<option value="">-- Sélectionner une classe --</option>';
                data.sort((a, b) => a.niveau - b.niveau);

                data.forEach(classe => {
                    const option = document.createElement('option');
                    option.value = classe.id;
                    const type = classe.formation?.type_formation || '';
                    const niveau = classe.niveau || '';
                    option.textContent = `${type} ${niveau}`;
                    classeSelect.appendChild(option);
                });

                if (data.length === 0) {
                    classeSelect.innerHTML = '<option value="">Aucune classe disponible</option>';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des classes:', error);
                classeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    });

    classeSelect.addEventListener('change', function () {
        const selectedId = parseInt(this.value);

        const selectedClasse = classesData.find(c => c.id === selectedId);

        if (selectedClasse) {
            const inscription = parseFloat(selectedClasse.prix_inscription) || 0;
            const mensuel = parseFloat(selectedClasse.prix_mensuel) || 0;
            const duree = parseInt(selectedClasse.duree) || 0;

            const total = inscription + (mensuel * duree);

            prixInscription.textContent = inscription.toLocaleString();
            prixMensuel.textContent = mensuel.toLocaleString();
            dureeMois.textContent = duree;
            prixTotal.textContent = total.toLocaleString();

            detailsContainer.classList.remove('d-none');
        } else {
            detailsContainer.classList.add('d-none');
        }
    });
});
</script>
</html>
