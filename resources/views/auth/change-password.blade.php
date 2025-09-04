<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Change Password Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-key me-2"></i>ISI SUPTECH</h3>
                            </a>
                            <h3>Changer le mot de passe</h3>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.change') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <!-- Nouveau mot de passe -->
                            <div class="form-floating mb-3">
                                <input id="password" type="password" name="password" required
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Nouveau mot de passe">
                                <label for="password">Nouveau mot de passe</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmer mot de passe -->
                            <div class="form-floating mb-4">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    class="form-control" placeholder="Confirmer mot de passe">
                                <label for="password_confirmation">Confirmer mot de passe</label>
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Changer le mot de passe</button>

                            <p class="text-center mb-0 text-white">
                                Retour à <a href="{{ route('login') }}">la connexion</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Change Password End -->
    </div>

    @include('sections.admin.script')
</body>

</html>
