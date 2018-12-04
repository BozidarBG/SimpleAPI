<?php

use Illuminate\Http\Request;




Route::get('/tags', 'TagController@index');

Route::get('/articles', 'ArticleController@index');
Route::get('/articles/{article}', 'ArticleController@show')->name('articles.show');
Route::post('/articles', 'ArticleController@store');
Route::post('/articles/{article}', 'ArticleController@update');
Route::post('/articles/delete/{article}', 'ArticleController@destroy');
Route::get('/my-articles', 'ArticleController@myArticles');

Route::post('/comment', 'CommentController@store');
Route::post('/delete-comment/{comment}', 'CommentController@destroy');

//Route::get('/test', 'ArticleController@test')->name('articles.test');

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');