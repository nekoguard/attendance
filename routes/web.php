<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\Admin\MasterDepartmentController;
use App\Http\Controllers\Admin\MasterSectionController;
use App\Http\Controllers\Admin\MasterStatusWorkTypeController;
use App\Models\Attendance;




Route::get('/', function () {
    return redirect()->route('login');
});

// ダッシュボード
Route::get('/dashboard', function () {
    $user = Auth::user();
    $today = now()->toDateString();
    $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
    return view('dashboard', ['attendance' => $attendance]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // 勤怠関連
    Route::get('/attendance-list', [AttendanceController::class, 'index'])->name('attendance-list');
    Route::get('/timecard', [AttendanceController::class, 'timecard'])->name('timecard');

    // 打刻APIを1本化
    Route::post('/attendances/stamp', [AttendanceController::class, 'stamp'])->name('attendances.stamp');
    Route::put('/attendances/{attendance}/status', [AttendanceController::class, 'updateStatus'])->name('attendance.status.update');

    // 在籍情報編集
    Route::get('/user-infos/{id}/edit', [UserInfoController::class, 'edit'])->name('user-infos.edit');
    Route::post('/user-infos/{id}', [UserInfoController::class, 'update'])->name('user-infos.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 管理者用ルーティング
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // 部署マスタ管理
    Route::get('/departments', [MasterDepartmentController::class, 'index'])->name('departments');
    Route::get('/departments/create', [MasterDepartmentController::class, 'createDepartment'])->name('departments.create');
    Route::post('/departments', [MasterDepartmentController::class, 'storeDepartment'])->name('departments.store');
    Route::get('/departments/{id}/edit', [MasterDepartmentController::class, 'editDepartment'])->name('departments.edit');
    Route::put('/departments/{id}', [MasterDepartmentController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}', [MasterDepartmentController::class, 'deleteDepartment'])->name('departments.delete');

    // 課マスタ管理
    Route::get('/sections', [MasterSectionController::class, 'index'])->name('sections');
    Route::get('/sections/create', [MasterSectionController::class, 'createSection'])->name('sections.create');
    Route::post('/sections', [MasterSectionController::class, 'storeSection'])->name('sections.store');
    Route::get('/sections/{id}/edit', [MasterSectionController::class, 'editSection'])->name('sections.edit');
    Route::put('/sections/{id}', [MasterSectionController::class, 'updateSection'])->name('sections.update');
    Route::delete('/sections/{id}', [MasterSectionController::class, 'deleteSection'])->name('sections.delete');


    // 在籍状態・勤務区分マスタ管理（1ページ）
    Route::prefix('master/status-work-type')->name('master.status_work_type.')->group(function () {
        Route::get('/', [MasterStatusWorkTypeController::class, 'index'])->name('index');
        // Statuses
        Route::post('/status', [MasterStatusWorkTypeController::class, 'storeStatus'])->name('status.store');
        Route::put('/status/{id}', [MasterStatusWorkTypeController::class, 'updateStatus'])->name('status.update');
        Route::delete('/status/{id}', [MasterStatusWorkTypeController::class, 'destroyStatus'])->name('status.destroy');
        // WorkTypes
        Route::post('/work-type', [MasterStatusWorkTypeController::class, 'storeWorkType'])->name('work_type.store');
        Route::put('/work-type/{id}', [MasterStatusWorkTypeController::class, 'updateWorkType'])->name('work_type.update');
        Route::delete('/work-type/{id}', [MasterStatusWorkTypeController::class, 'destroyWorkType'])->name('work_type.destroy');
    });
});

require __DIR__.'/auth.php';
