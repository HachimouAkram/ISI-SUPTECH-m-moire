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
                <div class="card bg-dark text-white shadow-lg">
                    <div class="card-body">
                        <h3 class="mb-4">📄 Ajouter Vos Document</h3>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nom" class="form-label">Document nécessaires</label>
                                <select name="nom" id="nom" class="form-select" required>
                                    <option value="">-- Sélectionnez --</option>
                                    @foreach($types as $key => $desc)
                                        <option value="{{ $key }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description"
                                          id="description"
                                          class="form-control"
                                          rows="2"
                                          readonly
                                ></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="fichier" class="form-label">Fichier PDF</label>
                                <input type="file" class="form-control" name="fichier" accept="application/pdf" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-upload"></i> Ajouter Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('sections.admin.footer')
        </div>

        @include('sections.admin.script')
    </div>

    <script>
        // On prépare la map nom → description
        const descriptions = @json($types);

        document.getElementById('nom').addEventListener('change', function() {
            const val = this.value;
            const desc = descriptions[val] || '';
            document.getElementById('description').value = desc;
        });
    </script>
</body>
</html>
