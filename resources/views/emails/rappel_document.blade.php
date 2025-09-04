<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rappel de documents</title>
</head>
<body>
    <p>Bonjour {{ $user->prenom }} {{ $user->nom }},</p>

    <p>Nous vous rappelons que certains documents sont manquants dans votre dossier d'inscription.</p>

    <p><strong>Message de l’administration :</strong></p>
    <blockquote>{{ $messageRappel }}</blockquote>

    <p>Merci de bien vouloir les fournir dans les plus brefs délais.</p>

    <p>Cordialement,<br>L’équipe administrative</p>
</body>
</html>
