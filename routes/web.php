<?php



Use App\User;
Use App\Events\TaskEvent;
Use App\Notifications\TaskCompleted;


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

Auth::routes();
Route::post('/admin/approve','AdminController@change_setting');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin','AdminController@begins')->name('admin');
Route::post('/admin/editUser','AdminController@editUser');
Route::get('/users','UserController@view');
Route::post('/workshop/login','WorkshopController@loginWorkshop');
Route::post('/workshop/create','WorkshopController@CreateWorkshop');
Route::post('/workshop','WorkshopController@enterWorkshop');
Route::post('/workshop/waiting','CardController@SendCard');
Route::post('/workshop/join_workshop','WorkshopController@join_workshop');
Route::get('/workshop/start','CardController@Start');
Route::post('/store','MainController@store');
Route::put('/workshop/Moniterstart','WorkshopController@Moniterstart');
Route::post('/workshop/sendIdea','CardController@send_idea_to_database');
Route::put('/startshuffle','WorkshopController@beginshuffle');
Route::put('/workshop/submit_grade','CardController@vote');
Route::post('/monitor/end_workshop','WorkshopController@finish');
Route::post('/workshop/group','WorkshopController@distribution');




//Routes For Events:
// Route::get('/event', function(){
//     event(new TaskEvent('MyMessage'));
// });

// Route::get('/listen',function(){
//     return view('listenBroadcast');
// });


//Notification Route Example:
// Route::get('/notification',function(){
//     // User::find(3)->notify(new TaskCompleted);


//     $users= User::find(3);
//     Notification::send($users, new TaskCompleted('Please Refresh Your Page'));
// });

// Route::get('/notify', function(){
//     return view('notification');
// });

//Route To Delete Viewed Notifications:
Route::post('/markAsRead','NotificationController@markAsRead' );