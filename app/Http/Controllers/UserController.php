<?php

namespace App\Http\Controllers;

use App\City;
use App\Company;
use App\Event;
use App\EventGameConnector;
use App\Experience;
use App\Gamer;
use App\Games;
use App\EventOrganizer;
use App\Rating;
use App\SponsorshipManagement;
use App\Team;
use App\Vacancy;
use App\VacancyManagement;
use App\Worker;
use App\Industry;
use App\Participant;
use App\ParticipantManagement;
use App\ParticipatedTeam;
use App\Role;
use App\Subrole;
use App\User;
use App\UserGameConnector;
use App\CompanyIndustryConnector;
use App\UserSubroleConnector;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use DateTime;

class UserController extends Controller
{
    public function searchPlayer(Request $request)
    {
        if ($request->has('q')) {
            $keyword = $request->q;
            $data = Worker::select('user_id', 'display_name')->where('display_name', 'LIKE', '%' . $keyword . '%')->get();
            return response()->json($data);
        }
    }

    public function showRegistrationForm()
    {
        $roles = Role::get();

        return view('register', ['roles' => $roles]);
    }

    public function showNextRegistrationForm(Request $request)
    {
        $email = $request->input("email");
        $username = $request->input("username");
        $password = hash("md5", $request->input("password"));
        $role_id = $request->input("role_id");

        $role = Role::where('id', '=', $role_id)->get();
        $role_name = ($role[0])["name"];

        if ($role_name == "Event Organizer") {
            return view('register_eo', ['email' => $email, 'username' => $username, 'password' => $password, 'role' => $role[0]]);
        } else if ($role_name == "Company") {
            $industries = Industry::get();

            return view('register_company', ['industries' => $industries, 'email' => $email, 'username' => $username, 'password' => $password, 'role' => $role[0]]);
        } else if ($role_name == "Individual") {
            $games = Games::select('id', 'name')->get();
            $subroles = Subrole::get();

            return view('register_individual', ['games' => $games, 'subroles' => $subroles, 'email' => $email, 'username' => $username, 'password' => $password, 'role' => $role[0]]);
        }
    }

    public function submitRegistrationForm(Request $request)
    {
        $user = new User;
        $user->email = $request->input("email");
        $user->username = $request->input("username");
        $user->password = $request->input("password");
        $user->role_id = $request->input("role_id");

        $user->save();

        $role_name = $request->input("role_name");


        if ($role_name == "Event Organizer") {
            $eo_detail = new EventOrganizer;

            $eo_detail->display_name = $request->input("display_name");
            $eo_detail->contact_person = $request->input("contact_person");
            $eo_detail->company_name = $request->input("company_name");
            $eo_detail->user_id = $user->id;

            $eo_detail->save();
        } else if ($role_name == "Company") {
            $company = new Company;

            $company->company_name = $request->input("company_name");
            $company->contact_person = $request->input("contact_person");

            $file = $request->file('company_logo');

            if ($file != null) {
                $company->company_logo = 'logo_' . $user->id . '.' . $file->getClientOriginalExtension();
                $file->move('images/company_logo', 'logo_' . $user->id . '.' . $file->getClientOriginalExtension());
            }


            $company->user_id = $user->id;

            $industry_id = $request->input("industry_id");

            $company->save();

            foreach ($industry_id as $id) {
                $user_industry_connector = new CompanyIndustryConnector;
                $user_industry_connector->company_id = $company->id;
                $user_industry_connector->industry_id = $id;
                $user_industry_connector->save();
            }

        } else if ($role_name == "Individual") {
            $subrole_id = $request->input("subrole_id");
            $is_gamer = false;
            $is_worker = false;

            foreach ($subrole_id as $id) {
                $subrole_name = (Subrole::find($id))->name;

                if ($subrole_name == "E-Sport Player" || $subrole_name == "E-Sport Enthusiast") {
                    $is_gamer = true;
                } else {
                    $is_worker = true;
                }

                if ($is_gamer && $is_worker) {
                    break;
                }
            }


            if ($is_worker) {
                $worker = new Worker;

                $worker->display_name = $request->input("display_name");
                $worker->description = $request->input("description");
                $worker->gender = $request->input("gender");
                $worker->dob = $request->input("dob");
                $worker->city_id = $request->input("city_id");
                $worker->user_id = $user->id;

                $worker->save();
            }

            if ($is_gamer) {
                $gamer = new Gamer;

                $gamer->display_name = $request->input("display_name");
                $gamer->description = $request->input("description");
                $gamer->gender = $request->input("gender");
                $gamer->dob = $request->input("dob");
                $gamer->city_id = $request->input("city_id");
                $gamer->user_id = $user->id;

                $gamer->save();
            }


            $game_id = $request->input("game_id");
            foreach ($game_id as $id) {
                $user_game_connector = new UserGameConnector;
                $user_game_connector->user_id = $user->id;
                $user_game_connector->game_id = $id;
                $user_game_connector->save();
            }

            $subrole_id = $request->input("subrole_id");
            foreach ($subrole_id as $id) {
                $user_subrole_connector = new UserSubroleConnector;
                $user_subrole_connector->user_id = $user->id;
                $user_subrole_connector->subrole_id = $id;
                $user_subrole_connector->save();
            }
        }
        return view('register_done');
    }

    public function showLoginPage(Request $request)
    {
        if ($request->cookie("user_id") == null) {
            return view('login');
        } else {
            return redirect('http://geeksports.online/home');
        }
    }

    public function login(Request $request)
    {
        $user = User::select('id', 'role_id')->where('username', '=', $request->input('username'))->where('password', '=', hash('md5', $request->input('password')))->first();

        if (is_null($user)) {
            return response()->view('login_failed', compact('data'), 200)
                ->header("Refresh", "1;url=/");
        } else {
            Cookie::queue("user_id", $user->id, 180);
            $role = Role::select('name')->where('id', $user->role_id)->first();

            $user_subrole = UserSubroleConnector::where('user_id', $user->id)->where('subrole_id', (Subrole::select('id')->where('name', 'E-Sport Player')->first())->id)->first();

            if (is_null($user_subrole)) {
                Cookie::queue("gamer", "false", 180);
            } else {
                Cookie::queue("gamer", "true", 180);
            }


            $user_subrole = UserSubroleConnector::where('user_id', $user->id)->where('subrole_id', '!=', (Subrole::select('id')->where('name', 'E-Sport Player')->first())->id)->where('subrole_id', '!=', (Subrole::select('id')->where('name', 'E-Sport Enthusiast')->first())->id)->first();

            if (is_null($user_subrole)) {
                Cookie::queue("worker", "false", 180);
            } else {
                Cookie::queue("worker", "true", 180);
            }

            Cookie::queue("role_name", $role->name, 180);

            if ($role->name == "Event Organizer") {
                return redirect('my_event');
            }
            return redirect('home');
        }
    }

    public function logout()
    {
        Cookie::queue(Cookie::forget("user_id"));
        Cookie::queue(Cookie::forget("role_name"));
        Cookie::queue(Cookie::forget("player"));
        Cookie::queue(Cookie::forget("worker"));

        return redirect('/index.php');
    }

    public function showUpdateProfileForm()
    {
        if (Cookie::get("role_name") == "Individual") {
            $individual = null;

            $is_gamer = false;
            $is_worker = false;

            $user_subroles = UserSubroleConnector::where('user_id', Cookie::get('user_id'))->get();

            foreach ($user_subroles as $user_subrole) {

                $subrole_name = (Subrole::select('name')->find($user_subrole->subrole_id))->name;


                if ($subrole_name == "E-Sport Player") {
                    $is_gamer = true;
                } else if ($subrole_name != "E-Sport Enthusiast") {
                    $is_worker = true;
                }

                $user_subrole->save();
            }

            if ($is_gamer) {
                $individual = Gamer::where('user_id', Cookie::get('user_id'))->first();
            } else if ($is_worker) {
                $individual = Worker::where('user_id', Cookie::get('user_id'))->first();
            }


            $individual->user_subrole = UserSubroleConnector::select('subrole_id')->where('user_id', Cookie::get('user_id'))->get();

            $individual->user_game = UserGameConnector::select('game_id')->where('user_id', Cookie::get('user_id'))->get();
            for ($idx = 0; $idx < count($individual->user_game); $idx++) {
                if ($idx == 0) {
                    $individual->game = Games::select('name')->where('id', (($individual->user_game)[$idx])->game_id);
                } else {
                    $individual->game = ($individual->game)->orWhere('id', (($individual->user_game)[$idx])->game_id);
                }

                if ($idx == count($individual->user_game) - 1) {
                    $individual->game = ($individual->game)->get();
                }
            }

            $individual->city = (City::select('name')->find($individual->city_id))->name;
            return view('individual_profile', ['individual' => $individual, 'all_subroles' => Subrole::get()]);
        } else if (Cookie::get("role_name") == "Event Organizer") {
            return view('event_organizer_profile', ['event_organizer' => EventOrganizer::where('user_id', Cookie::get('user_id'))->first()]);
        } else {
            $company = Company::where('user_id', Cookie::get('user_id'))->first();
            return view('company_profile', ['company' => $company, 'all_industries' => Industry::get(), 'company_industries' => CompanyIndustryConnector::select('industry_id')->where('company_id', $company->id)->get()]);
        }
    }

    public function updateProfile(Request $request)
    {
        if (Cookie::get("role_name") == "Individual") {
            $is_gamer = false;
            $is_worker = false;

            //delete and reinput the subroles
            UserSubroleConnector::where('user_id', Cookie::get('user_id'))->delete();

            foreach ($request->subrole_id as $id) {
                $user_subrole = new UserSubroleConnector;
                $user_subrole->user_id = Cookie::get('user_id');
                $user_subrole->subrole_id = $id;
                $subrole_name = (Subrole::select('name')->find($id))->name;

                $user_subrole->save();

                if ($subrole_name == "E-Sport Player") {
                    $is_gamer = true;
                } else if ($subrole_name != "E-Sport Enthusiast") {
                    $is_worker = true;
                }
            }

            //delete and reinput the games
            UserGameConnector::where('user_id', Cookie::get('user_id'))->delete();

            foreach ($request->game_id as $id) {
                $user_game = new UserGameConnector;
                $user_game->user_id = Cookie::get('user_id');
                $user_game->game_id = $id;

                $user_game->save();
            }

            if ($is_gamer) {
                $gamer = Gamer::where('user_id', Cookie::get('user_id'))->first();
                $gamer->display_name = $request->display_name;
                $gamer->gender = $request->gender;
                $gamer->dob = $request->dob;
                $gamer->city_id = $request->city_id;
                $gamer->description = $request->description;

                if (!is_null($request->profile_picture)) {
                    $gamer->profile_picture = 'profile_picture_' . Cookie::get('user_id') . '.' . ($request->profile_picture)->getClientOriginalExtension();

                    ($request->profile_picture)->move('images/profile_picture', 'profile_picture_' . Cookie::get('user_id') . '.' . ($request->profile_picture)->getClientOriginalExtension());
                }
                $gamer->save();
            }

            if ($is_worker) {
                $worker = Worker::where('user_id', Cookie::get('user_id'))->first();

                $worker->display_name = $request->display_name;
                $worker->gender = $request->gender;
                $worker->dob = $request->dob;
                $worker->city_id = $request->city_id;
                $worker->description = $request->description;

                if (!is_null($request->profile_picture)) {
                    $worker->profile_picture = 'profile_picture_' . Cookie::get('user_id') . '.' . ($request->profile_picture)->getClientOriginalExtension();

                    ($request->profile_picture)->move('images/profile_picture', 'profile_picture_' . Cookie::get('user_id') . '.' . ($request->profile_picture)->getClientOriginalExtension());
                }
                $worker->save();
            }

        } else if (Cookie::get("role_name") == "Event Organizer") {
            $event_organizer = EventOrganizer::find($request->event_organizer_id);
            $event_organizer->display_name = $request->display_name;
            $event_organizer->contact_person = $request->contact_person;
            $event_organizer->company_name = $request->company_name;

            //upload profile picture
            if ($event_organizer->save()) {
                $profile_picture = $request->file('profile_picture');

                if ($profile_picture != null) {
                    $event_organizer->profile_picture = 'profile_picture_' . $event_organizer->id . '.' . $profile_picture->getClientOriginalExtension();

                    $profile_picture->move('images/profile_picture', 'profile_picture_' . $event_organizer->id . '.' . $profile_picture->getClientOriginalExtension());
                    $event_organizer->save();
                }


                $success = true;
            }
        } else {
            $company = Company::find($request->company_id);
            $company->company_name = $request->company_name;
            $company->contact_person = $request->contact_person;

            //delete and reinput the industries
            CompanyIndustryConnector::where('company_id', $request->company_id)->delete();

            foreach ($request->industry_id as $id) {
                $company_industry = new CompanyIndustryConnector;
                $company_industry->company_id = $request->company_id;
                $company_industry->industry_id = $id;
                $company_industry->save();
            }

            //upload company logo
            if ($company->save()) {
                $company_logo = $request->file('company_logo');

                if ($company_logo != null) {
                    $company->company_logo = 'company_logo_' . $company->id . '.' . $company_logo->getClientOriginalExtension();

                    $company_logo->move('images/company_logo', 'company_logo_' . $company->id . '.' . $company_logo->getClientOriginalExtension());
                    $company->save();
                }


                $success = true;
            }
        }

        return redirect('view_profile?user_id=' . Cookie::get('user_id'));
    }

    public function showViewProfilePage()
    {
        if (is_null(Input::get('team_id'))) {
            $role = (Role::select('name')->find((User::select('role_id')->find(Input::get('user_id')))->role_id))->name;
            $user_subroles = UserSubroleConnector::select('subrole_id')->where('user_id', Input::get('user_id'))->get();
            $edit = false;

            if (Input::get('user_id') == Cookie::get('user_id')) {
                $edit = true;
            }

            if ($role == "Individual") {

                $is_worker = false;
                $is_gamer = false;
                for ($idx = 0; $idx < count($user_subroles); $idx++) {
                    $subrole_name = (Subrole::select('name')->find(($user_subroles[$idx])->subrole_id))->name;

                    if ($subrole_name == "E-Sport Player") {
                        $is_gamer = true;
                    }

                    if ($subrole_name != "E-Sport Player" && $subrole_name != "E-Sport Enthusiast") {
                        $is_worker = true;
                    }
                }

                $user = null;

                if ($is_gamer) {
                    $user = Gamer::where('user_id', Input::get('user_id'))->first();

                    //get all user's participation as players
                    $user->experience = Experience::where('gamer_id', $user->id)->get();
                    $events = null;
                    for ($idx = 0; $idx < count($user->experience); $idx++) {
                        $event_games = EventGameConnector::find((($user->experience)[$idx])->event_game_id);

                        if ($idx == 0) {
                            $events = Event::where('id', $event_games->event_id);
                        } else {
                            $events = $events->orWhere('id', $event_games->event_id);
                        }

                        if ($idx == count($user->experience) - 1) {
                            $user->events = $events->get();

                            foreach ($user->events as $event) {
                                foreach (EventGameConnector::select('id', 'game_id')->where('event_id', $event->id)->get() as $event_game) {
                                    $experience = Experience::where('gamer_id', $user->id)->where('event_game_id', $event_game->id)->first();

                                    $game_name = (Games::select('name')->find($event_game->game_id))->name;

                                    if (!is_null($experience)) {
                                        $event->experience_type = $game_name . ' ' . $experience->type;
                                    }
                                }
                            }
                        }
                    }

                }

                if ($is_worker) {
                    $user = Worker::where('user_id', Input::get('user_id'))->first();

                    $vacancy_managements = VacancyManagement::select('vacancy_id', 'subrole_id')->where('action', 'Confirm')->where('worker_id', $user->id)->get();

                    $events = null;
                    for ($idx = 0; $idx < count($vacancy_managements); $idx++) {
                        $event_id = (Vacancy::select('event_id')->find(($vacancy_managements[$idx])->vacancy_id))->event_id;

                        if ($idx == 0) {
                            $events = Event::where('id', $event_id);
                        } else {
                            $events = $events->orWhere('id', $event_id);
                        }

                        if ($idx == count($vacancy_managements) - 1) {
                            $events = $events->get();
                            $user->events = $events;
                        }
                    }

                    if (!is_null($user->events)) {
                        foreach ($user->events as $event) {
                            $vacancy_managements = VacancyManagement::select('vacancy_id', 'subrole_id')->where('action', 'Confirm')->where('worker_id', $user->id)->where('vacancy_id', (Vacancy::select('id')->where('event_id', $event->id)->first())->id)->get();

                            for ($idx = 0; $idx < count($vacancy_managements); $idx++) {
                                if ($idx == 0) {
                                    $event->subrole = (Subrole::select('name')->find(($vacancy_managements[$idx])->subrole_id))->name;
                                } else {
                                    $event->subrole .= "," . (Subrole::select('name')->find(($vacancy_managements[$idx])->subrole_id))->name;
                                }
                            }
                        }
                    }
                }

                if ($user->dob != null) {
                    $new_date = date('d F Y', strtotime($user->dob));
                    $user->dob = $new_date;
                }

                if (!is_null($user->city_id)) {
                    $user->city = (City::select('name')->find($user->city_id))->name;
                }

                //get all user's subroles (commentator, player, etc)
                for ($idx = 0; $idx < count($user_subroles); $idx++) {
                    $subrole_name = (Subrole::select('name')->find(($user_subroles[$idx])->subrole_id))->name;

                    if ($idx == 0) {
                        $user->subrole = $subrole_name;
                    } else {
                        $user->subrole .= ", " . $subrole_name;
                    }
                }

                $user_games = UserGameConnector::select('game_id')->where('user_id', Input::get('user_id'))->get();

                //get all games
                $games = null;
                for ($idx = 0; $idx < count($user_games); $idx++) {
                    if ($idx == 0) {
                        $games = Games::select('logo')->where('id', ($user_games[$idx])->game_id);
                    } else {
                        $games = $games->orWhere('id', ($user_games[$idx])->game_id);
                    }

                    if ($idx == count($user_games) - 1) {
                        $games = $games->get();
                        $user->games = $games;
                    }
                }

                return view('view_profile_individual', ['user' => $user, 'edit' => $edit]);
            } else if ($role == "Event Organizer") {
                $user = EventOrganizer::where('user_id', Input::get('user_id'))->first();

                $user->events = Event::where('event_organizer_id', $user->id)->where('status', 'Published')->get();

                foreach ($user->events as $event) {
                    $rating = Rating::where('event_id', $event->id)->avg('rating');

                    $event->rating = round((double)$rating, 2);
                    $event->no_of_user_rate = Rating::where('event_id', $event->id)->count('id');
                }

                return view('view_profile_event_organizer', ['user' => $user, 'edit' => $edit]);
            } else if ($role == "Company") {
                $user = Company::where('user_id', Input::get('user_id'))->first();

                //get all company industries
                $company_industries = CompanyIndustryConnector::select('industry_id')->where('company_id', $user->id)->get();

                for ($idx = 0; $idx < count($company_industries); $idx++) {
                    $industry_name = (Industry::select('name')->find(($company_industries[$idx])->industry_id))->name;
                    if ($idx == 0) {
                        $user->industry = $industry_name;
                    } else {
                        $user->industry .= ', ' . $industry_name;
                    }
                }

                //get all sponsored events
                $sponsorship_managements = SponsorshipManagement::select('event_id')->where('action', 'Deal')->where('company_id', $user->id)->get();
                $events = null;
                for ($idx = 0; $idx < count($sponsorship_managements); $idx++) {
                    if ($idx == 0) {
                        $events = Event::where('id', ($sponsorship_managements[$idx])->event_id);
                    } else {
                        $events = $events->orWhere('id', ($sponsorship_managements[$idx])->event_id);
                    }

                    if ($idx == count($sponsorship_managements) - 1) {
                        $events = $events->get();
                        $user->events = $events;

                        foreach ($user->events as $event) {
                            $rating = Rating::where('event_id', $event->id)->avg('rating');

                            $event->rating = round((double)$rating, 2);
                            $event->no_of_user_rate = Rating::where('event_id', $event->id)->count('id');
                        }
                    }
                }


                return view('view_profile_company', ['user' => $user, 'edit' => $edit]);
            }
        } else {
            $team = Team::find(Input::get("team_id"));
            $participant_managements = ParticipantManagement::select('gamer_id')->where('team_id', $team->id)->where('action', 'register')->get();
            $gamer = null;

            foreach($participant_managements as $participant_management) {
                if(is_null($gamer)){
                    $gamer = Gamer::where("id", $participant_management->gamer_id);
                } else {
                    $gamer = $gamer->orWhere("id", $participant_management->gamer_id);
                }
            }

            $team->members = $gamer->get();


            foreach ($team->members as $member) {
                //get games for related member
                $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $member->user_id)->get();
                $member->games = null;

                foreach ($users_games_connector as $user_games) {
                    $games = Games::select('name')->find($user_games->game_id);

                    if(is_null($member->games)){
                        $member->games = $games->name;
                    } else {
                        $member->games .= $games->name;
                    }
                    $member->games .= ", ";
                }
                $member->games = rtrim($member->games, ", ");

                //get age for related member
                $member->age = null;
                if (!is_null($member->dob)) {
                    $d1 = new DateTime($member->dob);
                    $d2 = new DateTime();

                    $diff = $d2->diff($d1);

                    $member->age = $diff->y;
                }

                //get member's city
                $city = City::select('name')->find($member->city_id);

                if (!is_null($city)) {
                    $member->city = $city->name;
                }
            }





            return view('view_profile_team', ['team' => $team]);
        }
    }
}