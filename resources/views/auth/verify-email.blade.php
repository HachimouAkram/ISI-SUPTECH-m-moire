<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

<!DOCTYPE html>
<html lang="fr">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Début du Spinner de chargement -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Chargement...</span>
            </div>
        </div>
        <!-- Fin du Spinner -->

        <!-- Début du formulaire de vérification d'email -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-12 col-md-10 col-lg-7 col-xl-6">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">

                        <!-- En-tête avec logo et titre -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ url('/') }}">
                                <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>ISI Suptech</h3>
                            </a>
                            <h3>Vérification de l'email</h3>
                        </div>

                        <!-- Message d’information -->
                        <div class="alert alert-info text-sm">
                            Merci pour votre inscription ! Avant de continuer, veuillez vérifier votre adresse email
                            en cliquant sur le lien que nous venons de vous envoyer.<br>
                            Si vous n'avez pas reçu l'email, vous pouvez demander un nouvel envoi.
                        </div>

                        <!-- Message de confirmation si un nouvel email a été envoyé -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success text-sm">
                                Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
                            </div>
                        @endif

                        <!-- Boutons d'action -->
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <!-- Formulaire pour renvoyer le mail de vérification -->
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Renvoyer l'email de vérification
                                </button>
                            </form>

                            <!-- Formulaire de déconnexion -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-light">
                                    Se déconnecter
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Fin vérification email -->
    </div>

    <!-- Scripts JavaScript globaux -->
    @include('sections.admin.script')
</body>

</html>

