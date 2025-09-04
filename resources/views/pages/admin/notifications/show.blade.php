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

            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h4 class="mb-4">Détail de la notification</h4>
                            <h5>{{ $notification->data['title'] ?? 'Titre non défini' }}</h5>
                            <p>{{ $notification->data['message'] ?? 'Aucun message' }}</p>
                            <p class="text-muted">Reçue : {{ $notification->created_at->diffForHumans() }}</p>
                            <a href="{{ url()->previous() }}" class="btn btn-primary">Retour</a>
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
