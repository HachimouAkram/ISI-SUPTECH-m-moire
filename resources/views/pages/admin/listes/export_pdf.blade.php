<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des étudiants</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>{{ $classe->formation->type_formation }} {{ $classe->niveau }} - {{ $classe->formation->nom }}</h2>
    <h3>Année académique : {{ $programme->annee_accademique }}</h3>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $key => $inscription)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $inscription->user->nom }}</td>
                    <td>{{ $inscription->user->prenom }}</td>
                    <td>{{ $inscription->user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
