<!DOCTYPE html>
<html lang="fr">
<head>
    @include('sections.admin.head')
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        @include('sections.admin.barreRateral')

        <div class="content">
            @include('sections.admin.navbar')
            <div class="container-fluid p-4">
                <h4 class="mb-4 text-white">Modifier mes documents</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @foreach ($documents as $document)
                    <div class="card mb-3 bg-dark text-white shadow-lg">
                        <div class="card-body">
                            <h5>{{ $document->nom }}</h5>
                            <p>
                                @php
                                    $cheminFichier = storage_path('app/public/' . $document->chemin_fichier);
                                    $timestamp = file_exists($cheminFichier) ? filemtime($cheminFichier) : time();
                                @endphp

                                <a href="{{ asset('storage/' . $document->chemin_fichier) }}?v={{ $timestamp }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    Voir le document
                                </a>

                            </p>
                            <form action="{{ route('mes.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="fichier" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">Remplacer le document</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            @include('sections.admin.footer')
        </div>

        @include('sections.admin.script')
    </div>
</body>
</html>
