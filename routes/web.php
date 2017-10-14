<?php

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

Route::get('/', 'AccountController@Login')->name('login');
Route::get('/logout', 'AccountController@Logout')->name('logout');
Route::post('/login','AccountController@LoginPost')->name('loginPost');


Route::get('/clear', function()
{
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    dd("Cleared!");
});
Route::get('/test',function(){
   dd(\App\Role::all());
});



Route::group(['prefix' => '/activity/', 'middleware' => ['auth','AllAuth']], function ()
{
   Route::group(['middleware' => ['AdminAuth']],function ()
   {
       Route::get('users','ActivityController@ViewUsers')->name('users');
       Route::get('users/add','ActivityController@AddUser')->name('addUsers');
       Route::post('users/add','ActivityController@SaveUser')->name('saveUser');
       Route::get('user/revoke-access/{token}','ActivityController@RevokeUser')->name('revokeUser');
       Route::get('user/edit/{token}','ActivityController@EditUser')->name('editUser');
       Route::get('user/delete/{token}','ActivityController@DeleteUser')->name('deleteUser');

   });

    //Student
    Route::get('student/view','ActivityController@ViewStudent')->name('viewStudent');
    Route::get('student/action/{token}','ActivityController@ActionStudent')->name('studentAction');
    Route::get('student/action/edit/{id}','ActivityController@ActionStudentEdit')->name('studentActionEdit');
    Route::get('student/action/delete/{id}','ActivityController@ActionStudentDelete')->name('studentActionDelete');
    Route::post('student/add','ActivityController@SaveStudent')->name('saveStudent');



    //Payment List
    Route::get('payment/list','ActivityController@PaymentList')->name('paymentList');
    Route::post('payment/list/add','ActivityController@AddPaymentList')->name('addPayList');
    Route::get('payment/list/delete/{token}','ActivityController@DeletePayList')->name('deletePayList');
});
