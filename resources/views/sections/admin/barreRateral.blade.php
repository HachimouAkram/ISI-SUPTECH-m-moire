<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3 text-center">
            <div style="line-height: 0.8;">
                <span style="font-size: 36px; font-weight: bold; color: #1d3c78; letter-spacing: 1px; font-family: 'Georgia', serif; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                    I S I
                </span><br>
                <span style="font-size: 20px; font-weight: bold; color: goldenrod; font-family: 'Georgia', serif; text-shadow: 1px 1px 1px rgba(0,0,0,0.2);">
                    SUPTECH
                </span>
            </div>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                @if(Auth::user()->photo)
                    <img class="rounded-circle"
                        src="{{ asset('storage/' . Auth::user()->photo) }}"
                        alt="Photo de profil"
                        style="width: 40px; height: 40px;">

                @else
                    <img class="rounded-circle"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nom . ' ' . Auth::user()->prenom) }}&background=0D8ABC&color=fff"
                        alt="Avatar par défaut"
                        style="width: 40px; height: 40px;">
                @endif
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{Auth::user()->nom}} {{Auth::user()->prenom}}</h6>
                @if (Auth::user()->role === 'etudiant')
                    <span>{{Auth::user()->role}}</span>
                @else
                    <span>{{Auth::user()->fonction}}</span>
                @endif
            </div>
        </div>
        <div class="navbar-nav w-100">
            @auth
            @can('voir_dashboard')
                <a href="{{ route('dashboard') }}" class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line me-2"></i>Tableau de bord
                </a>
            @endcan
            @can('voir_formations')
                <a href="{{ route('formations.index') }}" class="nav-item nav-link {{ request()->routeIs('formations.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open me-2"></i>Formations
                </a>
            @endcan

            @can('voir_classes')
                <a href="{{ route('classes.index') }}" class="nav-item nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                    <i class="fas fa-school me-2"></i>Classes
                </a>
            @endcan
            @can('voir_etudiants')
                <a href="{{ route('users.index') }}" class="nav-item nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i>Etudiants
                </a>
            @endcan
            @can('voir_admins')
                <a href="{{ route('admins.index') }}" class="nav-item nav-link {{ request()->routeIs('admins.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog me-2"></i>Administrateurs
                </a>
            @endcan
            @can('voir_paiements')
                <a href="{{ route('paiement.index') }}" class="nav-item nav-link {{ request()->routeIs('paiement.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd me-2"></i>Paiement effectué
                </a>
            @endcan
            @can('voir_echeances')
                @php
                    $hasInscription = \App\Models\Inscription::where('user_id', auth()->id())->where('statut', 'Valider')->exists();
                @endphp

                @if($hasInscription)
                    <a href="{{ route('echeance.index') }}" class="nav-item nav-link {{ request()->routeIs('echeance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i>Échéance
                    </a>
                    <a href="{{ route('recu.index') }}" class="nav-item nav-link {{ request()->routeIs('recu.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt me-2"></i>Historique des paiement
                    </a>
                @endif
            @endcan

            @can('gerer_programme')
                <a href="{{ route('programme.index') }}" class="nav-item nav-link {{ request()->routeIs('programme.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i>Année Académique
                </a>
            @endcan
            @can('gerer_programme')
                <a href="{{ route('listes.index') }}" class="nav-item nav-link {{ request()->routeIs('listes.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list me-2"></i> Listes des classes
                </a>
            @endcan
            @if(Auth::user()->fonction === 'Secretaire' || Auth::user()->fonction === 'Directeur' || Auth::user()->role === 'etudiant')
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('inscriptions.*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                        <i class="fas fa-file-signature me-2"></i>Inscriptions
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('inscriptions.index', ['statut' => 'Encours']) }}" class="dropdown-item">
                                    <i class="fas fa-spinner me-2 text-warning"></i>En cours
                                </a>
                                <a href="{{ route('inscriptions.index', ['statut' => 'Valider']) }}" class="dropdown-item">
                                    <i class="fas fa-check-circle me-2 text-success"></i>Validées
                                </a>
                                <a href="{{ route('inscriptions.index', ['statut' => 'Refuser']) }}" class="dropdown-item">
                                    <i class="fas fa-times-circle me-2 text-danger"></i>Refusées
                                </a>
                                <a href="{{ route('inscriptions.index', ['statut' => 'Terminer']) }}" class="dropdown-item">
                                    <i class="fas fa-flag-checkered me-2 text-primary"></i>Terminées
                                </a>
                            @elseif(Auth::user()->role === 'etudiant')
                                <a href="{{ route('inscriptions.mes') }}" class="dropdown-item">
                                    <i class="fas fa-user-check me-2 text-info"></i>Mes inscriptions
                                </a>
                                <a href="{{ route('inscriptions.create') }}" class="dropdown-item">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>Faire une inscription
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
            @endauth
        </div>
    </nav>
</div>
<!-- Sidebar End -->
