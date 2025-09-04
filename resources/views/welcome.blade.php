<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.vitrine.head')
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    @include('sections.vitrine.navbar')
    <!-- Navbar End -->

    <!-- Carousel Start -->
    @include('sections.vitrine.carousel')
    <!-- Carousel End -->

    <!-- Service Start -->
    @include('sections.vitrine.service')
    <!-- Service End -->

    <!-- About Start -->
    @include('sections.vitrine.apropo')
    <!-- About End -->

    <!-- Categories Start -->
    @include('sections.vitrine.categorie')
    <!-- Categories Start -->

    <!-- Courses Start -->
    @include('sections.vitrine.cours')
    <!-- Courses End -->

    <!-- Team Start -->
    @include('sections.vitrine.equipe')
    <!-- Team End -->

    <!-- Testimonial Start -->
    @include('sections.vitrine.atestation')
    <!-- Testimonial End -->

    <!-- Footer Start -->
    @include('sections.vitrine.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templates/templateVitrine/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('templates/templateVitrine/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('templates/templateVitrine/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('templates/templateVitrine/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('templates/templateVitrine/js/main.js') }}"></script>
</body>

</html>
