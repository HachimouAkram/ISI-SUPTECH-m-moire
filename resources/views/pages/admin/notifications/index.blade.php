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
                <div class="bg-secondary rounded h-100 p-4">
                    <h4 class="mb-4">Toutes les notifications</h4>

                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <a href="{{ route('notifications.show', $notification->id) }}"
                                   class="list-group-item list-group-item-action {{ is_null($notification->read_at) ? 'list-group-item-warning' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->data['message'] ?? 'Aucun message' }}</p>
                                </a>
                            @endforeach
                        </div>

                       
                    @else
                        <div class="text-center text-muted">Aucune notification</div>
                    @endif

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
