<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <!-- Nom -->
    <div class="form-floating mb-3">
        <input id="nom" type="text" name="nom" value="{{ old('nom', $user->nom) }}"
               class="form-control @error('nom') is-invalid @enderror" placeholder="Nom" required autofocus>
        <label for="nom">Nom</label>
        @error('nom')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Prénom -->
    <div class="form-floating mb-3">
        <input id="prenom" type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
               class="form-control @error('prenom') is-invalid @enderror" placeholder="Prénom" required>
        <label for="prenom">Prénom</label>
        @error('prenom')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-floating mb-3">
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
               class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
        <label for="email">Adresse Email</label>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Téléphone -->
    <div class="form-floating mb-3">
        <input id="telephone" type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}"
               class="form-control @error('telephone') is-invalid @enderror" placeholder="Téléphone" required>
        <label for="telephone">Téléphone</label>
        @error('telephone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Sexe -->
    <div class="mb-3">
        <select id="sexe" name="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
            <option value="" disabled {{ old('sexe', $user->sexe) ? '' : 'selected' }}>Sexe</option>
            <option value="Homme" {{ old('sexe', $user->sexe) == 'Homme' ? 'selected' : '' }}>Masculin</option>
            <option value="Femme" {{ old('sexe', $user->sexe) == 'Femme' ? 'selected' : '' }}>Féminin</option>
            <option value="Autre" {{ old('sexe', $user->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
        </select>
        @error('sexe')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Date de naissance -->
    <div class="form-floating mb-3">
        <input id="date_naissance" type="date" name="date_naissance"
               value="{{ old('date_naissance', $user->date_naissance) }}"
               class="form-control @error('date_naissance') is-invalid @enderror" placeholder="Date de naissance" required>
        <label for="date_naissance">Date de naissance</label>
        @error('date_naissance')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Photo -->
    <div class="mb-3">
        <label for="photoInput" class="form-label">Photo de profil</label>
        <input type="file" name="photo" id="photoInput" accept="image/*" class="form-control">
        <div class="mt-2">
            <img id="photoPreview" style="max-width: 100%; display: none;">
        </div>
        <input type="hidden" name="photo" id="photoCropped">
        @error('photo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Enregistrer</button>
</form>

<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
const photoInput = document.getElementById('photoInput');
const photoPreview = document.getElementById('photoPreview');

photoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        photoPreview.src = e.target.result;
        photoPreview.style.display = 'block';

        if (cropper) cropper.destroy();

        cropper = new Cropper(photoPreview, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
        });
    };
    reader.readAsDataURL(file);
});

const form = document.querySelector('form');
form.addEventListener('submit', function(e) {
    if (cropper) {
        e.preventDefault();
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        canvas.toBlob(function(blob) {
            const file = new File([blob], 'photo.png', { type: 'image/png' });
            const dt = new DataTransfer();
            dt.items.add(file);
            photoInput.files = dt.files;
            form.submit();
        }, 'image/png');
    }
});
</script>
