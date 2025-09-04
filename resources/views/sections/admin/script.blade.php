    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templates/templateAdmin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('templates/templateAdmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('templates/templateAdmin/js/main.js') }}"></script>
    <script>
    const themeLink = document.getElementById('theme-style');
    const switchBtn = document.getElementById('switch-theme');

    // Vérifie si un thème est déjà sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        themeLink.setAttribute('href', savedTheme);
    }

    switchBtn.addEventListener('click', function () {
        const current = themeLink.getAttribute('href');
        const defaultTheme = "{{ asset('templates/templateAdmin/css/style.css') }}";
        const altTheme = "{{ asset('templates/templateAdmin/css/style1.css') }}";

        const newTheme = current === defaultTheme ? altTheme : defaultTheme;
        themeLink.setAttribute('href', newTheme);
        localStorage.setItem('theme', newTheme);
    });
    </script>
