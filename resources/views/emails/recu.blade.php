<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
</head>
<body>
    <h2>Bonjour {{ $user->name }},</h2>

    <p>Nous confirmons la réception de votre paiement de
       <strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong>.</p>

    <p>Type de paiement : <strong>{{ $paiement->type_paiement }}</strong></p>
    <p>Date : <strong>{{ $paiement->date->format('d/m/Y H:i') }}</strong></p>

    <p>Vous trouverez en pièce jointe votre reçu au format PDF.</p>

    <br>
    <p>Cordialement,</p>
    <p>L'équipe ISI-Suptech</p>
</body>
</html>
