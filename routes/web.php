<?php

use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableUserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TypeTestController;
use App\Http\Controllers\Admin\TotalTestController;
use App\Http\Controllers\SubTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserTestController;
use App\Http\Controllers\Admin\ScoringRuleController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\SubtestInstructionRangeController;
use App\Http\Controllers\AdminUserController;

Auth::routes(['verify' => true]);

Route::get('/email/verify', function (Request $request) {

    if ($request->user()->hasAnyRole(['admin','super_admin'])) {
        return redirect()->route('admin.dashboard');
    }

    return app(VerificationController::class)->show($request);

})->middleware('auth')->name('verification.notice');

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
    Route::get('admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
    Route::post('admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
    Route::get('admin-users/{admin_user}/edit', [AdminUserController::class, 'edit'])->name('admin-users.edit');
    Route::put('admin-users/{admin_user}', [AdminUserController::class, 'update'])->name('admin-users.update');
    Route::delete('admin-users/{admin_user}', [AdminUserController::class, 'destroy'])->name('admin-users.destroy');
});

Route::middleware(['auth','verified','not_admin'])->group(function () { 
    Route::get('/', [UserTestController::class, 'tests'])->name('user.tests');
    Route::get('/profile', [ProfileController::class, 'form'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'save'])->name('profile.save');
    Route::prefix('home')->group(function () {
        Route::get('/', [UserTestController::class, 'tests'])->name('user.tests');
        Route::get('{test:slug}', [UserTestController::class, 'typeTests'])->name('user.type-tests');
        Route::get('{test:slug}/{typeTest:slug}', [UserTestController::class, 'subTests'])->name('user.sub-tests');
        Route::get('{test:slug}/{typeTest:slug}/{subTest:slug}', [UserTestController::class, 'questions'])->name('user.questions');
        Route::get('{test:slug}/{typeTest:slug}/{subTest:slug}/question', [UserTestController::class, 'questionData'])->name('user.questions.data');
        Route::post('{test:slug}/{typeTest:slug}/{subTest:slug}/submit-single', [UserTestController::class, 'submitSingle'])->name('user.questions.submit-single');
        Route::post('{test:slug}/{typeTest:slug}/{subTest:slug}/submit-all', [UserTestController::class, 'submitAll'])->name('user.questions.submit-all');
        Route::post('{test:slug}/{typeTest:slug}/{subTest:slug}/submit', [UserTestController::class, 'submit'])->name('user.questions.submit');
    });
});

Route::middleware(['auth','role:admin|super_admin'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard'); 

    Route::get('/total-test', [TotalTestController::class, 'index'])->name('admin.total-test.index');
    Route::get('/dashboard/users', [TableUserController::class, 'index'])->name('dashboard.users');
    Route::get('/admin/total-test/export', [TotalTestController::class, 'exportExcel'])->name('admin.total-test.export');

    Route::get('/tests', [HomeController::class, 'index'])->name('test.index');
    Route::get('/tests/create', [HomeController::class, 'create'])->name('test.create');
    Route::post('/tests', [HomeController::class, 'store'])->name('test.store');
    Route::get('/tests/{id}/edit', [HomeController::class, 'edit'])->name('test.edit');
    Route::put('/tests/{id}/update', [HomeController::class, 'update'])->name('test.update');
    Route::delete('/tests/{id}', [HomeController::class, 'destroy'])->name('test.destroy');

    Route::prefix('attempts')->group(function () {
        Route::get('/', [AttemptController::class, 'index'])->name('admin.attempts.index');
        Route::delete('/{attempt}', [AttemptController::class, 'destroy'])->name('admin.attempts.destroy');
        Route::delete('/type-test/{attemptTypeTest}', [AttemptController::class, 'destroyTypeTest'])->name('admin.attempts.type-test.destroy');
        Route::delete('/sub-test/{attemptSubTest}', [AttemptController::class, 'destroySubTest'])->name('admin.attempts.sub-test.destroy');
    });

    Route::prefix('test')->group(function () {
        Route::get('{test:slug}', [TypeTestController::class, 'index'])->name('type-test.index');
        Route::post('{test:slug}/store-type', [TypeTestController::class, 'store'])->name('type-test.store');
        Route::put('{test:slug}/type-test/{typeTest}', [TypeTestController::class, 'update'])->name('type-test.update');
        Route::delete('{test:slug}/type-test/{typeTest}', [TypeTestController::class, 'destroy'])->name('type-test.destroy');

        Route::get('{test:slug}/{typeTest:slug}', [SubTestController::class, 'index'])->name('sub-test.index');
        Route::get('{test:slug}/{typeTest:slug}/show', [SubTestController::class, 'show'])->name('sub-test.show');
        Route::post('{test:slug}/{typeTest:slug}/store-sub', [SubTestController::class, 'store'])->name('sub-test.store');
        Route::put('{test:slug}/{typeTest:slug}/sub-test/{id}', [SubTestController::class, 'update'])->name('sub-test.update');
        Route::delete('{test:slug}/{typeTest:slug}/sub-test/{id}', [SubTestController::class, 'destroy'])->name('sub-test.destroy');

        Route::prefix('{test:slug}/{typeTest:slug}/{subTest:slug}/ranges')->group(function () {
            Route::get('/', [SubtestInstructionRangeController::class, 'index'])->name('sub-test.ranges.index');
            Route::post('/', [SubtestInstructionRangeController::class, 'store'])->name('instruction-range.store');
            Route::get('/preview', [SubtestInstructionRangeController::class, 'preview'])->name('sub-test.ranges.preview');
            Route::put('/{range}', [SubtestInstructionRangeController::class, 'update'])->name('instruction-range.update');
            Route::delete('/{range}', [SubtestInstructionRangeController::class, 'destroy'])->name('instruction-range.destroy');
        });

        Route::prefix('{test:slug}/{typeTest:slug}/{subTest:slug}/questions')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('question.index');
            Route::get('/create', [QuestionController::class, 'create'])->name('question.create');
            Route::post('/', [QuestionController::class, 'store'])->name('question.store');
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
            Route::put('/{question}', [QuestionController::class, 'update'])->name('question.update');
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
        });
    });

    Route::prefix('scoring')->group(function () {
        Route::get('/', [ScoringRuleController::class, 'index'])->name('scoring.index');
        Route::post('/store', [ScoringRuleController::class, 'store'])->name('scoring.store');
        Route::put('/update{id}', [ScoringRuleController::class, 'update'])->name('scoring.update');
        Route::delete('/delete/{id}', [ScoringRuleController::class, 'destroy'])->name('scoring.destroy');
    });

    Route::delete('dashboard/users/{id}', [TableUserController::class, 'destroy'])->name('dashboard.users.destroy');
});

Route::get('/provinces', [ProfileController::class, 'provinces'])->name('provinces.proxy');
Route::get('/regencies/{code}', [ProfileController::class, 'regencies'])->name('regencies.proxy');
Route::get('/districts/{code}', [ProfileController::class, 'districts'])->name('districts.proxy');
Route::get('/villages/{code}', [ProfileController::class, 'villages'])->name('villages.proxy');
