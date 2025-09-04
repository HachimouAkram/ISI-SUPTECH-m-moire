<!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control bg-dark border-0" type="search" placeholder="Search">
                </form>
                <button id="switch-theme" class="btn btn-sm btn-outline-primary me-3 d-flex align-items-center gap-2" title="Changer la couleur">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notification</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0" style="max-height: 300px; overflow-y: auto;">
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.show', $notification->id) }}" class="dropdown-item">
                                    <h6 class="fw-normal mb-0">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                                <hr class="dropdown-divider">
                            @empty
                                <div class="dropdown-item text-center text-muted">Aucune notification</div>
                            @endforelse
                            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center">Voir toutes les notifications</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @if(Auth::user()->photo)
                            <img class="rounded-circle me-lg-2"
                                src="{{ asset('storage/' . Auth::user()->photo) }}"
                                alt="Photo de profil"
                                style="width: 40px; height: 40px;">
                            @else
                                <img class="rounded-circle me-lg-2"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nom . ' ' . Auth::user()->prenom) }}&background=0D8ABC&color=fff"
                                    alt="Avatar par défaut"
                                    style="width: 40px; height: 40px;">
                            @endif
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->prenom }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                Mon profil
                            </a>
                            <a href="#" class="dropdown-item">Paramètres</a>
                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                    Déconnexion
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
