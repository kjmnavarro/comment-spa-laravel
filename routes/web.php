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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/addcomment', 'CommentsController@addcomment');
Route::post('/reply/{id}', 'CommentsController@replycomment');
Route::get('/getcomments', 'CommentsController@getcomments');
Route::get('/getreplies/{id}', 'CommentsController@getreplies');