<?php
Route::match(['post','get'],'/login','Admin\LoginController@login');
Route::post('/logout','Admin\LoginController@logout');
Route::group(['middleware'=>['web','admin.login']],function (){
    Route::get('/main','Admin\MainController@index');
    Route::match(['post','get'],'/article_type_list','Admin\ArticleController@ArticleTypeList');
    Route::match(['post','get'],'/add_article_type_list','Admin\ArticleController@addArticleType');
    Route::match(['post','get'],'/edit_article_type_list','Admin\ArticleController@editArticleType');
    Route::match(['post','get'],'/add_child_article_type','Admin\ArticleController@addChildArticleType');
    Route::post('/delArticleType','Admin\ArticleController@delArticleType');
    Route::get('/article_type_recycle_bin_list','Admin\ArticleController@articleTypeRecycleBinList');
    Route::post('/reduction_article_type','Admin\ArticleController@reduction_article_type');
    Route::match(['post','get'],'/article_list','Admin\ArticleController@articleList');
    Route::match(['post','get'],'/add_article','Admin\ArticleController@createArticle');
    Route::match(['post','get'],'/edit_article','Admin\ArticleController@editArticle');
});
