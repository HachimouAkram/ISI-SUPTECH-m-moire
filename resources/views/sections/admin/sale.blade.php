<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 border border-light">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0 text-white">Formations récentes</h6>
        </div>

        <!-- Conteneur du tableau avec scroll si nécessaire -->
        <div class="table-responsive border rounded">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white bg-dark">
                        <th scope="col"><input class="form-check-input" type="checkbox"></th>
                        <th scope="col">Nom</th>
                        <th scope="col">Durée</th>
                        <th scope="col">Niveau</th>
                        <th scope="col">Domaine</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formations as $formation)
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ $formation->nom }}</td>
                        <td>{{ $formation->duree }}</td>
                        <td>{{ $formation->type_formation }}</td>
                        <td>{{ $formation->domaine }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination centrée -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $formations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
