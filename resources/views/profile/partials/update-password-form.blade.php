<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <!-- Mot de passe actuel -->
    <div class="form-floating mb-3 position-relative">
        <input id="current_password" type="password" name="current_password"
               class="form-control @error('current_password') is-invalid @enderror"
               placeholder="Mot de passe actuel" autocomplete="current-password" required>
        <label for="current_password">Mot de passe actuel</label>
        <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
              onclick="togglePassword('current_password', 'currentPasswordIcon')">
            <i class="fas fa-eye" id="currentPasswordIcon"></i>
        </span>
        @error('current_password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Nouveau mot de passe -->
    <div class="form-floating mb-3 position-relative">
        <input id="new_password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Nouveau mot de passe" autocomplete="new-password" required>
        <label for="new_password">Nouveau mot de passe</label>
        <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
              onclick="togglePassword('new_password', 'newPasswordIcon')">
            <i class="fas fa-eye" id="newPasswordIcon"></i>
        </span>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirmer mot de passe -->
    <div class="form-floating mb-4 position-relative">
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="form-control @error('password_confirmation') is-invalid @enderror"
               placeholder="Confirmer le mot de passe" autocomplete="new-password" required>
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
              onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
            <i class="fas fa-eye" id="confirmPasswordIcon"></i>
        </span>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Enregistrer</button>

    @if (session('status') === 'password-updated')
        <p class="text-center text-success mt-2">Mot de passe mis à jour avec succès.</p>
    @endif
</form>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
