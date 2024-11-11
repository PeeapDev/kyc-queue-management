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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

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
