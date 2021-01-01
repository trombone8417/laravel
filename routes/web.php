<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

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

Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
// Tag
Route::post('/create_tag','AdminController@addTag');
Route::get('/get_tags','AdminController@getTag');
Route::post('/edit_tags','AdminController@editTag');
Route::post('/delete_tags','AdminController@deleteTag');
// 圖片
Route::post('/upload','AdminController@upload');
Route::post('/delete_image','AdminController@deleteImage');
// Category
Route::post('/create_category','AdminController@addCategory');
Route::get('/get_category','AdminController@getCategory');
Route::post('/edit_category','AdminController@editCategory');
Route::post('/delete_category', 'AdminController@deleteCategory');
// Admin
Route::post('/create_user', 'AdminController@createUser');
Route::get('/get_users', 'AdminController@getUsers');
Route::post('/edit_user', 'AdminController@editUser');
Route::post('/delete_user', 'AdminController@deleteUser');
// 登入
Route::post('/admin_login', 'AdminController@adminLogin');
});


Route::get('/logout', 'AdminController@logout');
Route::get('/', 'AdminController@index');
Route::any('{slug}','AdminController@index');

// part 23 12:55
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::any('{slug}',function(){
//     return view('welcome');
// });
