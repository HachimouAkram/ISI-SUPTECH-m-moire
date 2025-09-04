<!DOCTYPE html>
<html lang="en">

<head>
    @include('sections.admin.head')
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Début du chargeur (spinner) -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!--barre latérale -->
        @include('sections.admin.barreRateral')
        <!-- Sidebar End -->


        <!-- Début du contenu -->
        <div class="content">
            <!-- Navbar Start -->
            @include('sections.admin.navbar')
            <!-- Navbar End -->

            <!-- Début des ventes et des revenus -->
            @include('sections.admin.revenu')
            <!-- Sale & Revenue End -->

            <!-- Début du graphique des ventes -->
            @include('sections.admin.graphique')
            <!-- Fin du graphique des ventes -->

            <!-- Recent Sales Start -->
            @include('sections.admin.sale')
            <!-- Recent Sales End -->

            <!-- Widgets Start -->
            @include('sections.admin.widget')
            <!-- Widgets End -->

            <!-- Footer Start -->
            @include('sections.admin.footer')
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    @include('sections.admin.script')
    <!-- Template Javascript -->
</body>

</html>
