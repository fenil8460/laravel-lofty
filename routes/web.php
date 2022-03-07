<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\SalesloftReport;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\Auth\RegisteredUserController;
// use Illuminate\Http\Request;
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
    return view('welcome');
});
//  Route::middleware(['auth']);

//view users
Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth'])->name('dashboard');

Route::get('/home', [UserRoleController::class, 'setKey'])->middleware(['auth'])->name('home');

//view users
Route::get('/users', [UserRoleController::class, 'viewUsers'])->middleware(['auth', 'is_admin'])->name('users');
//search users
Route::get('/searchings', [UserRoleController::class, 'SviewUsers'])->middleware(['auth', 'is_admin'])->name('userfetch');
//view insert-role form
Route::get('/insertrole', function () {
    return view('user.addUser');
})->middleware(['auth', 'is_admin'])->name('insertrole');
//insert role
// Route::post('/addrole',[UserRoleController::class,'addRole'] )->middleware(['auth','is_admin'])->name('addrole');
Route::post('/addrole', [RegisteredUserController::class, 'store'])->middleware(['auth', 'is_admin'])->name('addrole');
//update role
Route::post('/update', [UserRoleController::class, 'addRole'])->middleware(['auth', 'is_admin'])->name('update');
//delete data 
Route::get('delete/{id}', [UserRoleController::class, 'deleteRole'])->middleware(['auth', 'is_admin'])->name('deleteRole');
//show-data in form(update)
Route::get('update/{id}', [UserRoleController::class, 'showUpdateRole'])->middleware(['auth', 'is_admin'])->name('updateid');

//groups
Route::get('groups', [GroupsController::class, 'create'])->middleware(['auth', 'is_admin'])->name('groups');
Route::get('userfetch', [GroupsController::class, 'userFetch'])->middleware(['auth', 'is_admin'])->name('user-fetch');
Route::post('addgroup', [GroupsController::class, 'store'])->middleware(['auth', 'is_admin'])->name('add-group');
Route::get('listgroups', [GroupsController::class, 'index'])->middleware(['auth', 'is_admin'])->name('list-group');
Route::get('searchgroups', [GroupsController::class, 'searchGroups'])->middleware(['auth', 'is_admin'])->name('search-group');
Route::get('deletegroups/{id}', [GroupsController::class, 'destroy'])->middleware(['auth', 'is_admin'])->name('delete-group');
Route::get('viewgroup/{id}', [GroupsController::class, 'edit'])->middleware(['auth', 'is_admin'])->name('viewspecific-group');
Route::post('updategroup', [GroupsController::class, 'update'])->middleware(['auth', 'is_admin'])->name('update-group');

//Report cadence,executiv,singlerep
Route::get('sl/cadencereport', [SalesloftReport::class, 'slCadenceReport'])->middleware(['auth'])->name('cadencereport');
Route::get('sl/executivereport', [SalesloftReport::class, 'slExecutiveReport'])->middleware(['auth'])->name('executivereport');
Route::get('sl/singlerepreport', [SalesloftReport::class, 'slSingleRepReport'])->middleware(['auth'])->name('singlerep');


//Filter Data
Route::get('sl/cadencefilter', [SalesloftReport::class, 'cadenceFilter'])->middleware(['auth'])->name('filter.cadence');
Route::get('sl/executivefilter', [SalesloftReport::class, 'executiveFilter'])->middleware(['auth'])->name('filter.executive');
Route::get('sl/singlerepfilter', [SalesloftReport::class, 'singlerepFilter'])->middleware(['auth'])->name('filter.singlerep');
Route::get('sl/test', function () {
    return print_r(phpinfo());
});

//redirection home and sl 
if (Route::currentRouteName() == '' || Route::currentRouteName() == '/sl') {
    Route::get('/', function () {
        return redirect('sl/cadencereport');
    });
    Route::get('/sl', function () {
        return redirect('sl/cadencereport');
    });
}


require __DIR__ . '/auth.php';
