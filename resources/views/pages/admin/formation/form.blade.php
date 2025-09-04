<div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" class="form-control @error('nom') is-invalid @enderror"
        id="nom" name="nom"
        value="{{ old('nom', $formation->nom ?? '') }}" required>
    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="type_formation" class="form-label">Niveau</label>
    <select class="form-select @error('type_formation') is-invalid @enderror" id="type_formation" name="type_formation" required>
        <option value="">-- Choisir --</option>
        @foreach(['Master', 'Licence', 'BTS'] as $type)
            <option value="{{ $type }}" {{ (old('type_formation', $formation->type_formation ?? '') == $type) ? 'selected' : '' }}>{{ $type }}</option>
        @endforeach
    </select>
    @error('type_formation')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="duree" class="form-label">Durée</label>
    <select class="form-select @error('duree') is-invalid @enderror" id="duree" name="duree" required>
        <option value="">-- Choisir --</option>
        @foreach(['3 ans', '2 ans'] as $duree)
            <option value="{{ $duree }}" {{ (old('duree', $formation->duree ?? '') == $duree) ? 'selected' : '' }}>{{ $duree }}</option>
        @endforeach
    </select>
    @error('duree')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="domaine" class="form-label">Département</label>
    <select class="form-select @error('domaine') is-invalid @enderror" id="domaine" name="domaine" required>
        <option value="">-- Choisir --</option>
        @foreach(['Informatique', 'Gestion'] as $domaine)
            <option value="{{ $domaine }}" {{ (old('domaine', $formation->domaine ?? '') == $domaine) ? 'selected' : '' }}>{{ $domaine }}</option>
        @endforeach
    </select>
    @error('domaine')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $formation->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
