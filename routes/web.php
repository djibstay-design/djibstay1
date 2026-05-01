<?php

use App\Http\Controllers\Admin\AvisController as AdminAvisController;
use App\Http\Controllers\Admin\ChambreController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\HotelHotelImageController;
use App\Http\Controllers\Admin\HotelImageController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TypeChambreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationPaymentController;
use App\Http\Controllers\ReservationStatusController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\WelcomeController::class)->name('home');

// Pages statiques
Route::get('/a-propos', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('pages.contact.submit');
Route::post('/contact/partenaire', [PageController::class, 'partenaireSubmit'])->name('pages.partenaire.submit');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}/paiement', [ReservationPaymentController::class, 'show'])->name('reservations.payment.show');
Route::post('/reservations/{reservation}/paiement', [ReservationPaymentController::class, 'store'])->name('reservations.payment.store');
Route::get('/reservations/confirmation/{reservation}', [ReservationController::class, 'confirmation'])->name('reservations.confirmation');
Route::get('/reservations/statut', [ReservationStatusController::class, 'show'])->name('reservations.status');
Route::patch('/reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])->name('reservations.annuler');
Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/inscription', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/inscription', [LoginController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/mon-compte', [LoginController::class, 'monCompte'])->name('client.compte');
    Route::get('/mes-reservations', [LoginController::class, 'mesReservations'])->name('client.reservations');
});

// ══════════════════════════════════════
//  ESPACE SUPER ADMIN
// ══════════════════════════════════════
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::middleware('superadmin')->resource('users', UserController::class)->parameters(['users' => 'user']);
    
    // Types de paiement
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class)->except(['show']);
    Route::patch('payment-methods/{payment_method}/toggle', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleActive'])->name('payment-methods.toggle');

    Route::resource('hotels', AdminHotelController::class)->except(['show']);
    Route::get('hotels/{hotel}/images', [HotelHotelImageController::class, 'index'])->name('hotels.images.index');
    Route::post('hotels/{hotel}/images', [HotelHotelImageController::class, 'store'])->name('hotels.images.store');
    Route::patch('hotels/{hotel}/images/{image}/main', [HotelHotelImageController::class, 'setMain'])->name('hotels.images.set-main');
    Route::delete('hotels/{hotel}/images/{image}', [HotelHotelImageController::class, 'destroy'])->name('hotels.images.destroy');
    Route::resource('images', HotelImageController::class)->except(['show'])->parameters(['images' => 'image']);
    Route::resource('types-chambre', TypeChambreController::class)->parameters(['types-chambre' => 'typeChambre']);
    Route::resource('chambres', ChambreController::class)->parameters(['chambres' => 'chambre']);
    Route::resource('reservations', AdminReservationController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])->parameters(['reservations' => 'reservation']);
    Route::get('avis', [AdminAvisController::class, 'index'])->name('avis.index');
    Route::post('avis/{avi}/reponse', [AdminAvisController::class, 'repondre'])->name('avis.repondre');

    // Partenaires
    Route::prefix('partenaires')->name('partenaires.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PartenaireController::class, 'index'])->name('index');
        Route::get('/creer', [\App\Http\Controllers\Admin\PartenaireController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PartenaireController::class, 'store'])->name('store');
        Route::get('/{partenaire}', [\App\Http\Controllers\Admin\PartenaireController::class, 'show'])->name('show');
        Route::patch('/{partenaire}/statut', [\App\Http\Controllers\Admin\PartenaireController::class, 'updateStatut'])->name('statut');
        Route::post('/{partenaire}/note', [\App\Http\Controllers\Admin\PartenaireController::class, 'saveNote'])->name('note');
        Route::post('/{partenaire}/invitation', [\App\Http\Controllers\Admin\PartenaireController::class, 'envoyerInvitation'])->name('invitation');
        Route::post('/{partenaire}/valider', [\App\Http\Controllers\Admin\PartenaireController::class, 'valider'])->name('valider');
        Route::post('/{partenaire}/refuser', [\App\Http\Controllers\Admin\PartenaireController::class, 'refuser'])->name('refuser');
    }); // ← fin partenaires

    // Boîte mail
    Route::prefix('boite-mail')->name('boite-mail.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BoiteMailController::class, 'index'])->name('index');
        Route::get('/{message}', [\App\Http\Controllers\Admin\BoiteMailController::class, 'show'])->name('show');
        Route::post('/{message}/repondre', [\App\Http\Controllers\Admin\BoiteMailController::class, 'repondre'])->name('repondre');
        Route::post('/{message}/inviter', [\App\Http\Controllers\Admin\BoiteMailController::class, 'inviterPartenaire'])->name('inviter-partenaire');
        Route::patch('/{message}/archiver', [\App\Http\Controllers\Admin\BoiteMailController::class, 'archiver'])->name('archiver');
        Route::patch('/{message}/lu', [\App\Http\Controllers\Admin\BoiteMailController::class, 'toggleLu'])->name('lu');
        Route::delete('/{message}', [\App\Http\Controllers\Admin\BoiteMailController::class, 'destroy'])->name('destroy');
    }); // ← fin boite-mail

}); // ← fin admin

// Formulaire public hôtelier (hors admin)
Route::get('/partenaire/inscription/{token}', [\App\Http\Controllers\Admin\PartenaireController::class, 'formulaireInscription'])->name('partenaire.formulaire');
Route::post('/partenaire/inscription/{token}', [\App\Http\Controllers\Admin\PartenaireController::class, 'soumettreFormulaire'])->name('partenaire.soumettre');

// ══════════════════════════════════════
//  ESPACE ADMIN HÔTEL
// ══════════════════════════════════════
Route::middleware(['auth', 'hoteladmin'])->prefix('hotel-admin')->name('hoteladmin.')->group(function () {

    Route::get('/', \App\Http\Controllers\HotelAdmin\DashboardController::class)->name('dashboard');

    Route::get('mon-hotel', [\App\Http\Controllers\HotelAdmin\MonHotelController::class, 'edit'])->name('hotel.edit');
    Route::put('mon-hotel', [\App\Http\Controllers\HotelAdmin\MonHotelController::class, 'update'])->name('hotel.update');

    Route::get('photos', [\App\Http\Controllers\HotelAdmin\PhotoController::class, 'index'])->name('photos.index');
    Route::post('photos', [\App\Http\Controllers\HotelAdmin\PhotoController::class, 'store'])->name('photos.store');
    Route::patch('photos/{image}/main', [\App\Http\Controllers\HotelAdmin\PhotoController::class, 'setMain'])->name('photos.setMain');
    Route::delete('photos/{image}', [\App\Http\Controllers\HotelAdmin\PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::resource('types-chambre', \App\Http\Controllers\HotelAdmin\TypeChambreController::class)
        ->parameters(['types-chambre' => 'typeChambre']);

    Route::resource('chambres', \App\Http\Controllers\HotelAdmin\ChambreController::class)
        ->parameters(['chambres' => 'chambre']);

    Route::get('reservations', [\App\Http\Controllers\HotelAdmin\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('reservations/{reservation}', [\App\Http\Controllers\HotelAdmin\ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('reservations/{reservation}/statut', [\App\Http\Controllers\HotelAdmin\ReservationController::class, 'updateStatut'])->name('reservations.statut');
    Route::get('avis', [\App\Http\Controllers\HotelAdmin\AvisController::class, 'index'])->name('avis.index');
    Route::post('avis/{avis}/repondre', [\App\Http\Controllers\HotelAdmin\AvisController::class, 'repondre'])->name('avis.repondre');
});

// Vérification email en temps réel
Route::post('/api/verify-email', function(\Illuminate\Http\Request $request) {
    $email = $request->input('email', '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['valid' => false, 'message' => 'Format d\'email invalide']);
    }

    $domain = substr(strrchr($email, '@'), 1);

    $whitelistDomains = [
        'djibstay.dj',
        'gmail.com','yahoo.com','yahoo.fr','hotmail.com',
        'outlook.com','outlook.fr','live.com','msn.com',
        'icloud.com','me.com','mac.com',
        'protonmail.com','proton.me',
        'aol.com','mail.com','gmx.com','gmx.fr',
        'wanadoo.fr','orange.fr','sfr.fr','free.fr','laposte.net',
        'djibouti.dj','intnet.dj','dd.dj',
    ];

    if (in_array(strtolower($domain), $whitelistDomains)) {
        return response()->json(['valid' => true, 'message' => 'Email valide']);
    }

    $hasMx = false;
    $hasA  = false;

    try {
        $mx = [];
        $hasMx = @getmxrr($domain, $mx);
        if (!$hasMx) {
            $hasA = @checkdnsrr($domain, 'A');
        }
    } catch (\Throwable $e) {
        return response()->json(['valid' => true, 'message' => 'Email valide']);
    }

    if ($hasMx || $hasA) {
        return response()->json(['valid' => true, 'message' => 'Email valide']);
    }

    return response()->json([
        'valid'   => false,
        'message' => 'Ce domaine email n\'existe pas. Vérifiez votre adresse.'
    ]);
})->name('verify.email');