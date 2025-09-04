<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Email Verification Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>ISI Suptech</h3>
                            </a>
                            <h3>Vérification de l'email</h3>
                        </div>

                        <p>Un code vous a été envoyé à : <strong>{{ session('email') ?? old('email') }}</strong></p>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form method="POST" action="{{ route('verify.code') }}">
                            @csrf

                            <!-- Email transmis en champ caché -->
                            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                            <div class="form-floating mb-3">
                                <input type="text"
                                    class="form-control @error('code') is-invalid @enderror"
                                    name="code"
                                    required
                                    placeholder="Code de vérification">
                                <label for="code">Code de vérification</label>
                                @error('code')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Vérifier</button>
                        </form>


                        <!-- ✅ Nouveau formulaire séparé -->
                        <form method="POST" action="{{ route('resend.code') }}">
                            @csrf
                            <input type="hidden" name="current_email" value="{{ session('email') ?? old('email') }}">

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('new_email') is-invalid @enderror" id="newEmail" name="new_email" placeholder="Nouvel email (facultatif)">
                                <label for="newEmail">Nouvel email (facultatif)</label>
                                <small class="text-muted d-block mt-1">
                                    Vous avez mal saisi votre email ? Entrez ici la bonne adresse pour la corriger. Le système mettra à jour votre compte et enverra un nouveau code à cette adresse.
                                </small>
                                @error('new_email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-outline-primary w-100">Renvoyer le code</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Email Verification End -->
    </div>

    <!-- JavaScript Libraries -->
    @include('sections.admin.script')
</body>
</html>
