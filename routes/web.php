<?php

use App\Http\Controllers\PatientsAddressController;
use App\Http\Controllers\PatientsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('patients', PatientsController::class);
    Route::get('/patients/all', [PatientsController::class, 'getPatients'])->name('patients.getPatients');
    Route::get('/patients/search/{cep}', [PatientsAddressController::class, 'searchCep'])->name('patients.search-cep');
    Route::post('/patients/uploadPatientImage', [PatientsController::class, 'uploadPatientImage'])->name('patients.uploadPatientImage');
    Route::post('/patients/export/{type}', [PatientsController::class, 'export'])->name('patients.export');
    Route::post('patients/import', [PatientsController::class, 'import'])->name('patients.import');
});

