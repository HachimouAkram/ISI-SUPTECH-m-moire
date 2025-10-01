<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Chargement...</span>
            </div>
        </div>
        @include('sections.admin.barreRateral')
        <!-- Content Start -->
        <div class="content">
            @include('sections.admin.navbar')
            <div class="container-fluid p-5 text-center">
                <div class="card bg-secondary shadow-lg">
                    <div class="card-body">
                        <h2 class="text-success mb-3">✅ Inscription envoyée avec succès !</h2>
                        <p class="text-warning mt-3">
                            ⚠️ Vous pouvez revenir plus tard pour ajouter vos documents nécessaires.
                            Toutefois, toute inscription restée incomplète pendant plus de <strong>5 jours</strong> sera automatiquement <strong>refusée</strong>.
                            Merci de votre compréhension.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                                <i class="bi bi-house-door"></i> Aller au dashboard
                            </a>
                            <a href="{{ route('documents.create') }}">
                                <button class="btn btn-success" disabled>
                                    <i class="bi bi-upload"></i> Ajouter des documents
                                </button>
                            </a>
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
</html>
