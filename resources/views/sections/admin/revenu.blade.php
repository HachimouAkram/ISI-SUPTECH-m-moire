<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Nombre d'étudiants</p>
                    <h6 class="mb-0">{{ $nbEtudiants }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Nombre de formations</p>
                    <h6 class="mb-0">{{ $nbFormations }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-clipboard-list fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Inscriptions cette année</p>
                    <h6 class="mb-0">{{ $nbInscriptions }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chalkboard fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Nombre de classes</p>
                    <h6 class="mb-0">{{ $nbClasses }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
