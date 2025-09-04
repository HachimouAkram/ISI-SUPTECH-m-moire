<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        @include('sections.admin.barreRateral')

        <!-- Content Start -->
        <div class="content">
            @include('sections.admin.navbar')

            <!-- Formations Table -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-4">Liste des inscriptions - {{ ucfirst($statut) }}</h6>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ Ajouter</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <form method="GET" action="{{ route('inscriptions.index') }}">
                                        <input type="hidden" name="statut" value="{{ request('statut', $statut) }}">

                                        <label for="per_page" class="text-white">Afficher</label>
                                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto ms-2">
                                            @foreach([5, 10, 25, 50, 100] as $size)
                                                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-white ms-2">éléments</span>
                                    </form>
                                </div>
                            </div>
                            @includeIf('pages.admin.inscription.partials.' . strtolower($statut), ['inscriptions' => $inscriptions])
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
