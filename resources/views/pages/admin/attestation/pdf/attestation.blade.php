<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat d'Inscription</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
            color: #000;
        }

        .certificate-container {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* En-tête avec logos */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 0 20px;
        }

        .isi-logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e4a72;
            text-align: left;
        }

        .isi-logo .isi {
            font-size: 32px;
        }

        .isi-logo .suptech {
            font-size: 18px;
            letter-spacing: 2px;
        }

        .institut-title {
            flex-grow: 1;
            text-align: center;
            padding: 0 20px;
        }

        .institut-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1e4a72;
            margin: 0;
            text-decoration: underline;
            letter-spacing: 1px;
        }

        .ref-number {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            color: #1e4a72;
        }

        /* Logos partenaires */
        .partner-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            gap: 15px;
            flex-wrap: wrap;
        }

        .partner-logos img {
            height: 40px;
            width: auto;
            max-width: 80px;
            object-fit: contain;
        }

        /* Titre du certificat */
        .certificate-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
            color: #000;
            letter-spacing: 1px;
        }

        /* Contenu principal */
        .content {
            margin: 40px 0;
            text-align: left;
            line-height: 1.8;
            font-size: 13px;
        }

        .attestation-text {
            margin-bottom: 25px;
        }

        .student-info {
            margin: 25px 0;
            line-height: 2;
        }

        .student-name {
            font-weight: bold;
            font-size: 14px;
        }

        .academic-year {
            margin: 25px 0;
        }

        /* Section filière */
        .filiere-section {
            text-align: center;
            margin: 40px 0;
            font-size: 14px;
        }

        .filiere-label {
            font-weight: bold;
            text-decoration: underline;
        }

        .filiere-name {
            font-weight: bold;
            margin-left: 10px;
        }

        /* Clause de validité */
        .validity {
            text-align: center;
            margin: 40px 0;
            font-size: 13px;
            line-height: 1.6;
        }

        /* Section signature */
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .date-location {
            text-align: left;
            font-size: 12px;
        }

        .director-signature {
            text-align: center;
            width: 200px;
        }

        .director-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .director-name {
            margin: 15px 0;
            font-weight: bold;
        }

        /* Sceau */
        .seal {
            width: 100px;
            height: 100px;
            border: 3px solid #1e4a72;
            border-radius: 50%;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #1e4a72;
            text-align: center;
            line-height: 1.2;
        }

        /* Pied de page */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #333;
            border-top: 1px solid #000;
            padding-top: 15px;
        }

        .contact-line {
            margin: 8px 0;
            font-weight: bold;
        }

        .branches {
            display: flex;
            justify-content: space-around; /* Ou space-evenly */
            align-items: flex-start;
            margin: 15px 0;
            font-size: 9px;
            text-align: center;
            flex-wrap: nowrap;
        }

        .branch {
            flex: 1;
            padding: 0 5px;
        }

        .address-line {
            margin-top: 15px;
            font-size: 9px;
            font-weight: bold;
        }

        /* Styles pour l'impression */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .certificate-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-top">
                <div class="isi-logo">
                    <img src="{{ public_path('images/logo_isi.jpeg') }}" alt="Logo ISI" style="height: 60px;">
                </div>

                <div class="institut-title">
                    <h1>INSTITUT SUPÉRIEUR DE TECHNOLOGIES</h1>
                </div>

                <div class="ref-number">
                    REF : #{{ str_pad($inscription->id, 5, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <!-- Logos partenaires -->
            <div class="partner-logos">
                <img src="{{ public_path('images/microsoft.jpg') }}" alt="Microsoft">
                <img src="{{ public_path('images/cisco.png') }}" alt="Cisco">
                <img src="{{ public_path('images/fnege.png') }}" alt="FNEGE">
                <img src="{{ public_path('images/allience.jpg') }}" alt="SAP University">
                <img src="{{ public_path('images/cames_logo.png') }}" alt="CAMES">
                <img src="{{ public_path('images/UADB.png') }}" alt="UADB">
            </div>
        </div>

        <!-- Titre du certificat -->
        <h2 class="certificate-title">CERTIFICAT D'INSCRIPTION</h2>

        <!-- Contenu principal -->
        <div class="content">
            <div class="attestation-text">
                Je soussigné le Directeur des Études de l'Institut Supérieur de Technologies,
            </div>

            <div class="student-info">
                Atteste que
                @if($user->civilite === 'M')
                    Monsieur
                @elseif($user->civilite === 'Mme')
                    Madame
                @else
                    Monsieur/Madame
                @endif
                <span class="student-name">{{ strtoupper($user->nom) }} {{ ucwords($user->prenom) }}</span>,
                né(e) le <strong>{{ $user->date_naissance ? Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') : '___/___/____' }}</strong>
                à <strong>{{ $user->lieu_naissance ?? '_________________' }}</strong>,
                est étudiant(e) dans notre Institut pour l'année
            </div>

            <div class="academic-year">
                académique <strong>{{ $inscription->programmeAccademique->annee_accademique ?? '2024-2025' }}</strong>.
            </div>
        </div>

        <!-- Section filière -->
        <div class="filiere-section">
            <span class="filiere-label">FILIÈRE</span>
            <span class="filiere-name">: {{ $inscription->classe->formation->nom }}
            @if($inscription->classe->formation->type_formation)
                - {{ $inscription->classe->formation->type_formation }}
            @endif
            </span>
        </div>

        <!-- Clause de validité -->
        <div class="validity">
            Ce certificat lui est délivré pour servir et valoir ce que de droit.
        </div>

        <!-- Section signature -->
        <div class="signature-section">
            <div class="date-location">
                Fait à Dakar, le {{ Carbon\Carbon::now()->format('d M Y') }}
            </div>

            <div class="director-signature">
                <div class="director-title">Le Directeur des Études</div>
                <div class="director-name">{{ config('app.director_name', 'Serigne M. Kara SAMB') }}</div>
                <div class="seal">
                    <!-- Sceau de l'institut -->
                </div>
            </div>
        </div>

       <!-- Pied de page -->
        <div class="footer">
            <div class="contact-line">
                TEL: (+221) 33 825 62 10 - POR: (+221) 77 978 26 18 - Site Web: www.suptech.sn - E-mail: suptech@suptech.info
            </div>

            <div class="branches">
                <div class="branch">
                    <strong>ISI DAKAR</strong><br>
                    Tél : 338252610<br>
                    suptech@suptech.sn
                </div>
                <div class="branch">
                    <strong>ISI SUPTECH</strong><br>
                    Tél : 33 849 26 75<br>
                    suptech@suptech.info
                </div>
                <div class="branch">
                    <strong>ISI KAOLACK</strong><br>
                    Tél : 33 941 26 39<br>
                    kaolack@groupesuptech.com
                </div>
                <div class="branch">
                    <strong>ISI DIOURBEL</strong><br>
                    Tél : 33 971 37 37<br>
                    diourbel@groupesuptech.com
                </div>
                <div class="branch">
                    <strong>ISI KAFFRINE</strong><br>
                    Tél : 33 946 12 12<br>
                    kaffrine@groupesuptech.com
                </div>
                <div class="branch">
                    <strong>ISI KOLNACK</strong><br>
                    Tél : 77 530 33 33<br>
                    kolnack@suptech.sn
                </div>
                <div class="branch">
                    <strong>ISI NOLAKHIOU</strong><br>
                    Tél : 31 17 32 53<br>
                    nolakhiou@suptech.sn
                </div>
            </div>

            <div class="address-line">
                Allées Khalifa Abubacar SY - Liberté 3 N°1977, B.P. 47 226 - DAKAR - LIBERTÉ
            </div>
        </div>
    </div>
</body>
</html>
