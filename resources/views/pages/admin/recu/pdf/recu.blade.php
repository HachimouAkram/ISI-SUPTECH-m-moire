<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px 0; }
        .infos, .details, .reste { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .infos td, .infos th, .details td, .details th, .reste td, .reste th { border: 1px solid black; padding: 6px; }
        .section-title { font-weight: bold; margin-top: 20px; text-decoration: underline; }
        .footer { margin-top: 40px; font-size: 11px; text-align: center; }
        .signature { margin-top: 30px; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Institut Supérieur d’Informatique</h2>
        <h3>Reçu de Paiement</h3>
    </div>

    <table class="infos">
        <tr>
            <th>N° reçu</th>
            <td>{{ $paiement->id }}</td>
            <th>Date</th>
            <td>{{ $paiement->date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Encaisse par</th>
            <td>Plateforme Stripe</td>
            <th>Nom étudiant</th>
            <td>{{ $user->nom }} {{ $user->prenom }}</td>
        </tr>
        <tr>
            <th>Classe</th>
            <td>{{ $paiement->inscription->classe->formation->nom ?? '' }}</td>
            <th>Mode de paiement</th>
            <td>{{ $paiement->mode_paiement }}</td>
        </tr>
    </table>

    <h4 class="section-title">Détails du paiement</h4>
    <table class="details">
        <tr>
            <th>Type de paiement</th>
            <th>Montant</th>
            <th>@if($paiement->type_paiement === 'Inscription') Année académique @else Mois payé @endif</th>
        </tr>
        <tr>
            <td>{{ $paiement->type_paiement }}</td>
            <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
            <td>
                @php
                    use Carbon\Carbon;

                    $classe = $paiement->inscription->classe;
                    $annee = $paiement->inscription->created_at->year;
                    $mois_map = [
                        'Janvier'=>1,'Février'=>2,'Mars'=>3,'Avril'=>4,
                        'Mai'=>5,'Juin'=>6,'Juillet'=>7,'Août'=>8,
                        'Septembre'=>9,'Octobre'=>10,'Novembre'=>11,'Décembre'=>12,
                    ];
                    $mois_debut = $mois_map[$classe->mois_rentree] ?? 1;

                    if($paiement->type_paiement === 'Inscription'){
                        // Afficher l'année académique (ex: 2025-2026)
                        $annee_academique = $annee . '-' . ($annee + 1);
                        echo $annee_academique;
                    } else {
                        // Paiement mensuel : afficher le mois payé
                        $total_paye = $paiement->inscription->paiements()
                                        ->where('id', '<=', $paiement->id)->sum('montant');
                        $reste = max(0, $total_paye - $classe->prix_inscription);
                        $mois_payes = ceil($reste / $classe->prix_mensuel);
                        $mois_paye = Carbon::createFromDate($annee, $mois_debut, 1)
                                        ->addMonths($mois_payes - 1)
                                        ->locale('fr')
                                        ->translatedFormat('F Y');
                        echo $mois_paye;
                    }
                @endphp
            </td>
        </tr>
    </table>


    {{-- Rappel des mois restants --}}
    <h4 class="section-title">Mois restant à payer</h4>
    @php
        $total_paye = $paiement->inscription->paiements()->sum('montant');
        $reste = max(0, ($classe->prix_inscription + $classe->prix_mensuel * ($classe->duree-1)) - $total_paye);

        // Nombre de mensualités payées (hors inscription)
        $mois_payes = floor(max(0, ($total_paye - $classe->prix_inscription) / $classe->prix_mensuel));

        // Début des mois restant = mois de rentrée
        $mois_debut_carbon = Carbon::createFromDate($annee, $mois_debut, 1);

        $mois_restants = [];
        for($i = $mois_payes; $i < $classe->duree-1; $i++){
            $mois_restants[] = $mois_debut_carbon->copy()->addMonths($i)->locale('fr')->translatedFormat('F Y');
        }
    @endphp

    <table class="reste">
        <tr>
            <th>Mois</th>
            <th>Montant</th>
        </tr>
        @foreach($mois_restants as $mois)
        <tr>
            <td>{{ $mois }}</td>
            <td>{{ number_format($classe->prix_mensuel, 0, ',', ' ') }} FCFA</td>
        </tr>
        @endforeach
        <tr>
            <th>Total restant</th>
            <th>{{ number_format($reste, 0, ',', ' ') }} FCFA</th>
        </tr>
    </table>

    <div class="signature">
        <div>
            <p><strong>Cachet et signature exigés</strong></p>
            <p>{{ $paiement->date->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <p><strong>La caisse</strong></p>
            <p style="color:blue;font-weight:bold;">PAYÉ</p>
        </div>
    </div>

    <div class="footer">
        <p>Merci pour votre paiement - {{ config('app.name') }}</p>
    </div>
</body>
</html>
