<?php
Route::match(['post','get'],'/login','Admin\LoginController@login');
Route::post('/logout','Admin\LoginController@logout');
Route::group(['middleware'=>['web','admin.login']],function (){
    Route::get('/main','Admin\MainController@index');

});
