<div class="table-responsive">
    <table class="table text-white table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Formation</th>
                <th>Niveau</th>
                <th>Date d'inscription</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscriptions as $inscription)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $inscription->user->nom }}</td>
                    <td>{{ $inscription->user->prenom }}</td>
                    @php
                        $formationNom = $inscription->classe->formation->nom;
                        $motsIgnorés = ['de', 'des', 'du', 'en', 'à', 'aux', 'et', 'a'];
                        $acronyme = collect(explode(' ', $formationNom))
                            ->filter(fn($mot) => !in_array(strtolower($mot), $motsIgnorés))
                            ->map(fn($mot) => strtoupper(substr($mot, 0, 1)))
                            ->implode('.') . '.';
                    @endphp

                    <td title="{{ $formationNom }}">
                        {{ $acronyme }}
                    </td>

                    <td>{{ $inscription->classe->formation->type_formation }} {{ $inscription->classe->niveau }}</td>
                    <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('inscriptions.show', $inscription->id) }}" class="btn btn-sm btn-info" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $inscriptions->links('pagination::bootstrap-5') }}
    </div>
</div>
