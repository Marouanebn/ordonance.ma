<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PharmacienController;
use App\Http\Controllers\LaboratoireController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\DemandeLaboratoireController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Patient routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/patient/profile', [PatientController::class, 'profile']);
        Route::put('/patient/profile', [PatientController::class, 'updateProfile']);
        Route::get('/patient/ordonnances', [PatientController::class, 'ordonnances']);
    });

    // Medecin routes
    Route::middleware('role:medecin')->group(function () {
        Route::get('/medecin/profile', [MedecinController::class, 'profile']);
        Route::put('/medecin/profile', [MedecinController::class, 'updateProfile']);
        Route::get('/medecin/patients', [MedecinController::class, 'patients']);
        Route::get('/medecin/ordonnances', [OrdonnanceController::class, 'index']);
        Route::post('/medecin/ordonnances', [OrdonnanceController::class, 'store']);
        Route::get('/medecin/ordonnances/{ordonnance}', [OrdonnanceController::class, 'show']);
        Route::put('/medecin/ordonnances/{ordonnance}', [OrdonnanceController::class, 'update']);
        Route::delete('/medecin/ordonnances/{ordonnance}', [OrdonnanceController::class, 'destroy']);
        Route::post('/medecin/ordonnances/{ordonnance}/medicaments', [OrdonnanceController::class, 'ajouterMedicament']);
    });

    // Pharmacien routes
    Route::middleware('role:pharmacien')->group(function () {
        Route::get('/pharmacien/profile', [PharmacienController::class, 'profile']);
        Route::put('/pharmacien/profile', [PharmacienController::class, 'updateProfile']);
        Route::get('/pharmacien/medicaments', [MedicamentController::class, 'index']);
        Route::get('/pharmacien/demandes-laboratoire', [DemandeLaboratoireController::class, 'index']);
        Route::post('/pharmacien/demandes-laboratoire', [DemandeLaboratoireController::class, 'store']);
        Route::get('/pharmacien/demandes-laboratoire/{demande}', [DemandeLaboratoireController::class, 'show']);
        Route::get('/pharmacien/patients/search', [PharmacienController::class, 'searchPatients']);
        Route::get('/pharmacien/patients/{patient}/ordonnances', [PharmacienController::class, 'patientOrdonnances']);
        Route::put('/pharmacien/ordonnances/{ordonnance}/status', [OrdonnanceController::class, 'updateStatus']);
        Route::get('/pharmacien/ordonnances/validated', [OrdonnanceController::class, 'validatedByPharmacien']);
    });

    // Laboratoire routes
    Route::middleware('role:laboratoire')->group(function () {
        Route::get('/laboratoire/profile', [LaboratoireController::class, 'profile']);
        Route::put('/laboratoire/profile', [LaboratoireController::class, 'updateProfile']);
        Route::get('/laboratoire/demandes', [DemandeLaboratoireController::class, 'laboratoireDemandes']);
        Route::get('/laboratoire/demandes/{demande}', [DemandeLaboratoireController::class, 'show']);
        Route::put('/laboratoire/demandes/{demande}', [DemandeLaboratoireController::class, 'updateStatus']);
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AuthController::class, 'allUsers']);
        Route::get('/admin/statistics', [AuthController::class, 'statistics']);

        // Admin CRUD routes for all models
        Route::apiResource('/admin/medecins', MedecinController::class);
        Route::apiResource('/admin/pharmaciens', PharmacienController::class);
        Route::apiResource('/admin/laboratoires', LaboratoireController::class);
        Route::apiResource('/admin/medicaments', MedicamentController::class);
        Route::apiResource('/admin/ordonnances', OrdonnanceController::class);
        Route::apiResource('/admin/demandes-laboratoire', DemandeLaboratoireController::class);
    });

    // General CRUD routes (with role-based access)
    Route::get('/medecins', [MedecinController::class, 'index']);
    Route::get('/medecins/{medecin}', [MedecinController::class, 'show']);
    Route::get('/pharmaciens', [PharmacienController::class, 'index']);
    Route::get('/pharmaciens/{pharmacien}', [PharmacienController::class, 'show']);
    Route::get('/laboratoires', [LaboratoireController::class, 'index']);
    Route::get('/laboratoires/{laboratoire}', [LaboratoireController::class, 'show']);
    Route::get('/medicaments', [MedicamentController::class, 'index']);
    Route::get('/medicaments/{medicament}', [MedicamentController::class, 'show']);
    Route::post('/medicaments', [MedicamentController::class, 'store']);
    Route::get('/ordonnances', [OrdonnanceController::class, 'index']);
    Route::get('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'show']);
    Route::get('/ordonnances/{ordonnance}/download', [OrdonnanceController::class, 'downloadPdf']);
    Route::post('/ordonnances/bulk-download', [OrdonnanceController::class, 'downloadBulkPdf']);
    Route::get('/demandes-laboratoire', [DemandeLaboratoireController::class, 'index']);
    Route::get('/demandes-laboratoire/{demande}', [DemandeLaboratoireController::class, 'show']);
    Route::get('/patients', [PatientController::class, 'index']);
});
