<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Sélection Formation et Classe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="mb-4">🎓 Sélectionnez une formation et une classe</h4>

            <form>
                <div class="mb-3">
                    <label for="formation_id" class="form-label">Formation</label>
                    <select id="formation_id" class="form-select">
                        <option value="">-- Choisir une formation --</option>
                        @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}">{{ $formation->type_formation }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="classe_id" class="form-label">Classe</label>
                    <select id="classe_id" class="form-select">
                        <option value="">-- Sélectionnez une formation d'abord --</option>
                    </select>
                </div>
            </form>

            <div id="message" class="text-danger mt-3"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formationSelect = document.getElementById('formation_id');
    const classeSelect = document.getElementById('classe_id');
    const messageDiv = document.getElementById('message');

    formationSelect.addEventListener('change', function () {
        const formationId = this.value;
        classeSelect.innerHTML = '<option value="">Chargement...</option>';
        messageDiv.textContent = '';

        if (!formationId) {
            classeSelect.innerHTML = '<option value="">-- Sélectionnez une formation d\'abord --</option>';
            return;
        }

        fetch(`/formations/${formationId}/classes`)
            .then(response => {
                if (!response.ok) throw new Error("Erreur HTTP: " + response.status);
                return response.json();
            })
            .then(data => {
                classeSelect.innerHTML = '<option value="">-- Sélectionner une classe --</option>';

                data.sort((a, b) => a.niveau.localeCompare(b.niveau));

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
                messageDiv.textContent = 'Une erreur est survenue lors du chargement des classes.';
            });
    });
});
</script>

</body>
</html>
