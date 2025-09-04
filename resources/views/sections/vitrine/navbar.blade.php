<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img src="{{ asset('templates/templateVitrine/img/isi.png') }}" alt="Logo ISI" style="height: 40px;" class="me-2">
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
            <a href="{{ url('/apropo') }}" class="nav-item nav-link {{ request()->is('about') ? 'active' : '' }}">À propos</a>
            <a href="{{ url('/cours') }}" class="nav-item nav-link {{ request()->is('courses') ? 'active' : '' }}">Formation</a>
            <a href="{{ url('/contact') }}" class="nav-item nav-link {{ request()->is('contact') ? 'active' : '' }}">Contactez-Nous</a>
        </div>

        <div class="d-none d-lg-flex">
            <a href="{{route('login')}}" class="btn btn-outline-primary py-4 px-5">
                Se connecter
            </a>
            <a href="{{route('register') }}" class="btn btn-outline-primary py-4 px-5">
                S'inscrire
            </a>
        </div>
    </div>
</nav>
