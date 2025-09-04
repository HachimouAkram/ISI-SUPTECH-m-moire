<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation d'Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 20px 0;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 ISI SUPTECH</h1>
        <p>Institut Supérieur de Technologies</p>
    </div>

    <div class="content">
        <h2>Bonjour {{ $user->prenom }} {{ $user->nom }},</h2>

        <p>Félicitations ! Votre inscription a été validée et votre paiement d'inscription a été confirmé.</p>

        <div class="info-box">
            <h3>📋 Informations sur votre inscription :</h3>
            <ul>
                <li><strong>Formation :</strong> {{ $inscription->classe->formation->nom }}</li>
                <li><strong>Niveau :</strong> {{ $inscription->classe->formation->type_formation ?? 'N/A' }}</li>
                <li><strong>Année académique :</strong> {{ $inscription->programmeAccademique->annee_accademique ?? '2024-2025' }}</li>
                <li><strong>Date d'inscription :</strong> {{ $inscription->created_at->format('d/m/Y') }}</li>
            </ul>
        </div>

        <div class="highlight">
            <h3>📄 Votre attestation d'inscription</h3>
            <p>Vous trouverez en pièce jointe votre <strong>attestation d'inscription officielle</strong>.
            Ce document certifie votre statut d'étudiant(e) à l'ISI SUPTECH pour l'année académique en cours.</p>

            <p><strong>Important :</strong> Conservez précieusement ce document, il pourra vous être demandé pour :</p>
            <ul>
                <li>Les démarches administratives</li>
                <li>Les demandes de bourse</li>
                <li>Les réductions étudiantes</li>
                <li>Tout autre justificatif de scolarité</li>
            </ul>
        </div>

        <h3>📞 Besoin d'aide ?</h3>
        <p>Si vous avez des questions concernant votre inscription ou nos formations, n'hésitez pas à nous contacter :</p>
        <ul>
            <li><strong>Email :</strong> suptech@suptech.sn</li>
            <li><strong>Téléphone :</strong> (+221) 33 825 62 10</li>
            <li><strong>Site web :</strong> www.suptech.sn</li>
        </ul>

        <p><strong>Prochaines étapes :</strong></p>
        <ol>
            <li>Consultez régulièrement votre compte étudiant</li>
            <li>Préparez-vous pour la rentrée académique</li>
            <li>Surveillez vos emails pour les informations importantes</li>
        </ol>
    </div>

    <div class="footer">
        <p><strong>ISI SUPTECH - Institut Supérieur de Technologies</strong></p>
        <p>Dakar, Sénégal | Tél: (+221) 33 825 62 10 | www.suptech.sn</p>
        <p style="margin-top: 10px; font-size: 11px;">
            Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
        </p>
    </div>
</body>
</html>
