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
    phpinfo();
    dd("Cleared!");
});
//Route::post('/api/test','ActivityController@ddJson');
Route::get('/test',function ()
{
    dd(storage_path(), public_path());
});


Route::get('excel','ActivityController@ExcelTest')->name('excel');
//Route::get('excel','ActivityController@Excel')->name('excel');


Route::group(['prefix' => '/activity/','middleware' => ['auth','AllAuth']], function ()
{
    //Change Password
    Route::get('password/change','ActivityController@changePassword')->name('passwordChange');
    Route::post('password/change','ActivityController@changePasswordPost')->name('passwordChangePost');
});

Route::group(['prefix' => '/activity/', 'middleware' => ['auth','AllAuth','changePassword']], function ()
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
    Route::get('student/view/{token}','ActivityController@ViewStudentID')->name('viewStudentID');
    Route::get('student/action/{token}','ActivityController@ActionStudent')->name('studentAction');
    Route::get('student/payment/add/{token}','ActivityController@ActionStudentAdd')->name('studentActionAdd');
    Route::get('student/action/edit/{id}','ActivityController@ActionStudentEdit')->name('studentActionEdit');
    Route::get('student/action/delete/{id}','ActivityController@ActionStudentDelete')->name('studentActionDelete');
    Route::post('student/add','ActivityController@SaveStudent')->name('saveStudent');
    Route::post('student/search', 'ActivityController@searchStudent')->name('searchStudent');

    //Class
    Route::get('/class/view','ActivityController@ViewClass')->name('viewClass');
    Route::get('/class/action/{token}','ActivityController@ClassAction')->name('classAction');
    Route::post('/class/add','ActivityController@ClassAdd')->name('addClass');

    //Payment List
    Route::get('payment/list','ActivityController@PaymentList')->name('paymentList');
    Route::post('payment/list/add','ActivityController@AddPaymentList')->name('addPayList');
    Route::get('payment/list/delete/{token}','ActivityController@DeletePayList')->name('deletePayList');



    //Session
    Route::get('session/view','ActivityController@Sess')->name('viewSession');
    Route::post('session/add','ActivityController@SessAdds')->name('addSession');


    //Payment
    Route::get('payment/view/{col}/{val}','ActivityController@ViewPaymentCol')->name('viewPaymentID');
    Route::get('payment/view','ActivityController@ViewPayment')->name('viewPayment');
    Route::get('payment/action/{token}','ActivityController@PaymentAction')->name('payAction');
    Route::get('payment/edit/{token}','ActivityController@PaymentEdit')->name('payEdit');
    Route::post('payment/save','ActivityController@PaymentSave')->name('savePayment');
    Route::post('payment/search','ActivityController@PaymentSearch')->name('searchPayment');


    //Print
    Route::get('print/class','ActivityController@PrintClass')->name('print_class');
    Route::post('print/class','ActivityController@PrintClassPost')->name('print_class_post');

    Route::get('print/payment','ActivityController@PrintPayment')->name('print_payment');
    Route::post('print/payment','ActivityController@PrintPaymentPost')->name('print_payment_post');
});



