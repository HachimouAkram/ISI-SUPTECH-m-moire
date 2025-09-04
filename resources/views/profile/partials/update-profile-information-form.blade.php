<section>
    <header class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations personnelles') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour les informations de votre compte.") }}
        </p>
    </header>

    <!-- Formulaire mise à jour -->
    <form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <x-input-label for="prenom" :value="__('Prénom')" />
                <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full"
                              :value="old('prenom', $user->prenom)" required autofocus />
                <x-input-error class="mt-1" :messages="$errors->get('prenom')" />
            </div>

            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full"
                              :value="old('nom', $user->nom)" required />
                <x-input-error class="mt-1" :messages="$errors->get('nom')" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              :value="old('email', $user->email)" required />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Un nouvel email de vérification a été envoyé.') }}
                        </p>
                    @endif
                @endif
            </div>

            <div>
                <x-input-label for="telephone" :value="__('Téléphone')" />
                <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full"
                              :value="old('telephone', $user->telephone)" required />
                <x-input-error class="mt-1" :messages="$errors->get('telephone')" />
            </div>

            <div>
                <x-input-label for="sexe" :value="__('Sexe')" />
                <select id="sexe" name="sexe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="Homme" {{ old('sexe', $user->sexe) === 'Homme' ? 'selected' : '' }}>Homme</option>
                    <option value="Femme" {{ old('sexe', $user->sexe) === 'Femme' ? 'selected' : '' }}>Femme</option>
                    <option value="Autre" {{ old('sexe', $user->sexe) === 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>
                <x-input-error class="mt-1" :messages="$errors->get('sexe')" />
            </div>

            <div>
                <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                <x-text-input id="date_naissance" name="date_naissance" type="date" class="mt-1 block w-full"
                              :value="old('date_naissance', $user->date_naissance)" required />
                <x-input-error class="mt-1" :messages="$errors->get('date_naissance')" />
            </div>

            <div>
                <x-input-label for="photo" :value="__('Photo de profil')" />
                <input type="file" name="photo" id="photoInput" accept="image/*" class="mt-1 block w-full">
                <div class="mt-2">
                    <img id="photoPreview" style="max-width: 100%; display: none;">
                </div>
                <input type="hidden" name="photo" id="photoCropped">
                <x-input-error class="mt-1" :messages="$errors->get('photo')" />
            </div>

        </div>

        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
<!-- Ajouter Cropper.js -->
<!-- Cropper.js CSS et JS -->
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
