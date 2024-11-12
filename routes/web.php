<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DynamicSettingsController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\ThemeSettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\WelcomeController;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Guest routes
Route::middleware(['web', 'guest:admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.login');
    });

    Route::get('/admin/login', function () {
        return view('auth.admin-login');
    })->name('admin.login');

    Route::post('/admin/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    })->name('admin.login.submit');
});

// Admin routes
Route::middleware(['web', 'auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('queues', QueueController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('counters', CounterController::class);
    Route::resource('staff', StaffController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::resource('categories', CategoryController::class);

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/account', [SettingsController::class, 'account'])->name('account');
        Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        Route::get('/dynamic', [SettingsController::class, 'dynamic'])->name('dynamic');

        // Dynamic Settings API routes
        Route::post('/countries', [DynamicSettingsController::class, 'storeCountry'])->name('countries.store');
        Route::post('/regions', [DynamicSettingsController::class, 'storeRegion'])->name('regions.store');
        Route::post('/locations', [DynamicSettingsController::class, 'storeLocation'])->name('locations.store');
        Route::delete('/countries/{country}', [DynamicSettingsController::class, 'destroyCountry'])->name('countries.destroy');
        Route::delete('/regions/{region}', [DynamicSettingsController::class, 'destroyRegion'])->name('regions.destroy');
        Route::delete('/locations/{location}', [DynamicSettingsController::class, 'destroyLocation'])->name('locations.destroy');
        Route::post('/google-places/search', [DynamicSettingsController::class, 'searchGooglePlaces'])->name('google-places.search');

        // System Settings routes
        Route::get('/system', [SystemSettingsController::class, 'index'])->name('system');
        Route::post('/system/api-keys', [SystemSettingsController::class, 'updateApiKeys'])->name('system.api-keys');
        Route::post('/system/company', [SystemSettingsController::class, 'updateCompany'])->name('system.company');
        Route::post('/system/messages', [SystemSettingsController::class, 'updateMessages'])->name('system.messages');
        Route::post('/system/countries', [SystemSettingsController::class, 'updateCountries'])->name('system.countries');
        Route::get('/system/fetch-regions/{country}', [SystemSettingsController::class, 'fetchRegions'])->name('system.regions');

        // Scripts Settings routes
        Route::get('/scripts', [SettingsController::class, 'scripts'])->name('scripts');
        Route::post('/scripts/recaptcha', [SettingsController::class, 'updateRecaptcha'])->name('scripts.recaptcha');
        Route::post('/scripts/google-sso', [SettingsController::class, 'updateGoogleSSO'])->name('scripts.google-sso');
        Route::post('/scripts/chatbot', [SettingsController::class, 'updateChatbot'])->name('scripts.chatbot');

        // Theme Settings routes
        Route::get('/theme', [ThemeSettingsController::class, 'index'])->name('theme');
        Route::post('/theme/branding', [ThemeSettingsController::class, 'updateBranding'])->name('theme.branding');
        Route::post('/theme/upload-image', [ThemeSettingsController::class, 'uploadImage'])->name('theme.upload-image');
    });

    // Setup completion route
    Route::post('/complete-setup', function (Request $request) {
        $admin = $request->user('admin');
        $admin->update(['setup_completed' => true]);
        return response()->json(['success' => true]);
    })->name('complete-setup');

    // Inside your admin routes group
    Route::post('/staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

    // Users routes
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/kyc', [App\Http\Controllers\Admin\KycController::class, 'index'])->name('users.kyc');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');

    // Notifications routes
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::post('/settings/notifications/test', [SettingsController::class, 'testNotifications'])->name('settings.notifications.test');

    // SMTP routes
    Route::put('/settings/smtp', [SettingsController::class, 'updateSmtp'])->name('settings.smtp.update');

    // Queue routes
    Route::prefix('queue')->name('queue.')->group(function () {
        Route::get('/', [QueueController::class, 'index'])->name('list');
        Route::get('/create', [QueueController::class, 'create'])->name('create');
        Route::post('/store', [QueueController::class, 'store'])->name('store');
        Route::put('/{queue}/status', [QueueController::class, 'updateStatus'])->name('update-status');
        Route::get('/analytics', [QueueController::class, 'analytics'])->name('analytics');
        Route::get('/tracking', [QueueController::class, 'tracking'])->name('tracking');
    });
});

// Regular user routes
Route::middleware([
    'web',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Staff routes
Route::prefix('staff')->name('staff.')->group(function () {
    Route::middleware('guest:staff')->group(function () {
        Route::get('/login', [App\Http\Controllers\Staff\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Staff\AuthController::class, 'login']);
    });

    Route::middleware('auth:staff')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Staff\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');
    });
});

// Add these routes to your web.php file
Route::get('/setup-password/{token}', [App\Http\Controllers\Auth\SetupPasswordController::class, 'showSetupForm'])
    ->name('password.setup')
    ->middleware('guest');

Route::post('/setup-password', [App\Http\Controllers\Auth\SetupPasswordController::class, 'setup'])
    ->name('password.setup.save')
    ->middleware('guest');
