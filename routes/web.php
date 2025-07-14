<?php

use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ResguardoController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TipoMaterialController;
use App\Http\Controllers\TipoMovimientoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Autenticación
Auth::routes();

// Rutas protegidas por middleware 'auth'
Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Dashboards según rol
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/encargado/dashboard', [HomeController::class, 'encargadoDashboard'])->name('encargado.dashboard');
    Route::get('/empleado/dashboard', [HomeController::class, 'empleadoDashboard'])->name('empleado.dashboard');

    // Recursos principales
    Route::resource('devolucions', DevolucionController::class);
    Route::resource('resguardos', ResguardoController::class);
    Route::resource('ingresos', IngresoController::class);
    Route::resource('prestamos', PrestamoController::class);
    Route::resource('almacens', AlmacenController::class);
    Route::resource('ubicacions', UbicacionController::class);
    Route::resource('movimientos', MovimientoController::class);
    Route::resource('tipo-movimientos', TipoMovimientoController::class);
    Route::resource('unidad-medidas', UnidadMedidaController::class);
    Route::resource('tipo-materials', TipoMaterialController::class);
    Route::resource('materials', MaterialController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('stocks', StockController::class);

    // Rutas especiales para préstamos y devoluciones
    Route::prefix('prestamos')->group(function () {
        Route::get('/por-user/{user}', [PrestamoController::class, 'porUser'])
            ->name('prestamos.por-user');

        Route::get('/{id}/datos-devolucion', [PrestamoController::class, 'datosDevolucion'])
            ->name('prestamos.datos-devolucion');

        Route::post('/{prestamo}/devolucion', [PrestamoController::class, 'procesarDevolucion'])
            ->name('prestamos.procesar-devolucion');
    });

    // Ruta auxiliar para obtener préstamos por user
    Route::get('/prestamos-por-user/{user}', function ($userId) {
        return App\Models\Prestamo::where('personal_id', $userId)->get();
    });

    // Almacena devoluciones desde formulario directo
    Route::post('/devolucions', [DevolucionController::class, 'store'])->name('devolucions.store');
});
Route::resource('categorias', CategoriaController::class)->names([
    'index' => 'categorias.index',
]);

Route::resource('tipo-material', TipoMaterialController::class)->names([
    'index' => 'tipo-material.index',
]);

Route::resource('unidad-medida', UnidadMedidaController::class)->names([
    'index' => 'unidad-medida.index',
]);


Route::middleware(['auth'])->group(function() {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
});

Route::get('/materials/{material}/stock', [MaterialController::class, 'getStock'])->name('materials.stock');
//Route::get('/materiales/{id}/stock', [MaterialController::class, 'getStock'])->name('materiales.stock');
//Route::resource('', ::class)->names([])


Route::resource('users', UserController::class)->middleware('auth');
// Si quieres proteger las rutas con autenticación
// Perfil routes
Route::prefix('perfil')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('perfil.show');
    Route::put('/update', [ProfileController::class, 'update'])->name('perfil.update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('perfil.update-password');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// routes/web.php
Route::group(['middleware' => ['auth', 'role:admin,encargado']], function() {
    // Rutas protegidas para admin/encargado
});

// Protección por roles en grupos de rutas
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    // Otras rutas exclusivas para admin
});

Route::middleware(['auth', 'role:encargado'])->group(function () {
    Route::get('/encargado/dashboard', [HomeController::class, 'encargadoDashboard'])->name('encargado.dashboard');
    // Otras rutas exclusivas para encargado
});

Route::middleware(['auth', 'role:empleado'])->group(function () {
    Route::get('/empleado/dashboard', [HomeController::class, 'empleadoDashboard'])->name('empleado.dashboard');
    // Otras rutas exclusivas para empleado
});
Route::middleware('auth')->group(function () {
    Route::prefix('perfil')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('perfil.show');
        Route::get('/cambiar-contrasena', [ProfileController::class, 'showChangePasswordForm'])->name('perfil.change-password');
        Route::post('/cambiar-contrasena', [ProfileController::class, 'updatePassword'])->name('perfil.update-password');
    });
});
// En routes/web.php
Route::get('/materiales/eliminados', [MaterialController::class, 'eliminados'])
    ->name('materials.eliminados');
Route::put('/materiales/{id}/restaurar', [MaterialController::class, 'restaurar'])
    ->name('materials.restaurar');

//elementos nuevos para la implementacion de archivos csv
Route::prefix('material')->group(function () {
    // ... otras rutas existentes

    // Rutas para importación
    Route::get('/import', [MaterialController::class, 'showImportForm'])
        ->name('material.import.form');

    Route::get('/import/template', [MaterialController::class, 'downloadTemplate'])
        ->name('materials.import.template');

    Route::post('/import', [MaterialController::class, 'processImport'])
        ->name('materials.import.process');


});

//Route::get('/test-import', function () {
//    return 'Rutas funcionando hola';
//});

Route::prefix('user')->group(function () {

    Route::get('/import', [UserController::class, 'showImportForm'])->name('user.import.form');
    Route::get('/import/template', [UserController::class, 'downloadTemplate'])->name('users.import.template');
    Route::post('/import', [UserController::class, 'processImport'])->name('users.import.process');
});
