<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/',function(){
//     return redirect('https://mazenet.in');
// });

// Route::get('/mazenet_login',[App\Http\Controllers\AuthController::class,'mazenetLogin']);

Route::get('/',[App\Http\Controllers\AuthController::class,'index']);
Route::POST('/customlogin',[App\Http\Controllers\AuthController::class,'customLogin'])->name('customlogin');
Route::get('/logout',[App\Http\Controllers\AuthController::class,'logout'])->name('signout');



Auth::routes([
'register' => true, // Register Routes...

'reset' => false, // Reset Password Routes...

'verify' => false, // Email Verification Routes...

'login' => false // login disable
]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/projects_detail', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/team_members', [App\Http\Controllers\HomeController::class, 'index'])->name('team_members');

Route::group(['middleware' => 'isadmin'],function(){
    Route::get('/project', [App\Http\Controllers\ProjectController::class, 'index'])->name('project');
    Route::get('/project_add', [App\Http\Controllers\ProjectController::class, 'view'])->name('project_add');
    Route::POST('/project_store',[App\Http\Controllers\ProjectController::class,'store']);
    Route::get('/project_edit/{id}',[App\Http\Controllers\ProjectController::class,'view'])->where('id', '[0-9]+');
    Route::PATCH('/project_store',[App\Http\Controllers\ProjectController::class,'store']);
    Route::get('/project_remark/{id}',[App\Http\Controllers\ProjectController::class,'remark'])->where('id', '[0-9]+');
    Route::POST('/project_delete',[App\Http\Controllers\ProjectController::class,'destroy'])->where('id', '[0-9]+');
    Route::DELETE('/project_member_delete',[App\Http\Controllers\ProjectController::class,'project_member_remove'])->where('id', '[0-9]+');
    Route::get('/project_view/{id}',[App\Http\Controllers\ProjectController::class,'project_view'])->where('id', '[0-9]+');
    Route::DELETE('/other_docs_remove',[App\Http\Controllers\ProjectController::class,'other_docs_remove']);
});


Route::get('/task', [App\Http\Controllers\TaskController::class, 'index'])->name('task');
Route::get('/task_add', [App\Http\Controllers\TaskController::class, 'view'])->name('task_add');
Route::POST('/task_store',[App\Http\Controllers\TaskController::class,'store']);
Route::get('/task_edit/{id}',[App\Http\Controllers\TaskController::class,'view'])->where('id', '[0-9]+');
Route::PATCH('/task_store',[App\Http\Controllers\TaskController::class,'store']);
Route::get('/task_remark/{id}',[App\Http\Controllers\TaskController::class,'remark'])->where('id', '[0-9]+');
Route::POST('/task_delete',[App\Http\Controllers\TaskController::class,'destroy'])->where('id', '[0-9]+');
Route::get('/assigned_project_get',[App\Http\Controllers\TaskController::class,'assigned_project_get'])->where('id', '[0-9]+');


Route::POST('/client_add',[App\Http\Controllers\ClientController::class,'store']);

Route::get('/daily_report', [App\Http\Controllers\DailyReportController::class, 'index'])->name('daily_report');
Route::get('/consolidated_report', [App\Http\Controllers\ConsolidatedReportController::class, 'index'])->name('consolidated_report')->middleware(['isadmin']);


Route::get('/daily_report_mail_send',[App\Http\Controllers\AutomationController::class, 'daily_report']);

Route::get('/closed_project_mail_send',[App\Http\Controllers\AutomationController::class, 'closed_project_report']);

Route::get('/monthly_project_mail_send',[App\Http\Controllers\AutomationController::class, 'monthly_project_report']);

Route::get('/progress_project_weekly_mail',[App\Http\Controllers\AutomationController::class, 'progress_project_weekly_report']);

Route::get('/developers_individual_weekly_mail',[App\Http\Controllers\AutomationController::class, 'developers_individual_weekly_report']);

Route::get('/project_delivery_report_mail',[App\Http\Controllers\AutomationController::class, 'project_delivery_report']);



// Route::get('/project_client_details', [App\Http\Controllers\ProjectClientController::class, 'index'])->name('project_client_details');
// Route::get('/project_client_details_add', [App\Http\Controllers\ProjectClientController::class, 'view'])->name('project_client_details_add');
// Route::POST('/project_client_details_store',[App\Http\Controllers\ProjectClientController::class,'store']);
// Route::get('/project_client_details_edit/{id}',[App\Http\Controllers\ProjectClientController::class,'view'])->where('id', '[0-9]+');
// Route::DELETE('/remove_po_details',[App\Http\Controllers\ProjectClientController::class,'po_details_remove']);
// Route::get('/project_client_remark/{id}',[App\Http\Controllers\ProjectClientController::class,'remark'])->where('id', '[0-9]+');
// Route::POST('/project_client_delete',[App\Http\Controllers\ProjectClientController::class,'destroy']);


