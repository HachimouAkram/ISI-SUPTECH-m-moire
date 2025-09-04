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

            <div class="container-fluid p-4">
                <div class="card bg-dark text-white shadow-lg">
                    <div class="card-body">
                        <h3 class="mb-4">✏️ Modifier Document</h3>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Type de document</label>
                                <input type="text" class="form-control" value="{{ $document->nom }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2" readonly>{{ $types[$document->nom] ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Remplacer le fichier (PDF seulement)</label>
                                <input type="file" class="form-control" name="fichier" accept="application/pdf">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @include('sections.admin.footer')
        </div>
        <!-- Content End -->

        @include('sections.admin.script')
    </div>
</body>
</html>
