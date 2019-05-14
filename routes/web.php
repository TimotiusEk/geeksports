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

Route::get('/', 'UserController@showLoginPage');

Route::get('/home', 'EventController@showHomePage');

Route::post('/login', 'UserController@login');

Route::get('/registration', 'UserController@showRegistrationForm');
Route::post('/registration_2', 'UserController@showNextRegistrationForm');
Route::post('/registration_done', 'UserController@submitRegistrationForm');

Route::get('/search_event_location', 'EventController@showSearchEventLocationPage');
//Route::get('/profile', function () {
//    return view('profile');
//});
//Route::get('/create_event', function () {
//    return view('create_event');
//});

Route::get('/create_event', 'EventController@showCreateEventForm');
Route::post('/create_event', 'EventController@showCreateEventForm');

Route::post('/manage_news', 'EventController@showManageNewsForm');
//Route::get('/manage_event', function () {
//    return view('manage_event');
//});

Route::get('/my_event', 'EventController@showMyEventPage');


Route::post('/write_news', 'EventController@showWriteNewsPage');

Route::get('/get_news/{id}', 'NewsController@getAllWithCondition');

Route::post('/manage_vacancy', 'EventController@showManageVacancyPage');

//Route::get('/add_vacancy', 'EventController@addVacancyPage');
Route::post('/add_vacancy', 'EventController@addVacancyPage');
Route::post('/update_vacancy', 'EventController@updateVacancyPage');
Route::get('/event_details', 'EventController@showEventDetails');
Route::post('/manage_sponsorship_package', 'EventController@showManageSponsorshipPackagePage');
Route::post('/add_package', 'EventController@addPackagePage');
Route::get('/sponsor_status', function () {
    return view('sponsor_status');
});

Route::post('/sponsor_status', 'EventController@sponsorStatusPage');
Route::post('/vacancy_status', 'EventController@vacancyStatusPage');
Route::get('/participant_registration_form', 'EventController@showParticipantRegistrationForm');
Route::post('/participant_registration_form', 'EventController@showParticipantRegistrationForm');
Route::get('/manage_participant', 'EventController@showManageParticipantPage');
Route::post('/manage_participant', 'EventController@showManageParticipantPage');
Route::get('/search_player', 'UserController@searchPlayer');
//Route::post('/register_for_event', 'EventController@registerForEvent');
Route::post('/sponsor_search_result', 'EventController@showSponsorSearchResultPage');
Route::post('/worker_search_result', 'EventController@showWorkerSearchResultPage');
Route::get('/search_game', 'EventController@searchGame');
Route::post('/delete_event', 'EventController@deleteEvent');
Route::post('/delete_vacancy', 'EventController@deleteVacancy');
Route::post('/delete_package', 'EventController@deletePackage');
Route::post('/delete_news', 'EventController@deleteNews');
Route::post('/update_package', 'EventController@updatePackagePage');
Route::post('/update_event_form', 'EventController@showUpdateEventForm');
Route::post('/update_news', 'EventController@updateNews');
Route::post('/publish_news', 'EventController@publishNews');
Route::get('/search_city', 'EventController@searchCity');
Route::post('/invite_worker', 'EventController@inviteWorker');
Route::post('/broadcast_package', 'EventController@broadcastPackage');
Route::get('/logout', 'UserController@logout');
Route::get('/vacancy', 'EventController@showVacancyPage');
Route::get('/vacancy_registration_form', 'EventController@showVacancyRegistrationForm');
Route::post('/vacancy_registration', 'EventController@vacancyRegistration');

Route::get('/read_news', 'EventController@readNews');

Route::get('/sponsorship', 'EventController@showSponsorshipPage');
Route::post('/sponsorship', 'EventController@showSponsorshipPage');
Route::post('/gamer_search_result', 'EventController@showGamerSearchResultPage');
Route::post('/invite_gamer', 'EventController@inviteGamer');
Route::get('/participant_status', 'EventController@showParticipantStatusPage');
Route::post('/post_comment', 'EventController@postComment');
Route::post('/rate_event', 'EventController@rateEvent');
Route::get('/add_winner', 'EventController@showinputWinnerForm');
Route::post('/add_winner', 'EventController@inputWinnerToDatabase');
Route::get('/search_team', 'EventController@searchTeam');
Route::get('/manage_winner', 'EventController@showManageWinnerPage');
Route::post('/delete_winner', 'EventController@deleteWinner');
Route::get('/update_winner', 'EventController@showUpdateWinnerPage');
Route::post('/update_winner', 'EventController@updateWinner');
Route::get('/add_streaming_channel', 'EventController@showAddStreamingChannelForm');
Route::post('/add_streaming_channel', 'EventController@addStreamingChannelToDB');
Route::get('/manage_streaming_channel', 'EventController@showManageStreamingChannelPage');
Route::post('/delete_streaming_channel', 'EventController@deleteStreamingChannel');
Route::get('/update_streaming_channel', 'EventController@showUpdateStreamingChannelPage');
Route::post('/update_streaming_channel', 'EventController@updateStreamingChannel');
Route::post('/publish_event', 'EventController@publishEvent');
Route::post('/unpublish_event', 'EventController@unpublishEvent');
Route::post('/open_registration', 'EventController@openRegistration');
Route::post('/close_registration', 'EventController@closeRegistration');
Route::get('/update_profile', 'UserController@showUpdateProfileForm');
Route::post('/update_profile', 'UserController@updateProfile');
Route::get('/event_location_details', 'EventController@showEventLocationDetailsPage');
Route::get('/view_profile', 'UserController@showViewProfilePage');




