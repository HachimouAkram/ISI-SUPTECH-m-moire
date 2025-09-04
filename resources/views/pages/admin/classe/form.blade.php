<form action="{{ route('classes.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="prix_inscription" class="form-label">Prix d'inscription</label>
        <input type="number" step="0.01" name="prix_inscription" class="form-control @error('prix_inscription') is-invalid @enderror"
               value="{{ old('prix_inscription', $classe->prix_inscription ?? '') }}">
        @error('prix_inscription')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="prix_mensuel" class="form-label">Prix mensuel</label>
        <input type="number" step="0.01" name="prix_mensuel" class="form-control @error('prix_mensuel') is-invalid @enderror"
               value="{{ old('prix_mensuel', $classe->prix_mensuel ?? '') }}">
        @error('prix_mensuel')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="duree" class="form-label">Durée (en mois)</label>
        <input type="number" name="duree" class="form-control @error('duree') is-invalid @enderror"
               value="{{ old('duree', $classe->duree ?? '') }}">
        @error('duree')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="niveau" class="form-label">Niveau</label>
        <input type="number" name="niveau" class="form-control @error('niveau') is-invalid @enderror"
               value="{{ old('niveau', $classe->niveau ?? '') }}">
        @error('niveau')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="mois_rentree" class="form-label">Mois de rentrée</label>
        <select name="mois_rentree" class="form-select @error('mois_rentree') is-invalid @enderror">
            @php
                $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                         'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            @endphp
            @foreach($mois as $m)
                <option value="{{ $m }}"
                    {{ old('mois_rentree', $classe->mois_rentree ?? '') == $m ? 'selected' : '' }}>
                    {{ $m }}
                </option>
            @endforeach
        </select>
        @error('mois_rentree')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="etat" class="form-label">État</label>
        <select name="etat" class="form-select @error('etat') is-invalid @enderror">
            <option value="1" {{ old('etat', $classe->etat ?? 1) == 1 ? 'selected' : '' }}>Actif</option>
            <option value="0" {{ old('etat', $classe->etat ?? 1) == 0 ? 'selected' : '' }}>Inactif</option>
        </select>
        @error('etat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="formation_id" class="form-label">Formation</label>
        <select name="formation_id" class="form-select @error('formation_id') is-invalid @enderror">
            @foreach($formations as $formation)
                <option value="{{ $formation->id }}"
                    {{ old('formation_id', $classe->formation_id ?? '') == $formation->id ? 'selected' : '' }}>
                    {{ $formation->type_formation }} en {{ $formation->nom }}
                </option>
            @endforeach
        </select>
        @error('formation_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Valider</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
</form>
