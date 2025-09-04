<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\RappelController;
use App\Http\Controllers\RecuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProgrammeAcademiqueController;
use App\Http\Controllers\VerificationController;
use App\Models\Formation;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/programme-academique/create', [ProgrammeAcademiqueController::class, 'create'])->name('programme_academique.create');
Route::post('/programme-academique/store', [ProgrammeAcademiqueController::class, 'store'])->name('programme_academique.store');

// Vérification du code
Route::get('/verify-code', [VerificationController::class, 'show'])->name('verify.code');
Route::post('/verify-code', [VerificationController::class, 'verify']);
Route::post('/resend-code', [VerificationController::class, 'resend'])->name('resend.code');


// Afficher le formulaire de changement de mot de passe
Route::get('/password/change-notice', [PasswordController::class, 'notice'])
     ->name('password.change.notice');

// Soumettre le formulaire (POST)
Route::post('/password/change', [PasswordController::class, 'update'])
     ->name('password.change');

// Créer un admin
Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// Routes pour le contrôleur ClasseController
Route::get('/classes', [ClasseController::class, 'index'])->name('classes.index');
Route::post('/classes', [ClasseController::class, 'store'])->name('classes.store');
Route::put('/classes/{classe}', [ClasseController::class, 'update'])->name('classes.update');
Route::delete('/classes/{classe}', [ClasseController::class, 'destroy'])->name('classes.destroy');
Route::get('/classes/{classe}', [ClasseController::class, 'show'])->where('id', '[0-9]+')->name('classes.show');
Route::get('/classes/getformdetails', [ClasseController::class, 'getformdetails'])->name('classes.getformadetails');
Route::get('/formations/{id}/classes', [ClasseController::class, 'getClassesByFormation']);

// Routes pour le contrôleur DocumentController
Route::middleware(['auth'])->group(function () {
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    Route::get('/mes-documents/modifier', [DocumentController::class, 'editMesDocuments'])->name('mes.documents.edit');
    Route::post('/mes-documents/{document}', [DocumentController::class, 'updateMesDocument'])->name('mes.documents.update');
});
Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
Route::get('/documents/{document}', [DocumentController::class, 'show'])->where('id', '[0-9]+')->name('documents.show');
Route::get('/documents/getformdetails', [DocumentController::class, 'getformdetails'])->name('documents.getformdetails');


// Routes pour le contrôleur FormationController
Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
Route::post('/formations', [FormationController::class, 'store'])->name('formations.store');
Route::put('/formations/{formation}', [FormationController::class, 'update'])->name('formations.update');
Route::delete('/formations/{formation}', [FormationController::class, 'destroy'])->name('formations.destroy');
Route::get('/formations/{formation}', [FormationController::class, 'show'])->where('id', '[0-9]+')->name('formations.show');
Route::get('/formations/getformdetails', [FormationController::class, 'getformdetails'])->name('formations.getformdetails');

// Routes pour le contrôleur InscriptionController
Route::post('/inscriptions/{id}/rappel', [InscriptionController::class, 'envoyerRappel'])->name('inscriptions.rappel');
Route::get('/inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
Route::post('/inscriptions', [InscriptionController::class, 'store'])->name('inscriptions.store');
Route::put('/inscriptions/{inscription}', [InscriptionController::class, 'update'])->name('inscriptions.update');
Route::delete('/inscriptions/{inscription}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');
Route::get('/inscriptions/{inscription}', [InscriptionController::class, 'show'])->where('id', '[0-9]+')->name('inscriptions.show');
Route::get('/inscriptions/getformdetails', [InscriptionController::class, 'getformdetails'])->name('inscriptions.getformdetails');
Route::patch('/inscriptions/{id}/valider', [InscriptionController::class, 'valider'])->name('inscriptions.valider');
Route::patch('/inscriptions/{id}/refuser', [InscriptionController::class, 'refuser'])->name('inscriptions.refuser');
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-inscriptions', [InscriptionController::class, 'mesInscriptions'])->name('inscriptions.mes');
    Route::get('/inscriptions/{inscription}/edit', [InscriptionController::class, 'edit'])->name('inscriptions.edit');
});
Route::get('/inscription/confirmation', function () {
    return view('pages.admin.inscription.confirmation');
})->name('inscription.confirmation');
Route::get('/inscription/create', [InscriptionController::class, 'create'])->name('inscriptions.create');
Route::get('/api/formations/{id}/classes', function ($id) {
    return \App\Models\Classe::where('formation_id', $id)
        ->where('etat', true) // facultatif : pour ne montrer que les classes actives
        ->orderBy('niveau')
        ->get();
});
Route::get('/test-form', [FormationController::class, 'showTestForm']);

// Route paiement
// Strip
Route::get('/paiement/stripe/{type}', [PaiementController::class, 'createStripePaiement'])->name('stripe.create');
Route::get('/paiement/stripe/success/{type}', [PaiementController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/paiement/stripe/cancel', [PaiementController::class, 'stripeCancel'])->name('stripe.cancel');
//Choix
Route::get('/paiement/choix', [PaiementController::class, 'choixType'])->name('paiement.choix');
Route::get('/paiement/{type}', [PaiementController::class, 'afficherPaiement'])->name('paiement.page');
// Routes PayPal
Route::middleware(['auth'])->group(function () {
    Route::get('/paiement/paypal/{type}', [PaiementController::class, 'createPaypalPaiement'])->name('paypal.create');
    Route::post('/paiement/paypal/success', [PaiementController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paiement/paypal/cancel', [PaiementController::class, 'paypalCancel'])->name('paypal.cancel');
    // Routes de compatibilité (pour votre vue actuelle)
    //Route::get('/paiement/success', [PaiementController::class, 'paypalSuccess'])->name('paiement.success');
    //Route::get('/paiement/cancel', [PaiementController::class, 'paypalCancel'])->name('paiement.cancel');
    //Route::post('/paypal/capture', [PaiementController::class, 'captureOrder'])->name('paypal.order.capture');

});

//Normal
Route::get('/paiements', [PaiementController::class, 'index'])->name('paiement.index');
Route::post('/paiements', [PaiementController::class, 'store'])->name('paiement.store');
Route::put('/paiements/{paiement}', [PaiementController::class, 'update'])->name('paiement.update');
Route::delete('/paiements/{paiement}', [PaiementController::class, 'destroy'])->name('paiement.destroy');
Route::get('/paiements/{paiement}', [PaiementController::class, 'show'])->where('id', '[0-9]+')->name('paiement.show');
Route::get('/paiements/getformdetails', [PaiementController::class, 'getformdetails'])->name('paiement.getformdetails');

// Routes pour le contrôleur ProfileController
Route::get('/profiles', [ProfileController::class, 'index']);
Route::post('/profiles', [ProfileController::class, 'store']);
Route::put('/profiles/{profile}', [ProfileController::class, 'update']);
Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);
Route::get('/profiles/{profile}', [ProfileController::class, 'show'])->where('id', '[0-9]+');
Route::get('/profiles/getformdetails', [ProfileController::class, 'getformdetails']);

// Routes pour le contrôleur RappelController
Route::get('/rappels', [RappelController::class, 'index']);
Route::post('/rappels', [RappelController::class, 'store']);
Route::put('/rappels/{rappel}', [RappelController::class, 'update']);
Route::delete('/rappels/{rappel}', [RappelController::class, 'destroy']);
Route::get('/rappels/{rappel}', [RappelController::class, 'show'])->where('id', '[0-9]+');
Route::get('/rappels/getformdetails', [RappelController::class, 'getformdetails']);

// Routes pour le contrôleur RecuController
Route::get('/recus', [RecuController::class, 'index'])->name('recu.index');
Route::post('/recus', [RecuController::class, 'store']);
Route::put('/recus/{recu}', [RecuController::class, 'update']);
Route::delete('/recus/{recu}', [RecuController::class, 'destroy']);
Route::get('/recus/{id}', [RecuController::class, 'show'])->where('id', '[0-9]+');
Route::get('/recus/getformdetails', [RecuController::class, 'getformdetails']);

// Routes pour le contrôleur UserController
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{user}', [UserController::class, 'show'])->where('id', '[0-9]+')->name('users.show');
Route::get('/users/getformdetails', [UserController::class, 'getformdetails'])->name('users.getformdetails');

//Npotification
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
require __DIR__.'/auth.php';

// Vitrine
Route::get('/contact', function () {
    return view('pages.vitrine.contact');
})->name('contact');

// À propos
Route::get('/apropo', function () {
    return view('pages.vitrine.apropo');
})->name('apropo');

// Cours
Route::get('/cours', function () {
    return view('pages.vitrine.cours');
})->name('cours');

// Équipe
Route::get('/team', function () {
    return view('pages.team');
})->name('team');

// Témoignages
Route::get('/testimonial', function () {
    return view('pages.testimonial');
})->name('testimonial');

// Page 404 personnalisée
Route::get('/404', function () {
    return view('pages.404');
})->name('404');

Route::get('/echeances', [App\Http\Controllers\PaiementController::class, 'afficherEcheances'])->middleware('auth')->name('echeance.index');
