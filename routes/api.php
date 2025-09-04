
<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RappelController;
use App\Http\Controllers\RecuController;
use App\Http\Controllers\UserController;

// Routes pour le contrôleur ClasseController
Route::get('/classes', [ClasseController::class, 'index']);
Route::post('/classes', [ClasseController::class, 'store']);
Route::put('/classes/{id}', [ClasseController::class, 'update']);
Route::delete('/classes/{id}', [ClasseController::class, 'destroy']);
Route::get('/classes/{id}', [ClasseController::class, 'show'])->where('id', '[0-9]+');
Route::get('/classes/getformdetails', [ClasseController::class, 'getformdetails']);

// Routes pour le contrôleur DocumentController
Route::get('/documents', [DocumentController::class, 'index']);
Route::post('/documents', [DocumentController::class, 'store']);
Route::put('/documents/{id}', [DocumentController::class, 'update']);
Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
Route::get('/documents/{id}', [DocumentController::class, 'show'])->where('id', '[0-9]+');
Route::get('/documents/getformdetails', [DocumentController::class, 'getformdetails']);

// Routes pour le contrôleur FormationController
Route::get('/formations', [FormationController::class, 'index']);
Route::post('/formations', [FormationController::class, 'store']);
Route::put('/formations/{id}', [FormationController::class, 'update']);
Route::delete('/formations/{id}', [FormationController::class, 'destroy']);
Route::get('/formations/{id}', [FormationController::class, 'show'])->where('id', '[0-9]+');
Route::get('/formations/getformdetails', [FormationController::class, 'getformdetails']);

// Routes pour le contrôleur InscriptionController
Route::get('/inscriptions', [InscriptionController::class, 'index']);
Route::post('/inscriptions', [InscriptionController::class, 'store']);
Route::put('/inscriptions/{id}', [InscriptionController::class, 'update']);
Route::delete('/inscriptions/{id}', [InscriptionController::class, 'destroy']);
Route::get('/inscriptions/{id}', [InscriptionController::class, 'show'])->where('id', '[0-9]+');
Route::get('/inscriptions/getformdetails', [InscriptionController::class, 'getformdetails']);

// Routes pour le contrôleur PaiementController
Route::get('/paiements', [PaiementController::class, 'index']);
Route::post('/paiements', [PaiementController::class, 'store']);
Route::put('/paiements/{id}', [PaiementController::class, 'update']);
Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);
Route::get('/paiements/{id}', [PaiementController::class, 'show'])->where('id', '[0-9]+');
Route::get('/paiements/getformdetails', [PaiementController::class, 'getformdetails']);

// Routes pour le contrôleur ProfileController
Route::get('/profiles', [ProfileController::class, 'index']);
Route::post('/profiles', [ProfileController::class, 'store']);
Route::put('/profiles/{id}', [ProfileController::class, 'update']);
Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']);
Route::get('/profiles/{id}', [ProfileController::class, 'show'])->where('id', '[0-9]+');
Route::get('/profiles/getformdetails', [ProfileController::class, 'getformdetails']);

// Routes pour le contrôleur RappelController
Route::get('/rappels', [RappelController::class, 'index']);
Route::post('/rappels', [RappelController::class, 'store']);
Route::put('/rappels/{id}', [RappelController::class, 'update']);
Route::delete('/rappels/{id}', [RappelController::class, 'destroy']);
Route::get('/rappels/{id}', [RappelController::class, 'show'])->where('id', '[0-9]+');
Route::get('/rappels/getformdetails', [RappelController::class, 'getformdetails']);

// Routes pour le contrôleur RecuController
Route::get('/recus', [RecuController::class, 'index']);
Route::post('/recus', [RecuController::class, 'store']);
Route::put('/recus/{id}', [RecuController::class, 'update']);
Route::delete('/recus/{id}', [RecuController::class, 'destroy']);
Route::get('/recus/{id}', [RecuController::class, 'show'])->where('id', '[0-9]+');
Route::get('/recus/getformdetails', [RecuController::class, 'getformdetails']);

// Routes pour le contrôleur UserController
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
Route::get('/users/getformdetails', [UserController::class, 'getformdetails']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

