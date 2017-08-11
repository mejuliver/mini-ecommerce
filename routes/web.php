<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//auth, log-in, register, etc..
// Auth::routes();
//admin auth
Route::get('/app/system/login','Auth\UserAuthController@index');
// ########################## N O T  A U T H E N T I C A T E D ##########################
//home
Route::get('/','Site\HomeController@index');
Route::get('/home', 'Site\HomeController@index');
//query by category
Route::get('/category/{id}','Site\QueryController@query_by_category');
//query by product
Route::get('/product/{id}','Site\QueryController@query_by_product');
Route::get('/tag/{id}','Site\QueryController@query_by_tag');
Route::get('/product/seller/{id}','Site\QueryController@query_by_sellers_product');
//add to wishlist
Route::post('/product/add-to-wishlist','Site\QueryController@add_to_wishlist');
//add to cart
Route::post('/product/add-to-cart','Site\QueryController@add_to_cart');
//search
Route::get('/search','Site\QueryController@search');
//get user settings
Route::get('/app/system/user-settings/get','Site\QueryController@get_settings');
//save settings
Route::post('/app/system/user-settings/save','Site\QueryController@save_settings');
//error 404
Route::get('/error/404', function(){
	return view('errors.404');
});
//clear notifications
Route::post('/notification/cleared','NotificationsController@clear_notification');

//test notification
Route::get('notification','NotificationsController@create_notification');

Route::get('/error/69', function(){
	return view('errors.69');
});
///////////////////////////////////////////// END TEST NOTIFICATIONS


// ########################## A U T H E N T I C A T E D ##########################
//logout
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::group(['middleware' => ['CheckDashboardUser']], function () {
	Route::get('/app/system/dashboard','HomeController@index');
});
// ############################### B U Y E R
Route::group(['middleware' => ['CheckBuyerRole']], function () {
	Route::get('/app/system/user/dashboard','BuyerController@index');
});
Route::post('/rating-system/add-review','BuyerController@add_review');

Route::group(['middleware' => ['CheckSellerPerm']], function () {
	//get the sales item page
	Route::get('/app/system/sale-items','ItemsController@index');
	//create sale item page
	Route::get('/app/system/sale-items/item/create','ItemsController@create_sale_item');
	//add item
	Route::post('/app/system/sale-items/item/create','ItemsController@add_sale_item');
	//view sale item
	Route::get('/app/system/sale-items/item/{id}','ItemsController@sale_item');
	//update sale item
	Route::post('/app/system/sale-items/item/update','ItemsController@update_item');
	//delete image
	Route::post('/app/system/sale-items/item/image/delete','ItemsController@item_delete_item_image');
	// REVIEWS
	//approve review
	Route::post('/app/system/sale-items/item/item-review/approve','ItemsController@approve_review');
	//disapprove review
	Route::post('/app/system/sale-items/item/item-review/disapprove','ItemsController@disapprove_review');
	//reload reviews
	Route::post('/app/system/sale-items/items/reload','ItemsController@reload_sale_items');
	//item data date filter
	Route::post('/app/system/sale-items/items/date-filter','ItemsController@sale_items_date_filter');
	// TAGS
	//get tag page
	Route::get('/app/system/sale-items/tags','ItemsController@tag_page');
	//create tag
	Route::post('/app/system/sale-items/tags/create-tag','ItemsController@create_tag');
	//delete tag
	Route::post('/app/system/sale-items/tags/delete-tag','ItemsController@delete_tag');
	//add item tag
	Route::post('/app/system/sale-items/item/tags/add-tag','ItemsController@item_add_tag');
	//search item tag
	Route::post('/app/system/sale-items/item/tags/search','ItemsController@search_tag');
	//remove item tag
	Route::post('/app/system/sale-items/item/tags/remove-tag','ItemsController@item_remove_tag');
	//delete sale item
	Route::post('/app/system/sale-items/item/delete','ItemsController@delete_sale_item');
	// ITEM PICTURES
	//add item pictures
	Route::post('/app/system/sale-items/item/image-upload','ItemsController@item_add_item_image');
	//set product primary picture
	Route::post('/app/system/sale-items/item/image/set-primary','ItemsController@item_add_product_primary_image');
	//add/remove categories
	Route::post('/app/system/sale-items/item/category','ItemsController@item_add_category');

});
//####################### A D M I N
Route::group(['middleware' => ['CheckAdminRole']], function () {
	Route::get('/app/system/admin/users','AdminController@users_page');
	Route::post('/app/system/admin/users/reject-account','AdminController@reject_account');
	Route::post('/app/system/admin/users/activate-account','AdminController@activate_account');
	Route::get('/app/system/admin/users/{id}','AdminController@view_account');
	Route::get('/app/system/admin/sale-items','AdminController@sale_items');
	Route::get('/app/system/admin/sale-items/item/{id}','AdminController@sale_items_item_view');
	//item data date filter
	Route::post('/app/system/admin/sale-items/items/date-filter','AdminController@sale_items_date_filter');
	//reload reviews
	Route::post('/app/system/admin/sale-items/items/reload','AdminController@reload_sale_items');
	//view orders
	Route::post('/app/system/admin/sale-items/item/view-orders','AdminController@view_orders');
	//categories
	Route::get('/app/system/admin/categories','AdminController@categories');
	//create category
	Route::post('/app/system/admin/categories/create-category','AdminController@add_category');
	//delete category
	Route::post('/app/system/admin/categories/delete-category','AdminController@delete_category');

	//approve item
	Route::post('/app/system/admin/sale-items/item/approve','AdminController@approve_item');
	//reject item
	Route::post('/app/system/admin/sale-items/item/reject','AdminController@reject_item');
	//approve new image of the user
	Route::post('/app/system/admin/users/new-img/review/approve','AdminController@approve_new_img');
	//reject new image fo the user
	Route::post('/app/system/admin/users/new-img/review/reject','AdminController@reject_new_img');
});

Route::post('/app/system/admin/orders-chart','OrderController@get_orders_chart');
Route::post('/app/system/admin/orders-chart/date-filter','OrderController@get_orders_chart_date_filter');

Route::group(['middleware' => ['CheckSellerPerm','CheckBuyerPerm']], function () {
	//view orders items
	Route::post('/app/system/admin/sale-items/item/view-orders/orders','OrderController@view_orders_items');
});

Route::group(['middleware' => ['CheckBuyerPerm']], function () {
	//get order page
	Route::get('/app/system/orders','OrderController@index');
	//reload orders
	Route::post('/app/system/orders/reload-orders','OrderController@reload_orders');
	//orders date filter
	Route::post('/app/system/orders/date-filter','OrderController@orders_date_filter');
});

//####################### PROFILE
//add profile pic
Route::post('/app/system/user/profile-pic','ProfileController@profile_pic');
//add banner pic
Route::post('/app/system/user/banner-pic','ProfileController@banner_pic');

//path for store image
Route::get('/sale-items/item/image/{username}/{item_id}/{image}', function($username,$item_id,$image)
{
    $path = storage_path().'/app/public/'.$username.'/items/'.$item_id.'/'. $image;
    if (file_exists($path)) { 
        return Response::download($path);
    }
});
//path for image from the storage
Route::get('/app/system/sale-items/item/image/{username}/{item_id}/{image}', function($username,$item_id,$image)
{
    $path = storage_path().'/app/public/'.$username.'/items/'.$item_id.'/'. $image;
    if (file_exists($path)) { 
        return Response::download($path);
    }
});
//path for user banner
Route::get('/app/system/user/{username}/banner/{image}', function($username,$image)
{
    $path = storage_path().'/app/public/'.$username.'/banner/'.$image;
    if (file_exists($path)) { 
        return Response::download($path);
    }
});

//path for user pic
Route::get('/app/system/user/{username}/profile/{image}', function($username,$image)
{
    $path = storage_path().'/app/public/'.$username.'/profile/'.$image;
    if (file_exists($path)) { 
        return Response::download($path);
    }
});
//path for user pic
Route::get('/app/system/user/{username}/profile/temp/{image}', function($username,$image)
{
    $path = storage_path().'/app/public/'.$username.'/profile/temp_img/'.$image;
    if (file_exists($path)) { 
        return Response::download($path);
    }
});

//get profile
Route::get('/app/system/user/profile','ProfileController@index');
Route::post('/app/system/user/profile/update','ProfileController@update_profile');
//get wishlists
Route::get('/app/system/wishlists','ProfileController@wishlists');
Route::post('/app/system/wishlists/delete','ProfileController@wishlists_delete');



// Route::group(['middleware' => ['CheckProfileRole']], function () {
// 	//get user
// 	Route::post('/profile/get-user', 'ProfileController@get_user');
// 	//create profile
// 	Route::post('/profile/profile-engine/add', 'ProfileController@profile_engine');
// 	Route::post('/profile/profile-engine/delete', 'ProfileController@profile_engine');
// });

//Dashboard Login
Route::get('dashboard/login', 'DashboardAuth\LoginController@showLoginForm');
Route::post('dashboard/login', 'DashboardAuth\LoginController@login');
Route::post('dashboard/logout', 'DashboardAuth\LoginController@logout');

//Dashboard Register
Route::get('dashboard/register', 'DashboardAuth\RegisterController@showRegistrationForm');
Route::post('dashboard/register', 'DashboardAuth\RegisterController@register');

//Dashboard Passwords
Route::post('dashboard/password/email', 'DashboardAuth\ForgotPasswordController@sendResetLinkEmail');
Route::post('dashboard/password/reset', 'DashboardAuth\ResetPasswordController@reset');
Route::get('dashboard/password/reset', 'DashboardAuth\ForgotPasswordController@showLinkRequestForm');
Route::get('dashboard/password/reset/{token}', 'DashboardAuth\ResetPasswordController@showResetForm');


//User Login
Route::get('user/login', 'UserAuth\LoginController@showLoginForm');
Route::post('user/login', 'UserAuth\LoginController@login');
Route::post('user/logout', 'UserAuth\LoginController@logout');

//User Register
Route::get('user/register', 'UserAuth\RegisterController@showRegistrationForm');
Route::post('user/register', 'UserAuth\RegisterController@register');

//User Passwords
Route::post('user/password/email', 'UserAuth\ForgotPasswordController@sendResetLinkEmail');
Route::post('user/password/reset', 'UserAuth\ResetPasswordController@reset');
Route::get('user/password/reset', 'UserAuth\ForgotPasswordController@showLinkRequestForm');
Route::get('user/password/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');

