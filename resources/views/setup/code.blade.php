<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Setup</title>
    @include('sections.admin.head')
</head>
<body class="bg-dark text-white">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="bg-secondary rounded p-4">
            <h3 class="mb-3 text-center">Entrer le code secret</h3>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('setup.check') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="password" name="code" class="form-control" placeholder="Code secret" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Valider</button>
            </form>
        </div>
    </div>
</body>
</html>
