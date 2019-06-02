<?php

namespace App\Http\Controllers;

use App\City;
use App\Comment;
use App\Company;
use App\EventLocation;
use App\EventLocationMedia;
use App\EventOrganizer;
use App\Event;
use App\EventGameConnector;
use App\Experience;
use App\Gamer;
use App\Games;

use App\Http\StreamingChannel;
use App\Rating;
use App\Team;
use App\ParticipantManagement;
use App\Winner;
use App\Worker;
use App\Industry;
use App\News;
use App\Role;
use App\SponsorshipManagement;
use App\SponsorshipPackage;
use App\Subrole;
use App\User;
use App\UserGameConnector;
use App\CompanyIndustryConnector;
use App\UserSubroleConnector;
use App\Vacancy;
use App\VacancyManagement;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    public function sponsorStatusPage(Request $request)
    {
        if ($request->action != null) {
            $sponsor_management = new SponsorshipManagement;
            $sponsor_management->event_id = $request->event_id;
            $sponsor_management->company_id = $request->company_id;
            $sponsor_management->action = $request->action;
            $sponsor_management->save();
        }

        $companies = array();
        $companies_idx = 0;

        $sponsor_managements = SponsorshipManagement::where("event_id", $request->event_id)->get();

        $company_industries = array();
        $company_industries_idx = 0;

        foreach ($sponsor_managements as $sponsor_management) {
            if ($sponsor_management->action == "Invite" && !is_null(SponsorshipManagement::where("event_id", $sponsor_management->event_id)->where("company_id", $sponsor_management->company_id)->where("action", "!=", "Invite")->first())) {
                $sponsor_management->action = "";
            }

            if ($sponsor_management->action == "Interested" && !is_null(SponsorshipManagement::where("event_id", $sponsor_management->event_id)->where("company_id", $sponsor_management->company_id)->where("action", "Deal")->first())) {
                $sponsor_management->action = "";
            }

            if ($sponsor_management->action != "") {
                $company = Company::find($sponsor_management->company_id);
                $companies[$companies_idx] = $company;
                $companies_idx++;

                $company_industry_connector = CompanyIndustryConnector::where("company_id", $sponsor_management->company_id)->get();

                //get industries by company id
                for ($idx = 0; $idx < count($company_industry_connector); $idx++) {
                    $industry = Industry::where("id", ($company_industry_connector[$idx])->industry_id)->first();

                    if ($idx == 0) {
                        $company_industries[$company_industries_idx] = "";
                    }

                    $company_industries[$company_industries_idx] .= $industry->name;
                    $company_industries[$company_industries_idx] .= ", ";
                }

                $company_industries[$company_industries_idx] = rtrim($company_industries[$company_industries_idx], ", ");
                $company_industries_idx++;
            }
        }


        return view('sponsor_status', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'sponsor_managements' => $sponsor_managements, 'companies' => $companies, 'company_industries' => $company_industries]);
    }

    public function addVacancyPage(Request $request)
    {
        $subroles = Subrole::where('name', '!=', 'E-Sport Enthusiast')->where('name', '!=', 'E-Sport Player')->get();
        if ($request->subrole_id == null) {
            return view('add_vacancy', ['subroles' => $subroles, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        } else {
            $vacancies = new Vacancy;
            foreach ($request->subrole_id as $id) {
                $subrole = Subrole::where('id', $id)->first();

                $subrole_name = str_replace(" ", "_", strtolower($subrole->name));


                $vacancies->$subrole_name = true;
            }

            $vacancies->description = $request->description;
            $vacancies->event_id = $request->event_id;

            if ($vacancies->save()) {
                return view('add_vacancy', ['success' => true, 'subroles' => $subroles, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
            }
        }
    }

    public function addPackagePage(Request $request)
    {
        if ($request->package_name == null) {
            return view('add_package', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        } else {
            $package = new SponsorshipPackage;

            $package->package_name = $request->package_name;
            $package->sponsor_rights = $request->sponsor_rights;
            $package->sponsor_obligations = $request->sponsor_obligations;
            $package->event_id = $request->event_id;

            if ($package->save()) {
                return view('add_package', ['success' => true, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
            }
        }
    }

    public function updateVacancyPage(Request $request)
    {
        $subroles = Subrole::where('name', '!=', 'E-Sport Enthusiast')->where('name', '!=', 'E-Sport Player')->get();
        if ($request->subrole_id == null) {
            $vacant_roles = explode(", ", $request->vacant_roles);

            return view('update_vacancy', ['subroles' => $subroles, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'vacant_roles' => $vacant_roles, 'description' => $request->description]);
        } else {
            $vacancies = Vacancy::where('event_id', '=', $request->event_id)->first();
            $vacant_roles = array();
            $idx = 0;

            foreach ($subroles as $subrole) {
                $subrole_name = str_replace(" ", "_", strtolower($subrole->name));
                $vacancies->$subrole_name = false;
            }

            foreach ($request->subrole_id as $id) {
                $subrole = Subrole::where('id', $id)->first();
                $subrole_name = str_replace(" ", "_", strtolower($subrole->name));

                $vacancies->$subrole_name = true;
                $vacant_roles[$idx] = $subrole->name;


                $idx++;
            }


            $vacancies->description = $request->description;

            if ($vacancies->save()) {
                return view('update_vacancy', ['success' => true, 'subroles' => $subroles, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'vacant_roles' => $vacant_roles, 'description' => $request->description]);
            }
        }
    }

    public function updatePackagePage(Request $request)
    {
        $package = SponsorshipPackage::find($request->package_id);
        if ($request->package_name == null) {
            return view('update_package', ['package' => $package, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        } else {
            $package->package_name = $request->package_name;
            $package->sponsor_rights = $request->sponsor_rights;
            $package->sponsor_obligations = $request->sponsor_obligations;
            $package->event_id = $request->event_id;

            if ($package->save()) {
                return view('update_package', ['success' => true, 'package' => $package, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
            }
        }
    }


    public function vacancyStatusPage(Request $request)
    {
        $vacancy = Vacancy::where("event_id", $request->event_id)->first();

        if ($request->action != null) {
            $vacancy_management = new VacancyManagement;
            $vacancy_management->action = $request->action;
            $vacancy_management->message = $request->message;
            $vacancy_management->vacancy_id = $vacancy->id;
            $vacancy_management->worker_id = $request->worker_id;
            $vacancy_management->subrole_id = $request->subrole_id;

            $vacancy_management->save();
        }

        $vacancy_managements = VacancyManagement::where("vacancy_id", $vacancy->id)->get();

        $user_subroles = array();
        $user_subroles_idx = 0;

        $user_games = array();
        $user_games_idx = 0;


        $workers = array();
        $workers_idx = 0;

        foreach ($vacancy_managements as $vacancy_management) {
            $worker = Worker::find($vacancy_management->worker_id);

            $worker->age = null;
            if (!is_null(($worker)->dob)) {
                $d1 = new DateTime($worker->dob);
                $d2 = new DateTime();
                $diff = $d2->diff($d1);

                $worker->age = $diff->y;
            }


            $city = City::select('name')->find($worker->city_id);
            $worker->city = null;

            if (!is_null($city)) {
                $worker->city = $city->name;
            }

            $user_id = (Worker::find($vacancy_management->worker_id))->user_id;

            $user_subroles[$user_subroles_idx] = "";
            if ($vacancy_management->action == "Invite") {
                //get subrole for each users
                $user_subrole_connector = UserSubroleConnector::where('user_id', $user_id)->get();

                for ($idx = 0; $idx < count($user_subrole_connector); $idx++) {
                    $subrole = Subrole::select('name')->where("id", ($user_subrole_connector[$idx])->subrole_id)->first();

                    $user_subroles[$user_subroles_idx] .= $subrole->name;
                    $user_subroles[$user_subroles_idx] .= ", ";
                }

                $user_subroles[$user_subroles_idx] = rtrim($user_subroles[$user_subroles_idx], ", ");

                //check if invitation has been responded
                $check_if_responded = VacancyManagement::where("worker_id", $vacancy_management->worker_id)->where("vacancy_id", $vacancy->id)->where("action", "!=", "Invite")->first();

                if (is_null($check_if_responded)) {
                    $workers[$workers_idx] = $worker;
                    $workers_idx++;
                } else {
                    $vacancy_management->action = "";
                    $user_subroles_idx--;
                }
            } else {
                if ($vacancy_management->action == "Register") {
                    //check if registration has been responded
                    $check_if_responded = VacancyManagement::where("worker_id", $vacancy_management->worker_id)->where("vacancy_id", $vacancy->id)->where("action", "!=", "Invite")->where("action", "!=", "Register")->where('subrole_id', $vacancy_management->subrole_id)->first();

                    if (is_null($check_if_responded)) {
                        $workers[$workers_idx] = $worker;
                        $workers_idx++;
                    } else {
                        $vacancy_management->action = "";
                        $user_subroles_idx--;
                    }
                } else {
                    $workers[$workers_idx] = $worker;
                    $workers_idx++;
                }

//                $registered_subroles = VacancyManagement::where("worker_id", $vacancy_management->worker_id)->where("vacancy_id", $vacancy->id)->where("action", $vacancy_management->action)->get();
                $user_subroles[$user_subroles_idx] = "";
//                foreach ($registered_subroles as $registered_subrole) {
                $user_subroles[$user_subroles_idx] .= (Subrole::select('name')->find($vacancy_management->subrole_id))->name . ", ";
//                }
                $user_subroles[$user_subroles_idx] = rtrim($user_subroles[$user_subroles_idx], ", ");
            }


            $user_subroles_idx++;

            //get game for each users
            $user_game_connector = UserGameConnector::where('user_id', $user_id)->get();

            for ($idx = 0; $idx < count($user_game_connector); $idx++) {
                $game = Games::select('name')->where("id", ($user_game_connector[$idx])->game_id)->first();
                if ($idx == 0) {
                    $user_games[$user_games_idx] = "";

                }

                $user_games[$user_games_idx] .= $game->name;
                $user_games[$user_games_idx] .= ", ";
            }

            if (count($user_games) != 0) {
                $user_games[$user_games_idx] = rtrim($user_games[$user_games_idx], ", ");
            }
            $user_games_idx++;
        }

        return view('vacancy_status', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'vacancy_managements' => $vacancy_managements, 'workers' => $workers, 'user_subroles' => $user_subroles, 'user_games' => $user_games]);
    }

    public function showWorkerSearchResultPage(Request $request)
    {
        $idx = 0;
        $subrole_name = array();
        $user_games = array();
        $game_name_idx = 0;
        $user_subrole_connector = null;
        $users_games_connector = null;
        $game_id = array();
        $game_name = array();
        $user_id = array();
        $user_id_idx = 0;
        $subroles = array();
        $subroles_idx = 0;
        $display_name = array();

        $profile_picture = array();

        $user_gender = array();

        $age = array();

        $user_city = array();

        $status = array(); //invited or not
        $status_idx = 0;
        $all_subroles = Subrole::where('name', '!=', 'E-Sport Enthusiast')->where('name', '!=', 'E-Sport Player')->get();


        //search by subrole
        if ($request->subrole_id != null) {
            $user_subrole_connector = UserSubroleConnector::select('user_id');

            //get all subrole name from searched id (param)
            foreach ($request->subrole_id as $id) {
                $subrole = Subrole::select('name')->where('id', $id)->get();

                $subrole_name[$idx] = ($subrole[0])["name"];

                if ($idx == 0) {
                    $user_subrole_connector = $user_subrole_connector->where("subrole_id", $id);
                } else {
                    $user_subrole_connector = $user_subrole_connector->orWhere("subrole_id", $id);
                }
                $idx++;
            }

            $user_subrole_connector = $user_subrole_connector->distinct()->get();


            foreach ($user_subrole_connector as $user) {
                $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $user->user_id)->get();

                $worker = Worker::select('id');
                if (!is_null($request->city_id)) {
                    $worker = $worker->where('city_id', $request->city_id);
                } else if (!is_null($request->gender)) {
                    $worker = $worker->where('gender', $request->gender);
                }

                if (!is_null($worker->where('user_id', $user->user_id)->first())) {
                    $user_id[$user_id_idx] = $user->user_id;
                    $user_id_idx++;

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }


        //search by games
        if ($request->game_id != null) {
            $idx = 0;

            //get all game name from searched id (param)
            foreach ($request->game_id as $id) {
                $games = Games::select('name')->where('id', '=', $id)->get();


                $game_id[$idx] = $id;
                $game_name[$idx] = ($games[0])["name"];
                $idx++;
            }

            if ($request->subrole_id != null) {
                for ($idx = 0; $idx < count($user_games); $idx++) {
                    $found = false;
                    foreach ($game_name as $name) {
                        if (strpos($user_games[$idx], $name) !== false) {
                            $found = true;
                        }
                    }

                    if (!$found) {
                        unset($user_games[$idx]);
                        unset($user_id[$idx]);
                    }
                }

                $user_games = $this->reindexing($user_games);
                $user_id = $this->reindexing($user_id);
            } else {
                foreach ($request->game_id as $id) {
                    $ids = UserGameConnector::select('user_id')->where('game_id', $id)->get();
                    foreach ($ids as $id_) {
                        $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $id_->user_id)->get();

                        foreach ($user_subrole_connector as $value) {
                            $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                            if ($subrole != "E-Sport Player" && $subrole != "E-Sport Enthusiast") {
                                $user_id[$user_id_idx] = $id_->user_id;
                                $user_id_idx++;
                            }
                        }
                    }
                }

                //get games for related user_id
                foreach ($user_id as $id) {
                    $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id->user_id)->get();

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game_) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game_->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }


        //search by keyword
        if ($request->keyword != "") {
            if (count($user_id) != 0) {
                $no_of_users = count($user_id);
                for ($idx = 0; $idx < $no_of_users; $idx++) {
                    $worker = Worker::select('display_name')->where('user_id', ($user_id[$idx]))->first();

                    $found = false;

                    if (strpos($worker->display_name, $request->keyword) !== false) {
                        $found = true;
                    }

                    if (!$found) {
                        unset($user_games[$idx]);
                        unset($user_id[$idx]);
                    }


                }

                $this->reindexing($user_games);
                $this->reindexing($user_id);
            } else {

                $workers = Worker::select('user_id')->where('display_name', 'like', '%' . $request->keyword . '%')->get();

                foreach ($workers as $worker) {
                    $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $worker->user_id)->get();

                    foreach ($user_subrole_connector as $value) {
                        $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                        if ($subrole != "E-Sport Player" && $subrole != "E-Sport Enthusiast") {
                            $user_id[$user_id_idx] = $worker->user_id;
                            $user_id_idx++;
                        }
                    }
                }

                //get games for related user_id
                foreach ($user_id as $id) {
                    $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game_) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game_->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }

        if ($request->subrole_id == null && $request->game_id == null && $request->keyword == "" && is_null($request->gender) && is_null($request->city_id)) {
            $workers = Worker::select('user_id')->get();

            foreach ($workers as $worker) {
                $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $worker->user_id)->get();

                foreach ($user_subrole_connector as $value) {
                    $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                    if ($subrole != "E-Sport Player" && $subrole != "E-Sport Enthusiast") {
                        $user_id[$user_id_idx] = $worker->user_id;
                        $user_id_idx++;
                    }
                }
            }

            //get games for related user_id
            foreach ($user_id as $id) {
                $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                foreach ($users_games_connector as $game) {
                    $games = Games::select('name')->where("id", $game->game_id)->get();


                    foreach ($games as $game_) {
                        if (!isset($user_games[$game_name_idx])) {
                            $user_games[$game_name_idx] = "";
                        }

                        $user_games[$game_name_idx] .= $game_->name;
                        $user_games[$game_name_idx] .= ", ";
                    }
                }

                $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                $game_name_idx++;
            }
        }


//        subrole based on selected users
        if (count($user_id) != 0) {
            foreach ($user_id as $id) {

                $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $id)->get();
                foreach ($user_subrole_connector as $user_subroles) {
                    $subrole = Subrole::select('name')->where('id', $user_subroles->subrole_id)->first();
                    if (!isset($subroles[$subroles_idx])) {
                        $subroles[$subroles_idx] = "";
                    }

                    $subroles[$subroles_idx] .= $subrole->name;
                    $subroles[$subroles_idx] .= " - ";


                }
                $subroles[$subroles_idx] = rtrim($subroles[$subroles_idx], " - ");
                $subroles_idx++;

            }

        }

//        display name based on selected users
        if (count($user_id) != 0) {
            $idx = 0;
            foreach ($user_id as $id) {
                $worker = Worker::select('display_name', 'profile_picture', 'gender', 'dob', 'city_id')->where('user_id', $id)->first();

                $display_name[$idx] = $worker->display_name;

                $profile_picture[$idx] = $worker->profile_picture;

                $user_gender[$idx] = $worker->gender;

                $age[$idx] = null;
                if (!is_null($worker->dob)) {
                    $d1 = new DateTime($worker->dob);
                    $d2 = new DateTime();

                    $diff = $d2->diff($d1);

                    $age[$idx] = $diff->y;
                }

                $user_city[$idx] = City::select('name')->find($worker->city_id);

                if (!is_null($user_city[$idx])) {
                    $user_city[$idx] = ($user_city[$idx])->name;
                }

                $idx++;
            }

        }


//        status (invited or not)
        if (count($user_id) != 0) {

            foreach ($user_id as $id) {
                $vacancy_id = (Vacancy::where('event_id', $request->event_id)->first())->id;

                $worker = Worker::where('user_id', $id)->first();

                $vacancy_management = VacancyManagement::select('id')->where('action', "Invite")->where('worker_id', $worker->id)->where('vacancy_id', $vacancy_id)->first();
                $check_if_registered = VacancyManagement::select('id')->where('action', "Register")->where('worker_id', $worker->id)->where('vacancy_id', $vacancy_id)->first();
                if (is_null($vacancy_management) && is_null($check_if_registered)) {
                    $status[$status_idx] = null;
                } elseif (!is_null($check_if_registered)) {
                    $status[$status_idx] = "Register";
                } else {
                    $status[$status_idx] = "Invite";
                }


                $status_idx++;
            }
        }

        $gender = "Any";

        if ($request->gender == "m") {
            $gender = "Male";
        } else if ($request->gender == "f") {
            $gender = "Female";
        }

        $city = City::select('id', 'name')->find($request->city_id);

        if (is_null($city)) {
            $city = new City;
            $city->name = "Any";
        }


        return view('worker_search_result', ['all_subroles' => $all_subroles, 'subroles' => $subroles, 'subrole_name' => $subrole_name, 'game_id' => $game_id, 'game_name' => $game_name, 'gender' => $gender, 'city' => $city, 'keyword' => $request->keyword, 'display_names' => $display_name, 'age' => $age, 'user_gender' => $user_gender, 'user_city' => $user_city, 'user_games' => $user_games, 'user_id' => $user_id, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'subrole_id' => $request->subrole_id, 'game_id' => $request->game_id, 'status' => $status, 'profile_picture' => $profile_picture]);
    }


    public function showSponsorSearchResultPage(Request $request)
    {
        $industry_name = array();
        $industry_idx = 0;
        $companies = null;
        $companies_collection = array();
        $companies_collection_idx = 0;
        $industries = array();
        $industries_idx = 0;
        $status = array();
        $status_idx = 0;
        $all_industries = Industry::get();
        //get all searched industry name based on its id and get all company according to that industry
        if ($request->industry_id != null) {
            foreach ($request->industry_id as $id) {
                $industry = Industry::select('name')->where('id', '=', $id)->get();

                $industry_name[$industry_idx] = ($industry[0])["name"];
                $industry_idx++;

                $company_industry_connector = CompanyIndustryConnector::select('company_id')->where('industry_id', $id)->get();
                $companies = null;
                for ($idx = 0; $idx < count($company_industry_connector); $idx++) {
                    if ($idx == 0) {
                        $companies = Company::where("id", ($company_industry_connector[$idx])->company_id);
                    } else {
                        $companies = $companies->orWhere("id", ($company_industry_connector[$idx])->company_id);
                    }

                    if ($idx == count($company_industry_connector) - 1) {
                        $companies = $companies->get();
                    }
                }

                if (!is_null($companies)) {
                    $companies_collection[$companies_collection_idx] = $companies;
                    $companies_collection_idx++;

                    //get company based on keyword
                    if ($request->keyword != null) {
                        $no_of_collections = count($companies_collection[$companies_collection_idx - 1]);
                        for ($loop = 0; $loop < $no_of_collections; $loop++) {
                            if (count($companies_collection[$companies_collection_idx - 1]) != 0) {
                                $found = false;

                                if (strpos(strtolower((($companies_collection[$companies_collection_idx - 1])[0])->company_name), strtolower($request->keyword)) !== false) {
                                    $found = true;
                                }

                                if (!$found) {
                                    unset(($companies_collection[$companies_collection_idx - 1])[0]);
                                    $companies_collection[$companies_collection_idx - 1] = $this->reindexing($companies_collection[$companies_collection_idx - 1]);
                                }
                            }
                        }
                    }
                }
            }
        }


        if (count($companies_collection) > 1) {
            for ($idx_1 = 0; $idx_1 < count($companies_collection); $idx_1++) {
                for ($idx_2 = $idx_1 + 1; $idx_2 < count($companies_collection); $idx_2++) {
                    foreach ($companies_collection[$idx_1] as $companies_1) {
                        for ($idx_3 = 0; $idx_3 < count($companies_collection[$idx_2]); $idx_3++) {
                            if ($companies_1->company_name == (($companies_collection[$idx_2])[$idx_3])->company_name) {
                                unset(($companies_collection[$idx_2])[$idx_3]);
                                $companies_collection[$idx_2] = $this->reindexing($companies_collection[$idx_2]);
                            }
                        }
                    }
                }
            }
        }


//        get all industries according to companies
        foreach ($companies_collection as $company) {
            for ($idx = 0; $idx < count($company); $idx++) {
                if (isset($company[$idx])) {
                    $company_industry_connector = CompanyIndustryConnector::select('industry_id')->where('company_id', ($company[$idx])->id)->get();

                    for ($idx_2 = 0; $idx_2 < count($company_industry_connector); $idx_2++) {

                        if ($idx_2 == 0) {
                            $industries[$industries_idx] = "";
                        }

                        $industry = Industry::select('name')->where('id', '=', ($company_industry_connector[$idx_2])->industry_id)->first();


                        $industries[$industries_idx] .= $industry->name;
                        $industries[$industries_idx] .= ", ";
                    }

                    if (count($industries) != 0) {
                        $industries[$industries_idx] = rtrim($industries[$industries_idx], ", ");
                        $industries_idx++;
                    }
                }
            }
        }


        //status (invited or not)
        foreach ($companies_collection as $company) {
            for ($idx = 0; $idx < count($company); $idx++) {
                $sponsor_management = SponsorshipManagement::select('id')->where('action', "Invite")->where('company_id', ($company[$idx])->id)->first();

                if ($sponsor_management == null) {
                    $status[$status_idx] = null;
                } else {
                    $status[$status_idx] = "Invite";
                }

                $status_idx++;

            }
        }

        return view('sponsor_search_result', ['industry_name' => $industry_name, 'keyword' => $request->keyword, 'companies_collection' => $companies_collection, 'industries' => $industries, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'industry_id' => $request->industry_id, 'status' => $status, 'all_industries' => $all_industries]);
    }

    public function searchGame(Request $request)
    {
        if ($request->has('q')) {
            $keyword = $request->q;
            $games = Games::select('id', 'name')->where('name', 'LIKE', '%' . $keyword . '%');

            if ($request->has('event_id')) {
                $event_game_connector = EventGameConnector::select('game_id')->where('event_id', $request->event_id)->get();

                for ($idx = 0; $idx < count($event_game_connector); $idx++) {
                    if ($idx == 0) {
                        $games = $games->where('id', ($event_game_connector[$idx])->game_id);
                    } else {
                        $games = $games->orWhere('id', ($event_game_connector[$idx])->game_id);
                    }
                }
            }

            $games = $games->get();

            return response()->json($games);
        } else {
            $games = Games::select('id', 'name');

            if ($request->has('event_id')) {
                $event_game_connector = EventGameConnector::select('game_id')->where('event_id', $request->event_id)->get();

                for ($idx = 0; $idx < count($event_game_connector); $idx++) {
                    if ($idx == 0) {
                        $games = $games->where('id', ($event_game_connector[$idx])->game_id);
                    } else {
                        $games = $games->orWhere('id', ($event_game_connector[$idx])->game_id);
                    }
                }
            }

            $games = $games->get();

            return response()->json($games);
        }
    }

    public function showCreateEventForm(Request $request)
    {
        //cannot create event if not logged in
        $this->checkIfCookieExists();

        $eo_details = EventOrganizer::select("id")->where("user_id", Cookie::get('user_id'))->get();

        if ($request->event_name != null) {
            $event = new Event;
            $event->name = $request->event_name;
            $event->city_id = $request->city_id;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->details = $request->details;
            $event->brochure = null;
            $event->status = $request->status;
            $event->event_organizer_id = ($eo_details[0])["id"];

            if (is_null($request->participant_registration)) {
                $request->participant_registration = false;
            } else {
                $request->participant_registration = true;
            }

            $success = false;
            if ($event->save()) {
                $brochure = $request->file('brochure');

                if ($brochure != null) {
                    $event->brochure = 'brochure_' . $event->id . '.' . $brochure->getClientOriginalExtension();

                    $brochure->move('images/event_brochure', 'brochure_' . $event->id . '.' . $brochure->getClientOriginalExtension());
                    $event->save();
                }

                $game_id = $request->game_id;

                if ($game_id != null) {
                    foreach ($game_id as $id) {
                        $event_game_connector = new EventGameConnector;
                        $event_game_connector->game_id = $id;
                        $event_game_connector->event_id = $event->id;
                        $event_game_connector->save();
                    }
                }

                $success = true;
            }

            return view('create_event', ['success' => $success]);
        }
        return view('create_event');
    }

    public function showMyEventPage()
    {
        //cannot create event if not logged in
        $this->checkIfCookieExists();

        $eo_details = EventOrganizer::select("id")->where("user_id", Cookie::get('user_id'))->get();

        $events = Event::where('event_organizer_id', '=', ($eo_details[0])["id"])->get();

        $game_name_idx = 0;

        $games_name = array();
        $cities_name = array();
        $cities_idx = 0;

        foreach ($events as $event) {
            if ($event->start_date != null) {
                $new_date = date('d F Y', strtotime($event->start_date));
                $event->start_date = $new_date;
            }

            if ($event->end_date != null) {
                $new_date = date('d F Y', strtotime($event->end_date));
                $event->end_date = $new_date;
            }

            $city = City::where('id', '=', $event->city_id)->get();

            if (count($city) != 0) {
                $cities_name[$cities_idx] = ($city[0])->name;
            } else {
                $cities_name[$cities_idx] = null;
            }

            $cities_idx++;

            $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $event->id)->get();

            $game_str = "";


            for ($idx = 0; $idx < count($event_game_connector); $idx++) {
                $games = Games::select('name')->where("id", ($event_game_connector[$idx])->game_id)->get();

                $game_str .= ($games[0])->name;
                $game_str .= ", ";
            }

            $game_str = rtrim($game_str, ", ");
            $games_name[$game_name_idx] = $game_str;
            $game_name_idx++;
        }


        return view('my_event', ['events' => $events, 'games' => $games_name, 'city_name' => $cities_name]);
    }

    public function showManageNewsForm(Request $request)
    {
        $news = News::where("event_id", $request->event_id)->get();

        $event = Event::find($request->event_id);

        if (!is_null($event->city_id)) {
            $event->city = (City::select('name')->find($event->city_id))->name;
        } else {
            $event->city = "-";
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        foreach (EventGameConnector::select('game_id')->where('event_id', $event->id)->get() as $event_game_connector) {
            if (is_null($event->game)) {
                $event->game = (Games::select('name')->find($event_game_connector->game_id))->name;
            } else {
                $event->game .= ", " . (Games::select('name')->find($event_game_connector->game_id))->name;
            }
        }

        return view('manage_news', ['event' => $event, 'news' => $news, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
    }

    public function showWriteNewsPage(Request $request)
    {
        if ($request->title == null) {
            return view('write_news', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        } else {
            $success = false;
            $news = new News;

            $news->title = $request->title;
            $news->content = $request->news_content;
            $news->status = $request->status;
            $news->event_id = $request->event_id;

            // Change the line below to your timezone!
            date_default_timezone_set('Asia/Jakarta');
            $news->created_at = date('Y-m-d H:i:s', time());

            if ($news->status == "Published") $news->published_on = $news->created_at;

            if ($news->save()) {
                $header_image = $request->file('header_image');

                if ($header_image != null) {
                    $news->header_image = 'news_header_' . $news->id . '.' . $header_image->getClientOriginalExtension();

                    $header_image->move('images/news_header', 'news_header_' . $news->id . '.' . $header_image->getClientOriginalExtension());
                    $news->save();
                }

                $success = true;
            }

            return view('write_news', ['success' => $success, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        }
    }

    public function deleteEvent(Request $request)
    {
        EventGameConnector::where('event_id', '=', $request->event_id)->delete();

        Event::where('id', '=', $request->event_id)->delete();

        return redirect('manage_event');
    }

    public function showUpdateEventForm(Request $request)
    {
        $game_name = array();
        $game_id = array();
        $city_name = null;

        //handle submitted form
        if ($request->event_name != null) {
            $event = Event::find($request->event_id);
            $event->name = $request->event_name;
            $event->city_id = $request->city_id;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->details = $request->details;

            if (is_null($request->participant_registration)) {
                $event->participant_registration = false;
            } else {
                $event->participant_registration = true;
            }


            if ($event->save()) {
                $brochure = $request->file('brochure');

                if ($brochure != null) {
                    $event->brochure = 'brochure_' . $event->id . '.' . $brochure->getClientOriginalExtension();

                    $brochure->move('images/event_brochure', 'brochure_' . $event->id . '.' . $brochure->getClientOriginalExtension());
                    $event->save();
                }


                $success = true;
            }

            EventGameConnector::where('event_id', '=', $event->id)->delete();

            if (!is_null($request->game_id)) {
                foreach ($request->game_id as $id) {
                    $event_game_connector = new EventGameConnector;
                    $event_game_connector->event_id = $event->id;
                    $event_game_connector->game_id = $id;
                    $event_game_connector->save();
                }
            }


            $event_game_connector = EventGameConnector::where("event_id", $request->event_id)->get();
            $idx = 0;

            $city = City::where("id", $event->city_id)->get();

            if (count($city) != 0) {
                $city_name = ($city[0])->name;
            }

            foreach ($event_game_connector as $event_game) {
                $games = Games::select('name')->where('id', '=', $event_game->game_id)->get();

                $game_id[$idx] = $event_game->game_id;
                $game_name[$idx] = ($games[0])["name"];
                $idx++;
            }

            return view('update_event', ['event' => $event, 'game_id' => $game_id, 'game_name' => $game_name, 'city_name' => $city_name, 'success' => $success]);
        } else { //only show "update event" form
            $event = Event::where("id", $request->event_id)->get();

            $event_game_connector = EventGameConnector::where("event_id", $request->event_id)->get();

            $idx = 0;

            $city = City::where("id", ($event[0])->city_id)->get();

            if (count($city) != 0) {
                $city_name = ($city[0])->name;
            }

            foreach ($event_game_connector as $event_game) {
                $games = Games::select('name')->where('id', '=', $event_game->game_id)->get();

                $game_id[$idx] = $event_game->game_id;
                $game_name[$idx] = ($games[0])["name"];
                $idx++;
            }

            return view('update_event', ['event' => $event[0], 'game_id' => $game_id, 'game_name' => $game_name, 'city_name' => $city_name]);
        }
    }

    public function updateNews(Request $request)
    {
        if ($request->title == null) {
            return view('update_news', ['news' => News::find($request->news_id), 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        } else {
            $news = News::find($request->news_id);

            $news->title = $request->title;
            $news->content = $request->news_content;

            // Change the line below to your timezone!
            date_default_timezone_set('Asia/Jakarta');
            $news->last_modified = date('Y-m-d H:i:s', time());

            if ($news->save()) {
                $header_image = $request->file('header_image');

                if ($header_image != null) {
                    $news->header_image = 'news_header_' . $news->id . '.' . $header_image->getClientOriginalExtension();

                    $header_image->move('images/news_header', 'news_header_' . $news->id . '.' . $header_image->getClientOriginalExtension());
                    $news->save();
                }

                $success = true;
            }
            return view('update_news', ['success' => $success, 'news' => $news, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        }
    }

    public function publishNews(Request $request)
    {
        $news = News::find($request->news_id);

        $news->status = "Published";
        // Change the line below to your timezone!
        date_default_timezone_set('Asia/Jakarta');
        $news->published_on = date('Y-m-d H:i:s', time());

        $news->save();

        return view('publish_news', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
    }

    public function searchCity(Request $request)
    {
        if ($request->has('q')) {
            $keyword = $request->q;
            $data = City::select('id', 'name')->where('name', 'LIKE', '%' . $keyword . '%')->get();
            return response()->json($data);
        } else {
            $data = City::select('id', 'name')->get();
            return response()->json($data);
        }
    }

    public function showManageVacancyPage(Request $request)
    {
        $vacancies = Vacancy::where("event_id", $request->event_id)->first();
        $subroles = Subrole::where('name', '!=', 'E-Sport Enthusiast')->where('name', '!=', 'E-Sport Player')->get();

        $vacant_roles = "";

        if ($vacancies != null) {
            foreach ($vacancies->getFillable() as $fillable) {

                if ($vacancies->$fillable == 1 && $fillable != "id" && $fillable != "event_id") {
                    $vacant_roles .= ucwords(str_replace("_", " ", $fillable));
                    $vacant_roles .= ", ";
                }
            }

            $vacant_roles = rtrim($vacant_roles, ", ");
        }

        $vacant_roles_arr = array();
        $vacancy_idx = 0;

        if (!is_null($vacancies)) {
            foreach ($vacancies->getFillable() as $fillable) {
                if ($fillable != "id" && $fillable != "event_id" && $fillable != "description") {
                    if ($vacancies->$fillable == 1) {
                        $vacant_roles_arr[$vacancy_idx] = ucfirst($fillable);
                        $vacancy_idx++;
                    }
                }
            }
        }

        $event_games = EventGameConnector::select('game_id')->where('event_id', $request->event_id)->get();

        //get event games
        $games = null;

        for ($idx = 0; $idx < count($event_games); $idx++) {
            if ($idx == 0) {
                $games = Games::where('id', ($event_games[$idx])->game_id);
            } else {
                $games = $games->orWhere('id', ($event_games[$idx])->game_id);
            }

            if ($idx == count($event_games) - 1) {
                $games = $games->get();
            }
        }

        $event = Event::find($request->event_id);

        if (!is_null($event->city_id)) {
            $event->city = (City::select('name')->find($event->city_id))->name;
        } else {
            $event->city = "-";
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        foreach (EventGameConnector::select('game_id')->where('event_id', $event->id)->get() as $event_game_connector) {
            if (is_null($event->game)) {
                $event->game = (Games::select('name')->find($event_game_connector->game_id))->name;
            } else {
                $event->game .= ", " . (Games::select('name')->find($event_game_connector->game_id))->name;
            }
        }

        return view('manage_vacancy', ['subroles' => $subroles, 'event' => $event, 'vacancies' => $vacancies, 'vacant_roles' => $vacant_roles, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'vacant_roles_arr' => $vacant_roles_arr, 'games' => $games]);
    }

    public function showManageSponsorshipPackagePage(Request $request)
    {
        //get all industry to be shown in modal
        $industries = Industry::get();

        $packages = SponsorshipPackage::where("event_id", $request->event_id)->get();

        $event = Event::find($request->event_id);

        if (!is_null($event->city_id)) {
            $event->city = (City::select('name')->find($event->city_id))->name;
        } else {
            $event->city = "-";
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        foreach (EventGameConnector::select('game_id')->where('event_id', $event->id)->get() as $event_game_connector) {
            if (is_null($event->game)) {
                $event->game = (Games::select('name')->find($event_game_connector->game_id))->name;
            } else {
                $event->game .= ", " . (Games::select('name')->find($event_game_connector->game_id))->name;
            }
        }

        return view('manage_sponsorship_package', ['industries' => $industries, 'event' => $event, 'packages' => $packages, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
    }

    public function deleteVacancy(Request $request)
    {
        Vacancy::where('event_id', '=', $request->event_id)->delete();

        return view('delete_vacancy', ["event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id)]);
    }

    public function deletePackage(Request $request)
    {
        if (SponsorshipPackage::where("id", $request->package_id)->delete()) {

            $packages = SponsorshipPackage::where("event_id", $request->event_id)->get();

            return view('delete_package', ['packages' => $packages, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        }
    }

    public function deleteNews(Request $request)
    {
        if (News::where("id", $request->news_id)->delete()) {

            return view('delete_news', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id)]);
        }
    }

    public function checkIfCookieExists()
    {
        if (Cookie::get('user_id') == "") {
            return redirect('/');
        }
    }

    public function showHomePage(Request $request)
    {
        $this->checkIfCookieExists();

        //get preferred games if any
        $user_games = UserGameConnector::select('game_id')->where('user_id', Cookie::get('user_id'))->get();

        foreach ($user_games as $user_game) {
            $user_game->name = (Games::select('name')->find($user_game->game_id))->name;
        }

        $events = new Event;

        $idx = 0;

        //filter game
        if (!is_null($request->game_id)) {
            foreach ($request->game_id as $id) {
                $event_game_connector = EventGameConnector::select('event_id')->where("game_id", $id)->get();
                if (count($event_game_connector) != 0) {
                    foreach ($event_game_connector as $event) {
                        if ($idx == 0) {
                            $events = $events::where('id', $event["event_id"]);
                            $idx++;
                        } else {
                            $events = $events->orWhere('id', $event["event_id"]);
                        }
                    }
                } else {
                    if ($idx == 0) {
                        $events = $events::where('id', -1);
                        $idx++;
                    } else {
                        $events = $events->orWhere('id', -1);
                    }
                }
            }
        } else if ($request->filter != "true" && count($user_games) != 0) {
            foreach ($user_games as $user_game) {
                $event_game_connector = EventGameConnector::select('event_id')->where("game_id", $user_game->game_id)->get();
                if (count($event_game_connector) != 0) {
                    foreach ($event_game_connector as $event) {
                        if ($idx == 0) {
                            $events = $events::where('id', $event["event_id"]);
                            $idx++;
                        } else {
                            $events = $events->orWhere('id', $event["event_id"]);
                        }
                    }
                } else {
                    if ($idx == 0) {
                        $events = $events::where('id', -1);
                        $idx++;
                    } else {
                        $events = $events->orWhere('id', -1);
                    }
                }
            }
        }

        //filter city if game is unfiltered
        if ($request->city_id != null && $request->game_id == null) {
            foreach ($request->city_id as $id) {

                if ($idx == 0) {
                    $events = $events::where('city_id', $id);
                    $idx++;
                } else {
                    $events = $events->orWhere('city_id', $id);
                }
            }
        }

        $events = $events->where('status', 'Published')->orderBy('start_date', 'ASC')->get();

        $removed_event = array();
        $removed_event_idx = 0;
        //filter city if game is filtered
        if ($request->city_id != null && $request->game_id != null) {
            for ($idx = 0; $idx < count($events); $idx++) {
                $found = false;
                foreach ($request->city_id as $id) {
                    if (($events[$idx])->city_id == $id) {
                        $found = true;
                    }
                }

                if (!$found) {
                    $removed_event[$removed_event_idx] = $idx;
                    $removed_event_idx++;

                }
            }
        }

        foreach ($removed_event as $idx) {
            unset($events[$idx]);
        }

        //reindexing
        $events = $this->reindexing($events);

        if ($request->keyword != null) {
            for ($idx = 0; $idx < count($events); $idx++) {
                if (!strpos(($events[$idx])->name, $request->keyword)) {
                    unset($events[$idx]);
                }
            }
        }

        //reindexing
        $events = $this->reindexing($events);

        $game_name_idx = 0;

        $games_name = array();
        $cities_name = array();
        $cities_idx = 0;

        foreach ($events as $event) {
            $new_date = date('d F Y', strtotime($event->start_date));
            if ($event->start_date != null) {
                $event->start_date = $new_date;
            } else {
                $event->start_date = null;
            }

            $new_date = date('d F Y', strtotime($event->end_date));

            if ($event->end_date != null) {
                $event->end_date = $new_date;
            } else {
                $event->end_date = null;
            }

            $city = City::where('id', '=', $event->city_id)->get();
            if (count($city) != 0) {
                $cities_name[$cities_idx] = ($city[0])->name;
            } else {
                $cities_name[$cities_idx] = null;
            }
            $cities_idx++;

            $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $event->id)->get();

            $game_str = "";

            if (count($event_game_connector) != 0) {
                for ($idx = 0; $idx < count($event_game_connector); $idx++) {
                    $games = Games::select('name')->where("id", ($event_game_connector[$idx])->game_id)->get();
                    $game_str .= ($games[0])->name;

                    if ($idx != count($event_game_connector) - 1)
                        $game_str .= ", ";
                    else {
                        $games_name[$game_name_idx] = $game_str;
                        $game_name_idx++;
                    }
                }
            } else {
                $games_name[$game_name_idx] = $game_str;
                $game_name_idx++;
            }
        }

        //get event organizer user_id and name
        foreach ($events as $event) {
            $event->event_organizer = EventOrganizer::select('user_id', 'display_name')->find($event->event_organizer_id);
        }


        return view('home', ['events' => $events, 'games' => $games_name, 'city_name' => $cities_name, 'user_games' => $user_games, 'filter' => $request->filter]);
    }

    public function reindexing($array)
    {
       $max_idx = -1;

       foreach($array as $k => $val){
           if($max_idx < $k) {
               $max_idx = $k;
           }
       }

        if (count($array) != 0) {
            $new_idx = 0;

            foreach ($array as $k => $val) {
                $array[$new_idx] = $array[$k];
                $new_idx++;
            }

            for ($idx = $new_idx; $idx <= $max_idx; $idx++) {
                unset($array[$idx]);
            }
        }

        if (is_array($array)) {
            ksort($array);
        }



        return $array;
    }

    public function inviteWorker(Request $request)
    {
        $vacancy = Vacancy::where('event_id', $request->event_id)->first();

        if (!is_array($request->user_id)) {
            $vacancy_management = new VacancyManagement;
            $worker = Worker::where('user_id', $request->user_id)->first();
            $vacancy_management->action = "Invite";
            $vacancy_management->message = $request->message;
            $vacancy_management->vacancy_id = $vacancy->id;
            $vacancy_management->worker_id = $worker->id;

            $vacancy_management->save();
        } else {
            foreach ($request->user_id as $user_id) {
                $worker = Worker::where('user_id', $user_id)->first();
                $vacancy_management = new VacancyManagement;
                $vacancy_management->action = "Invite";
                $vacancy_management->vacancy_id = $vacancy->id;
                $vacancy_management->worker_id = $worker->id;

                $vacancy_management->save();
            }
        }

        return view('invite_worker', ["user_id" => $request->user_id, "event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id), "subrole_id" => $request->subrole_id, "game_id" => $request->game_id, "keyword" => $request->keyword, "gender" => $request->gender, "city_id" => $request->city_id]);
    }

    public function broadcastPackage(Request $request)
    {
        if (!is_array($request->company_id)) {
            $sponsor_management = new SponsorshipManagement;
            $sponsor_management->action = "Invite";
            $sponsor_management->event_id = $request->event_id;
            $sponsor_management->company_id = $request->company_id;

            $sponsor_management->save();
        } else {
            foreach ($request->company_id as $company_id) {
                $sponsor_management = new SponsorshipManagement;
                $sponsor_management->action = "Invite";
                $sponsor_management->event_id = $request->event_id;
                $sponsor_management->company_id = $company_id;

                $sponsor_management->save();
            }
        }


        return view('broadcast_package', ["event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id), "industry_id" => $request->industry_id, "keyword" => $request->keyword]);
    }

    public function showEventDetails()
    {
        //check if current logged in user has registered to this event or not
        $is_register = false;

        foreach (EventGameConnector::where('event_id', Input::get('event_id'))->get() as $event_game) {
            if (Cookie::get("role_name") == "Individual" && count(ParticipantManagement::where("action", "Register")->where("event_game_id", $event_game->id)->where('gamer_id', (Gamer::select('id')->where("user_id", Cookie::get("user_id"))->first())->id)->get()) != 0) {
                $is_register = true;
            }
        }

        if (Cookie::get("role_name") != "Individual") {
            $is_register = true;
        }

        //retrieve event
        $event = Event::where('id', Input::get('event_id'))->first();

        //get event organizer user_id and name
        $event->event_organizer = EventOrganizer::select('user_id', 'display_name')->find($event->event_organizer_id);

        //retrieve event rating
        $ratings = Rating::where('event_id', Input::get('event_id'))->get();
        $event->rating = 0;
        $event->no_of_raters = 0;

        $user_rating = 0;

        foreach ($ratings as $rating) {
            $event->rating += $rating->rating;
            $event->no_of_raters++;

            //retrieve user rating
            if ($rating->user_id == Cookie::get("user_id")) {
                $user_rating = $rating->rating;
            }
        }

        if ($event->no_of_raters != 0) {
            $event->rating = round($event->rating / $event->no_of_raters);
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        $city = City::select('name')->where('id', $event->city_id)->first();

        $event_game_connector = EventGameConnector::select('id', 'game_id')->where('event_id', $event->id)->get();

        $game_name = "";

        $participated_teams = array();
        $participated_teams_idx = 0;

        foreach ($event_game_connector as $value) {
            $game = Games::select('name')->where('id', $value->game_id)->first();
            $game_name .= $game->name;
            $participated_teams[$participated_teams_idx] = new \stdClass();
            ($participated_teams[$participated_teams_idx])->event_game_id = $value->id;
            ($participated_teams[$participated_teams_idx])->game_name = $game->name;
            $participated_teams_idx++;
            $game_name .= ", ";
        }

        if (count($event_game_connector) == 0) {
            $game_name = "-";
        }

        $game_name = rtrim($game_name, ", ");

        $city_name = "-";

        if (isset($city->name)) {
            $city_name = $city->name;
        }

        //retrieve participated teams
        foreach ($participated_teams as $participated_team) {
            $participant_managements = ParticipantManagement::select('team_id')->where('action', 'Confirm')->where('event_game_id', $participated_team->event_game_id)->distinct()->get();

            $participated_team->teams = array();
            $team_idx = 0;
            foreach ($participant_managements as $participant_management) {
                ($participated_team->teams)[$team_idx] = Team::select('id', 'team_name', 'team_logo')->find($participant_management->team_id);

                $team_idx++;
            }
        }

        //retrieve 'deal' sponsors
        $deal_sponsors = SponsorshipManagement::select('company_id')->where('action', 'Deal')->where('event_id', $event->id)->get();

        foreach ($deal_sponsors as $deal_sponsor) {
            $company = Company::select('company_name', 'company_logo')->find($deal_sponsor->company_id);
            $deal_sponsor->company_name = $company->company_name;
            $deal_sponsor->company_logo = $company->company_logo;
        }

        //retrieve streaming channels
        $streaming_channels = StreamingChannel::where('event_id', $event->id)->get();


        foreach ($streaming_channels as $streaming_channel) {
            $streaming_channel->start_datetime = date('d M Y, H:i', strtotime($streaming_channel->start_datetime));

            if (strpos(strtolower($streaming_channel->url), 'twitch.') !== false) {
                $streaming_channel->platform = "twitch";
            } else if (strpos(strtolower($streaming_channel->url), 'youtube.') !== false) {
                $streaming_channel->platform = "youtube";
            }
        }

        //retrieve news
        $news = News::select('id', 'title', 'content', 'header_image', 'published_on')->where("status", "Published")->where("event_id", $event->id)->get();
        foreach ($news as $a_news) {
            $a_news->published_on = date('F d Y, H:i', strtotime($a_news->published_on));

            $a_news->content = substr($a_news->content, 0, strpos($a_news->content, "\n"));
        }

        //retrieve comments
        $comments = Comment::where('event_id', $event->id)->orderBy("datetime", 'DESC')->get();

        foreach ($comments as $comment) {
            date_default_timezone_set('Asia/Jakarta');
            $datetime_now = date('Y-m-d H:i:s', time());


            $comment_date = date('Y-m-d H:i:s', strtotime($comment->datetime));


            $comment->datetime = $this->calculateDateTimeDifference($datetime_now, $comment_date);

            //categorize comment based on role
            if ($comment->user_id == (EventOrganizer::select('user_id')->find($event->event_organizer_id))->user_id) {
                $comment->name = (EventOrganizer::select("display_name")->where("user_id", $comment->user_id)->first())->display_name;
                $comment->role = "Event Organizer";
                $comment->picture = "/images/profile_picture/" . (EventOrganizer::select("profile_picture")->where("user_id", $comment->user_id)->first())->profile_picture;
            } else if (!is_null(Gamer::select('id')->where('user_id', $comment->user_id)->first())) {
                $gamer_id = (Gamer::select('id')->where('user_id', $comment->user_id)->first())->id;

                foreach ($event_game_connector as $event_game) {
                    if (!is_null(ParticipantManagement::select('id')->where("event_game_id", $event_game->id)->where("action", "Confirm")->where("gamer_id", $gamer_id)->first())) {
                        $comment->name = (Gamer::select("display_name")->where("user_id", $comment->user_id)->first())->display_name;
                        $comment->role = "Registered " . (Games::select('name')->find($event_game->game_id))->name . " Player";
                        $comment->picture = "/images/profile_picture/" . (Gamer::select("profile_picture")->where("user_id", $comment->user_id)->first())->profile_picture;
                        break;
                    }
                }
            } else if (!is_null(Worker::select('id')->where('user_id', $comment->user_id)->first())) {
                $worker_id = (Worker::select('id')->where('user_id', $comment->user_id)->first())->id;
                $vacancy_id = (Vacancy::select('id')->where('event_id', $event->id)->first())->id;
                if (!is_null(VacancyManagement::select('subrole_id')->where("vacancy_id", $vacancy_id)->where("action", "Confirm")->where("worker_id", $worker_id)->first())) {
                    $subrole_id = (VacancyManagement::select('subrole_id')->where("vacancy_id", $vacancy_id)->where("action", "Confirm")->where("worker_id", $worker_id)->first())->subrole_id;
                    $comment->name = (Worker::select("display_name")->where("user_id", $comment->user_id)->first())->display_name;
                    $comment->picture = "/images/profile_picture/" . (Worker::select("profile_picture")->where("user_id", $comment->user_id)->first())->profile_picture;
                    $comment->role = "Registered " . (Subrole::select('name')->find($subrole_id))->name;
                }
            } else if (!is_null(Company::select('id')->where('user_id', $comment->user_id)->first())) {
                $company_id = (Company::select('id')->where('user_id', $comment->user_id)->first())->id;

                $comment->name = (Company::select("company_name")->where("user_id", $comment->user_id)->first())->company_name;
                $comment->picture = "/images/company_logo/" . (Company::select("company_logo")->where("user_id", $comment->user_id)->first())->company_logo;

                if (!is_null(SponsorshipManagement::select('id')->where("company_id", $company_id)->where("action", "Deal")->where("event_id", $event->id)->first())) {
                    $comment->role = "Sponsor";
                }
            } else {
                $comment->role = null;
            }
        }

        return view('event_details', ['event' => $event, 'city' => $city_name, 'game_name' => $game_name, 'is_register' => $is_register, 'comments' => $comments, 'deal_sponsors' => $deal_sponsors, 'participated_teams' => $participated_teams, 'news' => $news, 'user_rating' => $user_rating, 'streaming_channels' => $streaming_channels]);
    }

    public function calculateDateTimeDifference($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);

        $year_diff = date('Y', $date1) - date('Y', $date2);
        $month_diff = date('m', $date1) - date('m', $date2);
        $date_diff = date('d', $date1) - date('d', $date2);
        $hour_diff = date('H', $date1) - date('H', $date2);
        $minute_diff = date('i', $date1) - date('i', $date2);
        $second_diff = date('s', $date1) - date('s', $date2);


        if ($year_diff != 0) {
            if ($year_diff > 1) {
                return $year_diff . " years ago";
            } else {
                return $year_diff . " year ago";
            }
        } else if ($month_diff != 0) {
            if ($month_diff > 1) {
                return $month_diff . " months ago";
            } else {
                return $month_diff . " month ago";
            }
        } else if ($date_diff != 0) {
            if ($date_diff > 1) {
                return $date_diff . " days ago";
            } else {
                return $date_diff . " day ago";
            }
        } else if ($hour_diff != 0) {
            if ($hour_diff > 1) {
                return $hour_diff . " hours ago";
            } else {
                return $hour_diff . " hour ago";
            }
        } else if ($minute_diff != 0) {
            if ($minute_diff > 1) {
                return $minute_diff . " minutes ago";
            } else {
                return $minute_diff . " minute ago";
            }
        } else {
            if ($second_diff > 1) {
                return $second_diff . " seconds ago";
            } else {
                return $second_diff . " second ago";
            }
        }
    }

    public function showVacancyPage()
    {
        $vacant_roles = array();

        $vacancies = new Vacancy;
        $events = array();
        $event_organizers = array();
        $city_names = array();
        $games = array();

        $idx = 0;
        $eo_idx = 0;

        $worker_id = (Worker::where('user_id', Cookie::get('user_id'))->first())->id;
        $user_subroles = UserSubroleConnector::select('subrole_id')->where('user_id', Cookie::get('user_id'))->get();


        //retrieve vacancies based on user's subroles
        for ($user_subroles_idx = 0; $user_subroles_idx < count($user_subroles); $user_subroles_idx++) {
            $subrole_name = (Subrole::select('name')->find(($user_subroles[$user_subroles_idx])->subrole_id))->name;
            if ($subrole_name != "E-Sport Player") {
                if ($user_subroles_idx == 0) {
                    $vacancies = $vacancies->where(strtolower((Subrole::select('name')->find(($user_subroles[$user_subroles_idx])->subrole_id))->name), 1);
                } else {
                    $vacancies = $vacancies->orWhere(strtolower((Subrole::select('name')->find(($user_subroles[$user_subroles_idx])->subrole_id))->name), 1);
                }
            }
        }

        $vacancies = $vacancies->get();
        //get event based on its vacancy
        foreach ($vacancies as $vacancy) {
            $vacant_roles[$idx] = "";
            $games[$idx] = "";
            $events[$idx] = Event::find($vacancy->event_id);
            $event_organizers[$eo_idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);
            $eo_idx++;
            ($events[$idx])->open_vacancy = $vacancy->open;


            if (!is_null(($events[$idx])->city_id)) {
                $city_names[$idx] = (City::find(($events[$idx])->city_id))->name;
            }

            $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $vacancy->event_id)->get();

            foreach ($event_game_connector as $value) {
                $games[$idx] .= (Games::select('name')->where('id', $value->game_id)->first())->name;
                $games[$idx] .= ", ";
            }

            if (($events[$idx])->start_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->start_date));
                ($events[$idx])->start_date = $new_date;
            }

            if (($events[$idx])->end_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->end_date));
                ($events[$idx])->end_date = $new_date;
            }

            foreach ($vacancy->getFillable() as $fillable) {
                if ($vacancy->$fillable == 1 && $fillable != "id" && $fillable != "event_id") {
                    $vacant_roles[$idx] .= ucwords(str_replace("_", " ", $fillable));
                    $vacant_roles[$idx] .= ", ";
                }
            }

            ($events[$idx])->details = $vacancy->description;


            $vacancy_managements = VacancyManagement::where('vacancy_id', $vacancy->id)->where('worker_id', $worker_id)->get();

            ($events[$idx])->status = "all";

            foreach ($vacancy_managements as $vacancy_management) {
                if ($vacancy_management->action == "Register") {
                    if ((strpos($vacant_roles[$idx], "Registered")) === false) {
                        $vacant_roles[$idx] = "Registered as ";
                    }


                    //replace vacant roles to registered roles
                    $subrole = Subrole::select('name')->find($vacancy_management->subrole_id);


                    $vacant_roles[$idx] .= $subrole->name . ", ";

                    ($events[$idx])->status = "all_registered";
                }
            }


            $vacant_roles[$idx] = rtrim($vacant_roles[$idx], ", ");

            $games[$idx] = rtrim($games[$idx], ", ");

            $idx++;
        }

        $vacancy_managements = VacancyManagement::where('worker_id', $worker_id)->get();

        $event_id = array();
        $event_id_idx = 0;

        foreach ($vacancy_managements as $vacancy_management) {
            $vacant_roles[$idx] = "";
            $games[$idx] = "";

            $vacancy = Vacancy::find($vacancy_management->vacancy_id);

            $events[$idx] = Event::find($vacancy->event_id);

            $event_organizers[$eo_idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);
            $eo_idx++;

            if (($events[$idx])->city_id != null) {
                $city_names[$idx] = (City::where("id", ($events[$idx])->city_id)->first())->name;
            }
            $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $vacancy->event_id)->get();

            foreach ($event_game_connector as $value) {
                $games[$idx] .= (Games::select('name')->where('id', $value->game_id)->first())->name;
                $games[$idx] .= ", ";
            }

            if (($events[$idx])->start_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->start_date));
                ($events[$idx])->start_date = $new_date;
            }

            if (($events[$idx])->end_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->end_date));
                ($events[$idx])->end_date = $new_date;
            }

            foreach ($vacancy->getFillable() as $fillable) {
                if ($vacancy->$fillable == 1 && $fillable != "id" && $fillable != "event_id") {
                    $vacant_roles[$idx] .= ucwords(str_replace("_", " ", $fillable));
                    $vacant_roles[$idx] .= ", ";
                }
            }

            ($events[$idx])->details = $vacancy->description;

            if (!is_null($vacancy_management->message)) {
                ($events[$idx])->details = $vacancy_management->message;
            } else {
                ($events[$idx])->details = $vacancy->description;
            }

            if ($vacancy_management->action == "Invite") {
                ($events[$idx])->status = "invited";

                //check if event invitation has been responded
                $vm = VacancyManagement::where("vacancy_id", $vacancy_management->vacancy_id)->where("worker_id", $vacancy_management->worker_id)->where('action', '!=', "Invite")->first();

                //already registered/confirmed/denied
                if ($vm != null) {
                    ($events[$idx])->status = "";
                }
            } else {
                //check if registration has been responded
                $check_if_responded = VacancyManagement::where("worker_id", $vacancy_management->worker_id)->where("vacancy_id", $vacancy->id)->where("action", "!=", "Invite")->where("action", "!=", "Register")->where('subrole_id', $vacancy_management->subrole_id)->first();
                if (is_null($check_if_responded)) {
                    ($events[$idx])->status = $vacancy_management->action;
                } else if ($vacancy_management->action != "Register") {
                    ($events[$idx])->status = $vacancy_management->action;
                }

                $vacant_roles[$idx] = "Registered as ";

                //replace vacant roles to registered roles
                $subrole = Subrole::select('name')->find($vacancy_management->subrole_id);

                $vacant_roles[$idx] .= $subrole->name;

                $event_id[$event_id_idx] = $vacancy->event_id;
                $event_id_idx++;
            }

            $vacant_roles[$idx] = rtrim($vacant_roles[$idx], ", ");

            $games[$idx] = rtrim($games[$idx], ", ");

            $idx++;
        }


        return view('vacancy', ['events' => $events, 'event_organizers' => $event_organizers, 'vacant_roles' => $vacant_roles, 'city_names' => $city_names, 'games' => $games]);
    }

    public function showVacancyRegistrationForm(Request $request)
    {
        $vacancy = Vacancy::where('event_id', $request->event_id)->first();

        $subroles = array();
        $idx = 0;

        foreach ($vacancy->getFillable() as $fillable) {
            if ($vacancy->$fillable == 1 && $fillable != "id" && $fillable != "event_id") {
                $subroles[$idx] = Subrole::where('name', ucwords(str_replace("_", " ", $fillable)))->first();
                $idx++;
            }
        }

        $games = array();
        $idx = 0;

        $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $request->event_id)->get();

        foreach ($event_game_connector as $value) {
            $games[$idx] = Games::where('id', $value->game_id)->first();

            $idx++;
        }

        return view('vacancy_registration', ['event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'subroles' => $subroles]);
    }

    public function vacancyRegistration(Request $request)
    {
        $success = false;

        //get vacancy id based on event id
        $vacancy = Vacancy::select('id')->where('event_id', $request->event_id)->first();

        $worker_id = (Worker::select('id')->where('user_id', Cookie::get('user_id'))->first())->id;

        foreach ($request->subrole_id as $id) {
            $vacancy_management = new VacancyManagement;
            $vacancy_management->action = "Register";
            $vacancy_management->worker_id = $worker_id;
            $vacancy_management->vacancy_id = $vacancy->id;
            $vacancy_management->subrole_id = $id;
            $success = $vacancy_management->save();
        }

        $vacancy = Vacancy::where('event_id', $request->event_id)->first();

        $subroles = array();
        $idx = 0;

        foreach ($vacancy->getFillable() as $fillable) {
            if ($vacancy->$fillable == 1 && $fillable != "id" && $fillable != "event_id") {
                $subroles[$idx] = Subrole::where('name', ucwords(str_replace("_", " ", $fillable)))->first();
                $idx++;
            }
        }


        return view('vacancy_registration', ['success' => $success, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'subroles' => $subroles]);
    }

    public function showParticipantRegistrationForm(Request $request)
    {
        $logged_in_gamer = Gamer::select('id', 'display_name')->where('user_id', Cookie::get('user_id'))->first();

        $team = new Team;
        $team->team_name = $request->team_name;
        $team->save();

        $success = false;

        if ($request->gamer_id != null) {
            $team_logo = $request->file('team_logo');

            if ($team_logo != null) {
                $team->team_logo = 'team_logo_' . $team->id . '.' . $team_logo->getClientOriginalExtension();

                $team_logo->move('images/team_logo', 'team_logo_' . $team->id . '.' . $team_logo->getClientOriginalExtension());
                $team->save();
            }

            $event_game_id = (EventGameConnector::select('id')->where('event_id', $request->event_id)->where("game_id", $request->game_id)->first())->id;

            foreach ($request->gamer_id as $id) {
                $participant_management = new ParticipantManagement;
                $participant_management->event_game_id = $event_game_id;
                $participant_management->gamer_id = $id;
                $participant_management->team_id = $team->id;
                $participant_management->action = "Register";

                if ($participant_management->save()) {
                    $success = true;
                }
            }
        }


        $event_games = EventGameConnector::select('game_id')->where('event_id', $request->event_id)->get();

        $game_names = array();
        $game_names_idx = 0;

        foreach ($event_games as $event_game) {
            $game_names[$game_names_idx] = (Games::select('name')->find($event_game->game_id))->name;
            $game_names_idx++;
        }

        return view('participant_registration', ['event_information' => $this->getEventInformation($request->event_id), 'logged_in_gamer' => $logged_in_gamer, 'event_id' => $request->event_id, 'event_games' => $event_games, 'game_names' => $game_names, 'success' => $success]);
    }

    public function showSponsorshipPage(Request $request)
    {
        $company_id = (Company::select("id")->where("user_id", Cookie::get("user_id"))->first())->id;
        if ($request->action != null) {
            $sponsorship_management = new SponsorshipManagement;
            $sponsorship_management->event_id = $request->event_id;
            $sponsorship_management->company_id = $company_id;
            $sponsorship_management->action = $request->action;
            $sponsorship_management->save();
        }

        $sponsorship_packages = SponsorshipPackage::get();
        $events = array();
        $event_organizers = array();
        $city_names = array();
        $games = array();


        $idx = 0;
        foreach ($sponsorship_packages as $sponsorship_package) {
            $duplicate_event = false;

            for ($event_idx = 0; $event_idx < count($events); $event_idx++) {
                if (($events[$event_idx])->id == $sponsorship_package->event_id) {
                    $duplicate_event = true;
                    $idx = $event_idx;
                    break;
                }
            }

            if (is_null(SponsorshipManagement::where('action', 'Not Interested')->where('event_id', $sponsorship_package->event_id)->where('company_id', $company_id)->first())) {
                if (!$duplicate_event) {

                    $games[$idx] = "";
                    $events[$idx] = Event::find($sponsorship_package->event_id);
                    $event_organizers[$idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);

                    if (!is_null(($events[$idx])->city_id)) {
                        $city_names[$idx] = (City::find(($events[$idx])->city_id))->name;
                    } else {
                        $city_names[$idx] = "-";
                    }

                    $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $sponsorship_package->event_id)->get();

                    foreach ($event_game_connector as $value) {
                        $games[$idx] .= (Games::select('name')->where('id', $value->game_id)->first())->name;
                        $games[$idx] .= ", ";
                    }

                    if (($events[$idx])->start_date != null) {
                        $new_date = date('d F Y', strtotime(($events[$idx])->start_date));
                        ($events[$idx])->start_date = $new_date;
                    }

                    if (($events[$idx])->end_date != null) {
                        $new_date = date('d F Y', strtotime(($events[$idx])->end_date));
                        ($events[$idx])->end_date = $new_date;
                    }
                }

                if (!$duplicate_event) {
                    ($events[$idx])->details = "<b>" . $sponsorship_package->package_name . " Package</b>";
                } else {
                    ($events[$idx])->details .= "<b>" . $sponsorship_package->package_name . " Package</b>";
                }
                ($events[$idx])->details .= "<hr>";
                ($events[$idx])->details .= "<u>Sponsor Rights</u>";
                ($events[$idx])->details .= "<pre style=\"font-size: 23px; \">$sponsorship_package->sponsor_rights</pre>";
                ($events[$idx])->details .= "<u>Sponsor Obligations</u>";
                ($events[$idx])->details .= "<pre style=\"font-size: 23px; \">$sponsorship_package->sponsor_obligations</pre>";
                ($events[$idx])->details .= "<hr>";

                ($events[$idx])->status = "all";

                $games[$idx] = rtrim($games[$idx], ", ");

                if (!is_null(SponsorshipManagement::where("event_id", $sponsorship_package->event_id)->where("company_id", $company_id)->where("action", "Interested")->where("proposal", "!=", 1)->first())) {
                    ($events[$idx])->status = "all_interested";
                    $idx++;

                    $events[$idx] = clone $events[$idx - 1];
                    $event_organizers[$idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);
                    ($events[$idx])->status = "Interested";
                    $games[$idx] = $games[$idx - 1];
                    $city_names[$idx] = $city_names[$idx - 1];
                } else if (!is_null(SponsorshipManagement::where("event_id", $sponsorship_package->event_id)->where("company_id", $company_id)->where("action", "Deal")->where("proposal", "!=", 1)->first())) {
                    ($events[$idx])->status = "all_deal";
                    $idx++;

                    $events[$idx] = clone $events[$idx - 1];
                    $event_organizers[$idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);
                    ($events[$idx])->status = "Deal";
                    $games[$idx] = $games[$idx - 1];
                    $city_names[$idx] = $city_names[$idx - 1];
                } else if (!is_null(SponsorshipManagement::where("event_id", $sponsorship_package->event_id)->where("company_id", $company_id)->where("action", "Not Interested")->where("proposal", "!=", 1)->first())) {
                    ($events[$idx])->status = "Not Interested";
                } else if (!is_null(SponsorshipManagement::where("event_id", $sponsorship_package->event_id)->where("company_id", $company_id)->where("action", "Invite")->where("proposal", "!=", 1)->first())) {
                    $idx++;

                    $events[$idx] = clone $events[$idx - 1];
                    $event_organizers[$idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);

//                    ($events[$idx])-> proposal = (SponsorshipManagement::select('proposal')->where("event_id", $sponsorship_package->event_id)->where("company_id", $company_id)->where("action", "Invite")->first())->proposal;


                    ($events[$idx])->status = "Invite";
                    $games[$idx] = $games[$idx - 1];
                    $city_names[$idx] = $city_names[$idx - 1];
                }

                $idx++;
            }
        }

        foreach(SponsorshipManagement::where('company_id', $company_id)->get() as $sponsorship_management){

            $games[$idx] = "";
            $events[$idx] = Event::find($sponsorship_management->event_id);
            $event_organizers[$idx] = EventOrganizer::select('display_name', 'user_id')->find(($events[$idx])->event_organizer_id);

            if (!is_null(($events[$idx])->city_id)) {
                $city_names[$idx] = (City::find(($events[$idx])->city_id))->name;
            } else {
                $city_names[$idx] = "-";
            }

            $event_game_connector = EventGameConnector::select('game_id')->where("event_id", $sponsorship_management->event_id)->get();

            foreach ($event_game_connector as $value) {
                $games[$idx] .= (Games::select('name')->where('id', $value->game_id)->first())->name;
                $games[$idx] .= ", ";
            }

            if (($events[$idx])->start_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->start_date));
                ($events[$idx])->start_date = $new_date;
            }

            if (($events[$idx])->end_date != null) {
                $new_date = date('d F Y', strtotime(($events[$idx])->end_date));
                ($events[$idx])->end_date = $new_date;
            }

            ($events[$idx])-> proposal = $sponsorship_management->proposal;

            $games[$idx] = rtrim($games[$idx], ", ");

            ($events[$idx])->status = $sponsorship_management->action;
            $idx++;
        }

        return view('sponsorship', ['events' => $events, 'event_organizers' => $event_organizers, 'city_names' => $city_names, 'games' => $games]);
    }

    public function showManageParticipantPage(Request $request)
    {
        if ($request->action != null) {
            foreach (ParticipantManagement::where('event_game_id', $request->event_game_id)->where('team_id', $request->team_id)->where('action', 'Register')->get() as $participant_management) {
                $participant_management_2 = new ParticipantManagement;
                $participant_management_2->event_game_id = $request->event_game_id;
                $participant_management_2->gamer_id = $participant_management->gamer_id;
                $participant_management_2->team_id = $request->team_id;
                $participant_management_2->message = $request->message;
                $participant_management_2->action = $request->action;

                $participant_management_2->save();

                if ($request->action == "Confirm") {
                    $experience = new Experience;
                    $experience->event_game_id = $request->event_game_id;
                    $experience->gamer_id = $participant_management->gamer_id;
                    $experience->type = "Participant";
                }
            }
        }

        $event_game_connector = EventGameConnector::where('event_id', $request->event_id)->get();
        $teams = array();
        $teams_idx = 0;

        $gamers = array();
        $gamers_idx = 0;

        $games = null;
        $games_idx = 0;

        foreach ($event_game_connector as $event_game) {
            $participant_managements = ParticipantManagement::where('event_game_id', $event_game->id)->orWhere('event_id', $request->event_id)->get();

            if ($games_idx == 0) {
                $games = Games::where('id', $event_game->game_id);
            } else {
                $games = $games->orWhere('id', $event_game->game_id);
            }

            foreach ($participant_managements as $participant_management) {
                $duplicate = false;
                $duplicate_team_idx = -1;
                $ignore = false;

                if ($participant_management->action == "Register" && !is_null(ParticipantManagement::where("action", "!=", "Invite")->where("action", "!=", "Register")->where("event_game_id", $participant_management->event_game_id)->where("gamer_id", $participant_management->gamer_id)->where("team_id", $participant_management->team_id)->first())) {
                    $ignore = true;
                }

                if ($participant_management->action == "Invite" && !is_null(ParticipantManagement::where("action", "!=", "Invite")->where("event_game_id", $participant_management->event_game_id)->where("gamer_id", $participant_management->gamer_id)->where("team_id", $participant_management->team_id)->first())) {
                    $ignore = true;
                }

                if (!$ignore) {
                    for ($idx = 0; $idx < count($teams); $idx++) {
                        if (($teams[$idx])->id == $participant_management->team_id && ($teams[$idx])->action == $participant_management->action) {
                            $duplicate = true;
                            $duplicate_team_idx = $idx;
                            break;
                        }
                    }

                    if ($participant_management->action != "Invite") {

                        if (!$duplicate) {
                            $teams[$teams_idx] = Team::find($participant_management->team_id);

                            ($teams[$teams_idx])->action = $participant_management->action;

                            ($teams[$teams_idx])->game = (Games::select('name')->find($event_game->game_id))->name;


                            if (is_null(($teams[$teams_idx])->gamer)) {
                                ($teams[$teams_idx])->gamer = Gamer::select('display_name', 'user_id')->where('id', $participant_management->gamer_id);
                            } else {
                                ($teams[$teams_idx])->gamer = (($teams[$teams_idx])->gamer)->orWhere('id', $participant_management->gamer_id);
                            }

//                            ($teams[$teams_idx])->roster = '1. <a href = "view_profile?user_id=' .$gamer->user_id.'">'. $gamer->display_name . '</a> <br>';
                            ($teams[$teams_idx])->event_game_id = $event_game->id;
                            $teams_idx++;

                        } else {
                            $no = substr_count(($teams[$duplicate_team_idx])->roster, "\n") + 1;
                            ($teams[$duplicate_team_idx])->roster .= $no . ". " . (Gamer::select('display_name')->find($participant_management->gamer_id))->display_name . "\n";
                        }
                    } else if ($participant_management->event_id == $request->event_id) {
                        $registered = false;

                        //check if the invited user has registered
                        foreach ($event_game_connector as $event_game) {
                            $pm = ParticipantManagement::where('event_game_id', $event_game->id)->where('gamer_id', $participant_management->gamer_id)->where('action', 'Register')->first();

                            if (!is_null($pm)) {
                                $registered = true;
                                break;
                            }
                        }


                        if (!$registered) {
                            $gamers[$gamers_idx] = Gamer::find($participant_management->gamer_id);

                            ($gamers[$gamers_idx])->age = null;
                            if (!is_null(($gamers[$gamers_idx])->dob)) {
                                $d1 = new DateTime(($gamers[$gamers_idx])->dob);
                                $d2 = new DateTime();
                                $diff = $d2->diff($d1);

                                ($gamers[$gamers_idx])->age = $diff->y;
                            }


                            $city = City::select('name')->find(($gamers[$gamers_idx])->city_id);
                            ($gamers[$gamers_idx])->city = null;

                            if (!is_null($city)) {
                                ($gamers[$gamers_idx])->city = $city->name;
                            }

                            $user_game_connector = UserGameConnector::select('game_id')->where('user_id', ($gamers[$gamers_idx])->user_id)->get();

                            $game_name = "";

                            for ($idx = 0; $idx < count($user_game_connector); $idx++) {
                                if ($idx == 0) {
                                    $game_name = (Games::select('name')->find(($user_game_connector[$idx])->game_id))->name;
                                } else {
                                    $game_name .= (Games::select('name')->find(($user_game_connector[$idx])->game_id))->name;
                                }
                                $game_name .= ", ";
                            }

                            ($gamers[$gamers_idx])->games = rtrim($game_name, ", ");
                        }

                    }
                }
            }
        }

        foreach ($teams as $team) {
            $team->gamer = ($team->gamer)->get();
        }

        //get if it is open for registration or not
        $registration_status = (Event::select('participant_registration')->find($request->event_id))->participant_registration;

        //get related event
        $event = Event::find($request->event_id);

        if (!is_null($event->city_id)) {
            $event->city = (City::select('name')->find($event->city_id))->name;
        } else {
            $event->city = "-";
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        foreach (EventGameConnector::select('game_id')->where('event_id', $event->id)->get() as $event_game_connector) {
            if (is_null($event->game)) {
                $event->game = (Games::select('name')->find($event_game_connector->game_id))->name;
            } else {
                $event->game .= ", " . (Games::select('name')->find($event_game_connector->game_id))->name;
            }
        }

        //to prevent double resubmission on refresh
        if ($request->action != null) {
            return redirect()->action(
                'EventController@showManageParticipantPage', ['event' => $event, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'teams' => $teams, 'gamers' => $gamers, 'registration_status' => $registration_status]);
        }

        return view('manage_participant', ['event' => $event, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'teams' => $teams, 'gamers' => $gamers, 'registration_status' => $registration_status, 'games' => $games->get()]);
    }

    /**
     * todo: modify this method to get gamer from UserSubroleConnector
     */
    public function showGamerSearchResultPage(Request $request)
    {
        $user_games = array();
        $game_name_idx = 0;
        $users_games_connector = null;
        $game_id = array();
        $game_name = array();
        $user_id = array();
        $user_id_idx = 0;
        $display_name = array();

        $profile_picture = array();

        $user_gender = array();

        $age = array();

        $user_city = array();

        $status = array(); //invited or not
        $status_idx = 0;

        //search by games
        if ($request->game_id != null) {
            $idx = 0;

            //get all game name from searched id (param)
            foreach ($request->game_id as $id) {
                $games = Games::select('name')->where('id', $id)->get();

                $game_id[$idx] = $id;
                $game_name[$idx] = ($games[0])["name"];
                $idx++;
            }

            //get all user id based on  game_id (param)
            foreach ($request->game_id as $id) {
                $ids = UserGameConnector::select('user_id')->where('game_id', $id)->get();
                foreach ($ids as $id_) {
                    $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $id_->user_id)->get();

                    foreach ($user_subrole_connector as $value) {
                        $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                        if ($subrole == "E-Sport Player") {

                            $user_id[$user_id_idx] = $id_->user_id;
                            $user_id_idx++;
                        }
                    }
                }
            }


            //get games for related user_id
            foreach ($user_id as $id) {

                $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                foreach ($users_games_connector as $game) {
                    $games = Games::select('name')->where("id", $game->game_id)->get();


                    foreach ($games as $game) {
                        if (!isset($user_games[$game_name_idx])) {
                            $user_games[$game_name_idx] = "";
                        }

                        $user_games[$game_name_idx] .= $game->name;
                        $user_games[$game_name_idx] .= ", ";
                    }
                }

                $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                $game_name_idx++;
            }
        }

        //search by gender
        if(!is_null($request->gender)){
            if (count($user_id) != 0) {
                $no_of_users = count($user_id);
                for ($idx = 0; $idx < $no_of_users; $idx++) {
                    $gamer = Gamer::select('id')->where('user_id', ($user_id[$idx]))->where('gender', $request->gender)->first();

                    $found = false;

                    if (!is_null($gamer)) {
                        $found = true;
                    }

                    if (!$found) {
                        unset($user_games[$idx]);
                        unset($user_id[$idx]);
                    }
                }

                $this->reindexing($user_games);
                $this->reindexing($user_id);
            } else if(is_null($request->game_id)) {
                $gamers = Gamer::select('user_id')->where('gender', $request->gender)->get();

                foreach ($gamers as $gamer) {
                    $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $gamer->user_id)->get();

                    foreach ($user_subrole_connector as $value) {
                        $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                        if ($subrole == "E-Sport Player") {
                            $user_id[$user_id_idx] = $gamer->user_id;
                            $user_id_idx++;
                        }
                    }
                }

                //get games for related user_id
                foreach ($user_id as $id) {
                    $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }


        //search by city
        if(!is_null($request->city_id)){
            if (count($user_id) != 0) {
                $no_of_users = count($user_id);
                for ($idx = 0; $idx < $no_of_users; $idx++) {
                    $gamer = Gamer::select('id')->where('user_id', ($user_id[$idx]))->where('city_id', $request->city_id)->first();

                    $found = false;

                    if (!is_null($gamer)) {
                        $found = true;
                    }

                    if (!$found) {
                        unset($user_games[$idx]);
                        unset($user_id[$idx]);
                    }
                }

                $this->reindexing($user_games);
                $this->reindexing($user_id);
            } else if(is_null($request->game_id) && is_null($request->gender)) {
                $gamers = Gamer::select('user_id')->where('city_id', $request->city_id)->get();

                foreach ($gamers as $gamer) {
                    $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $gamer->user_id)->get();

                    foreach ($user_subrole_connector as $value) {
                        $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                        if ($subrole == "E-Sport Player") {
                            $user_id[$user_id_idx] = $gamer->user_id;
                            $user_id_idx++;
                        }
                    }
                }

                //get games for related user_id
                foreach ($user_id as $id) {
                    $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }

        //search by keyword
        if ($request->keyword != "") {
            if (count($user_id) != 0) {
                $no_of_users = count($user_id);
                for ($idx = 0; $idx < $no_of_users; $idx++) {
                    $gamer = Gamer::select('display_name')->where('user_id', ($user_id[$idx]))->first();

                    $found = false;

                    if (strpos($gamer->display_name, $request->keyword) !== false) {
                        $found = true;
                    }

                    if (!$found) {
                        unset($user_games[$idx]);
                        unset($user_id[$idx]);
                    }


                }

                $this->reindexing($user_games);
                $this->reindexing($user_id);
            } else if(is_null($request->game_id) && is_null($request->gender) && is_null($request->city_id)) {
                $gamers = Gamer::select('user_id')->where('display_name', 'like', '%' . $request->keyword . '%')->get();

                foreach ($gamers as $gamer) {
                    $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $gamer->user_id)->get();

                    foreach ($user_subrole_connector as $value) {
                        $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                        if ($subrole == "E-Sport Player") {
                            $user_id[$user_id_idx] = $gamer->user_id;
                            $user_id_idx++;
                        }
                    }
                }

                //get games for related user_id
                foreach ($user_id as $id) {
                    $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                    foreach ($users_games_connector as $game) {
                        $games = Games::select('name')->where("id", $game->game_id)->get();


                        foreach ($games as $game) {
                            if (!isset($user_games[$game_name_idx])) {
                                $user_games[$game_name_idx] = "";
                            }

                            $user_games[$game_name_idx] .= $game->name;
                            $user_games[$game_name_idx] .= ", ";
                        }
                    }

                    $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                    $game_name_idx++;
                }
            }
        }

        //get all gamers if filter is empty
        if ($request->game_id == null && $request->keyword == "") {
            $gamers = Gamer::select('user_id')->get();

            foreach ($gamers as $gamer) {
                $user_subrole_connector = UserSubroleConnector::select('subrole_id')->where('user_id', $gamer->user_id)->get();

                foreach ($user_subrole_connector as $value) {
                    $subrole = (Subrole::select('name')->find($value->subrole_id))->name;

                    if ($subrole == "E-Sport Player") {
                        $user_id[$user_id_idx] = $gamer->user_id;
                        $user_id_idx++;
                    }
                }
            }

            //get games for related user_id
            foreach ($user_id as $id) {
                $users_games_connector = UserGameConnector::select('game_id')->where('user_id', $id)->get();

                foreach ($users_games_connector as $game) {
                    $games = Games::select('name')->where("id", $game->game_id)->get();


                    foreach ($games as $game) {
                        if (!isset($user_games[$game_name_idx])) {
                            $user_games[$game_name_idx] = "";
                        }

                        $user_games[$game_name_idx] .= $game->name;
                        $user_games[$game_name_idx] .= ", ";
                    }
                }

                $user_games[$game_name_idx] = rtrim($user_games[$game_name_idx], ", ");
                $game_name_idx++;
            }
        }

//        display name based on selected users
        if (count($user_id) != 0) {
            $idx = 0;
            foreach ($user_id as $id) {
                $gamer = Gamer::select('profile_picture', 'display_name', 'gender', 'dob', 'city_id')->where('user_id', $id)->first();

                $display_name[$idx] = $gamer->display_name;

                $profile_picture[$idx] = $gamer->profile_picture;

                $user_gender[$idx] = $gamer->gender;

                $age[$idx] = null;
                if (!is_null($gamer->dob)) {
                    $d1 = new DateTime($gamer->dob);
                    $d2 = new DateTime();

                    $diff = $d2->diff($d1);

                    $age[$idx] = $diff->y;
                }

                $user_city[$idx] = City::select('name')->find($gamer->city_id);

                if (!is_null($user_city[$idx])) {
                    $user_city[$idx] = ($user_city[$idx])->name;
                }

                $idx++;
            }

        }

//        status (invited or not)
        if (count($user_id) != 0) {
            foreach ($user_id as $id) {
                $gamer = Gamer::where('user_id', $id)->first();

                $participant_management = ParticipantManagement::select('id')->where('action', "Invite")->where('gamer_id', $gamer->id)->where('event_id', $request->event_id)->first();

                $event_game_connector = EventGameConnector::select('id')->where('event_id', $request->event_id)->get();

                $check_if_registered = null;

                foreach ($event_game_connector as $event_game) {
                    $check_if_registered = ParticipantManagement::select('id')->where('action', "Register")->where('gamer_id', $gamer->id)->where('event_game_id', $event_game->id)->first();

                    if (!is_null($check_if_registered)) {
                        break;
                    }
                }


                if ($participant_management == null && is_null($check_if_registered)) {
                    $status[$status_idx] = null;
                } elseif (!is_null($check_if_registered)) {
                    $status[$status_idx] = "Register";
                } else {
                    $status[$status_idx] = "Invite";
                }

                $status_idx++;
            }
        }

        $gender = "Any";

        if ($request->gender == "m") {
            $gender = "Male";
        } else if ($request->gender == "f") {
            $gender = "Female";
        }

        $city = City::find($request->city_id);

        if (is_null($city)) {
            $city = new City;
            $city->name = "Any";
        }

        return view('gamer_search_result', ['game_id' => $game_id, 'game_name' => $game_name, 'gender' => $gender, 'city' => $city, 'keyword' => $request->keyword, 'display_names' => $display_name, 'age' => $age, 'user_gender' => $user_gender, 'user_city' => $user_city, 'user_games' => $user_games, 'user_id' => $user_id, 'event_id' => $request->event_id, 'event_information' => $this->getEventInformation($request->event_id), 'status' => $status, 'profile_picture' => $profile_picture]);
    }

    public function inviteGamer(Request $request)
    {
        if (!is_array($request->user_id)) {
            $gamer = Gamer::where('user_id', $request->user_id)->first();
            $participant_management = new ParticipantManagement;
            $participant_management->action = "Invite";
            $participant_management->message = $request->message;
            $participant_management->event_id = $request->event_id;
            $participant_management->gamer_id = $gamer->id;

            $participant_management->save();
        } else {
            foreach ($request->user_id as $user_id) {
                $gamer = Gamer::where('user_id', $user_id)->first();
                $participant_management = new ParticipantManagement;
                $participant_management->action = "Invite";
                $participant_management->event_id = $request->event_id;
                $participant_management->gamer_id = $gamer->id;

                $participant_management->save();
            }
        }

        return view('invite_gamer', ["user_id" => $request->user_id, "event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id), "subrole_id" => $request->subrole_id, "game_id" => $request->game_id, "keyword" => $request->keyword, "gender" => $request->gender, "city_id" => $request->city_id]);
    }

    public function showParticipantStatusPage()
    {
        $gamer_id = (Gamer::select('id')->where('user_id', Cookie::get("user_id"))->first())->id;

        $participant_managements = ParticipantManagement::where("gamer_id", $gamer_id)->get();

        foreach ($participant_managements as $participant_management) {
            $check_if_registered = null;
            $check_if_responded = null;
            $ignore = false;

            if (is_null($participant_management->event_id)) {
                $event_id = (EventGameConnector::select('event_id')->find($participant_management->event_game_id))->event_id;
                $event = Event::find($event_id);
                if (!is_null($event->city_id)) {
                    $event->city = (City::select('name')->find($event->city_id))->name;
                } else {
                    $event->city = "-";
                }

                if ($event->start_date != null) {
                    $new_date = date('d F Y', strtotime($event->start_date));
                    $event->start_date = $new_date;
                }

                if ($event->end_date != null) {
                    $new_date = date('d F Y', strtotime($event->end_date));
                    $event->end_date = $new_date;
                }

                $participant_management->event = $event;
                $participant_management->event_organizer = EventOrganizer::select('display_name', 'user_id')->find($event->event_organizer_id);
            } else {
                $event = Event::find($participant_management->event_id);
                if (!is_null($event->city_id)) {
                    $event->city = (City::select('name')->find($event->city_id))->name;
                } else {
                    $event->city = "-";
                }
                $participant_management->event = $event;

                $participant_management->event_organizer = EventOrganizer::select('display_name', 'user_id')->find($event->event_organizer_id);

                if (($participant_management->event)->start_date != null) {
                    $new_date = date('d F Y', strtotime(($participant_management->event)->start_date));
                    ($participant_management->event)->start_date = $new_date;
                }

                if (($participant_management->event)->end_date != null) {
                    $new_date = date('d F Y', strtotime(($participant_management->event)->end_date));
                    ($participant_management->event)->end_date = $new_date;
                }
            }
            if ($participant_management->action == "Invite") {
                $event_game_connector = EventGameConnector::select('id', 'game_id')->where('event_id', $participant_management->event_id)->get();


                foreach ($event_game_connector as $event_game) {
                    $check_if_registered = ParticipantManagement::select('id')->where('action', "Register")->where('gamer_id', $gamer_id)->where('event_game_id', $event_game->id)->first();

                    if (!is_null($check_if_registered)) {
                        $ignore = true;
                        break;
                    }


                    $participant_management->game_name .= (Games::select('name')->find($event_game->game_id))->name . ", ";
                }

                $participant_management->game_name = rtrim($participant_management->game_name, ", ");
            }

            if ($participant_management->action == "Register") {
                $check_if_responded = ParticipantManagement::select('id')->where('action', '!=', "Invite")->where('action', '!=', "Register")->where('event_game_id', $participant_management->event_game_id)->first();

                if (!is_null($check_if_responded)) {
                    $ignore = true;
                }
            }


            if (!$ignore) {
                if (!is_null($participant_management->team_id)) {
                    $participant_management->team_name = (Team::select('team_name')->find($participant_management->team_id))->team_name;
                }

                if (!is_null($participant_management->event_game_id)) {
                    $game_id = (EventGameConnector::select('game_id')->find($participant_management->event_game_id))->game_id;
                    $participant_management->game_name = (Games::select('name')->find($game_id))->name;
                }

            } else {
                $participant_management->action = "";
            }
        }

        return view('participant_status', ['participant_managements' => $participant_managements]);
    }

    public function postComment(Request $request)
    {

        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user_id = Cookie::get("user_id");
        $comment->event_id = $request->event_id;
        date_default_timezone_set('Asia/Jakarta');
        $comment->datetime = date('Y-m-d H:i:s', time());
        $comment->save();

        return redirect()->action('EventController@showEventDetails', ['event_id' => $request->event_id]);
    }

    public function readNews()
    {
        //retrieve news
        $news = News::select('title', 'content', 'header_image', 'published_on')->find(Input::get("news_id"));

        $news->published_on = date('F d Y, H:i', strtotime($news->published_on));

        return view('news', ['news' => $news]);
    }

    public function rateEvent(Request $request)
    {
        $user_rating = Rating::where('user_id', Cookie::get("user_id"))->first();

        //check if user has rated
        if (is_null($user_rating)) {
            $user_rating = new Rating;
            $user_rating->event_id = $request->event_id;
            $user_rating->user_id = Cookie::get("user_id");
        }

        $user_rating->rating = $request->rating;
        $user_rating->save();

        return redirect()->action('EventController@showEventDetails', ['event_id' => $request->event_id]);
    }

    public function showinputWinnerForm()
    {
        $event = Event::find(Input::get('event_id'));

        return view('add_winner', ['event_id' => Input::get('event_id'), 'event_information' => $this->getEventInformation(Input::get('event_id'))]);
    }

    public function inputWinnerToDatabase(Request $request)
    {
        $success = false;

        $event_game_id = (EventGameConnector::select('id')->where('event_id', $request->event_id)->where('game_id', $request->game_id)->first())->id;

        if ($request->win_type == "Other") {
            $request->win_type = $request->other_win_type;
        }

        $winner = new Winner;
        $winner->event_game_id = $event_game_id;
        $winner->win_type = $request->win_type;
        $winner->team_id = $request->team_id;
        $success = $winner->save();

        if ($success) {
            foreach (ParticipantManagement::select('gamer_id')->where('team_id', $request->team_id)->where("action", "Confirm")->get() as $participant_management) {
                $experience = Experience::where('event_game_id', $event_game_id)->where('gamer_id', $participant_management->gamer_id)->first();

                $experience->type = $request->win_type;

                $success = $experience->save();
            }
        }

        return view('add_winner', ["success" => $success, "event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id)]);
    }

    public function searchTeam(Request $request)
    {
        if ($request->has('q')) {
            $keyword = $request->q;
            $teams = Team::select('id', 'team_name')->where('team_name', 'LIKE', '%' . $keyword . '%');

            if ($request->has('event_id') && $request->has('game_id')) {
                $event_game_id = (EventGameConnector::select('id')->where('game_id', $request->game_id)->where('event_id', $request->event_id)->first())->id;

                $participant_managements = ParticipantManagement::select('team_id')->where("action", "Confirm")->where('event_game_id', $event_game_id)->get();

                //get game id
                for ($idx = 0; $idx < count($participant_managements); $idx++) {
                    if ($idx == 0) {
                        $teams = $teams->where('id', ($participant_managements[$idx])->team_id);
                    } else {
                        $teams = $teams->orWhere('id', ($participant_managements[$idx])->game_id);
                    }
                }
            }

            if (count($participant_managements) != 0) {
                $teams = $teams->get();
            } else {
                $teams = null;
            }

            if ($teams != null) {
                foreach ($teams as $team) {
                    $team->name = $team->team_name;
                    unset($team->team_name);
                }
            }

            return response()->json($teams);
        } else {
            $teams = Team::select('id', 'team_name');

            if ($request->has('event_id')) {
                $event_game_id = (EventGameConnector::select('id')->where('game_id', $request->game_id)->where('event_id', $request->event_id)->first())->id;


                $participant_managements = ParticipantManagement::select('team_id')->where("action", "Confirm")->where('event_game_id', $event_game_id)->get();

                for ($idx = 0; $idx < count($participant_managements); $idx++) {
                    if ($idx == 0) {
                        $teams = $teams->where('id', ($participant_managements[$idx])->team_id);
                    } else {
                        $teams = $teams->orWhere('id', ($participant_managements[$idx])->team_id);
                    }
                }
            }

            if (count($participant_managements) != 0) {
                $teams = $teams->get();
            } else {
                $teams = null;
            }

            if ($teams != null) {
                foreach ($teams as $team) {
                    $team->name = $team->team_name;
                    unset($team->team_name);
                }
            }

            return response()->json($teams);
        }
    }

    public function getEventInformation($event_id)
    {
        $event = Event::find($event_id);
        if (!is_null($event->start_date)) {
            $event->start_date = date('d F Y', strtotime($event->start_date));
        }

        if (!is_null($event->end_date)) {
            $event->end_date = date('d F Y', strtotime($event->end_date));
        }

        return $event->name . " (" . $event->start_date . " - " . $event->end_date . ")";
    }

    public function showManageWinnerPage()
    {
        foreach (EventGameConnector::select("id", "game_id")->where("event_id", Input::get("event_id"))->get() as $event_game) {
            $winners = Winner::where("event_game_id", $event_game->id)->get();

            foreach ($winners as $winner) {
                $winner->team_name = (Team::select('team_name')->find($winner->team_id))->team_name;
                $winner->game_name = (Games::select('name')->find($event_game->game_id))->name;
            }
        }

        return view('manage_winner', ["event_id" => Input::get("event_id"), "event_information" => $this->getEventInformation(Input::get("event_id")), "winners" => $winners]);
    }

    public function deleteWinner(Request $request)
    {
        Winner::find($request->winner_id)->delete();

        return redirect()->action('EventController@showManageWinnerPage', ['event_id' => $request->event_id]);
    }

    public function showUpdateWinnerPage()
    {
        $winner = Winner::find(Input::get('winner_id'));
        $winner->game_id = (EventGameConnector::select('game_id')->find($winner->event_game_id))->game_id;
        $winner->game_name = (Games::select('name')->find($winner->game_id))->name;
        $winner->team_name = (Team::select('team_name')->find($winner->team_id))->team_name;

        return view('update_winner', ['event_id' => Input::get('event_id'), 'event_information' => $this->getEventInformation(Input::get('event_id')), 'winner' => $winner, 'success' => Input::get("success")]);
    }

    public function updateWinner(Request $request)
    {
        $success = false;

        $winner = Winner::find($request->winner_id);
        $previous_event_game_id = $winner->event_game_id;
        $previous_win_type = $winner->win_type;

        $event_game_id = (EventGameConnector::select('id')->where('event_id', $request->event_id)->where('game_id', $request->game_id)->first())->id;

        if ($request->win_type == "Other") {
            $request->win_type = $request->other_win_type;
        }

        $winner->event_game_id = $event_game_id;
        $winner->win_type = $request->win_type;
        $winner->team_id = $request->team_id;
        $success = $winner->save();

        if ($success) {
            Experience::where('event_game_id', $previous_event_game_id)->where('win_type', $previous_win_type)->delete();

            foreach (ParticipantManagement::select('gamer_id')->where('team_id', $request->team_id)->where("action", "Confirm")->get() as $participant_management) {
                $experience = Experience::where('event_game_id', $event_game_id)->where('gamer_id', $participant_management->gamer_id)->first();

                $experience->type = $request->win_type;

                $success = $experience->save();
            }
        }

        return redirect()->action('EventController@updateWinner', ["success" => $success, "event_id" => $request->event_id, "winner_id" => $request->winner_id]);
    }

    public function showAddStreamingChannelForm()
    {
        return view('add_streaming_channel', ['event_id' => Input::get("event_id"), 'event_information' => $this->getEventInformation(Input::get("event_id"))]);
    }

    public function addStreamingChannelToDB(Request $request)
    {
        $streaming_channel = new StreamingChannel;
        $streaming_channel->title = $request->title;
        $streaming_channel->url = $request->url;
        $streaming_channel->event_id = $request->event_id;
        $success = false;

        if (!is_null($request->start_date)) {
            if (is_null($request->start_time)) {
                $request->start_time = "0:00";
            }
            $streaming_channel->start_datetime = DateTime::createFromFormat('Y-m-d:H:i', $request->start_date . ":" . $request->start_time)->format('Y-m-d H:i:s');
        }

        $success = $streaming_channel->save();

        return view('add_streaming_channel', ['event_id' => Input::get("event_id"), 'event_information' => $this->getEventInformation(Input::get("event_id")), 'success' => $success]);
    }

    public function showManageStreamingChannelPage()
    {
        $streaming_channels = StreamingChannel::where('event_id', Input::get("event_id"))->get();

        foreach ($streaming_channels as $streaming_channel) {
            if (!is_null($streaming_channel->start_datetime)) {
                $streaming_channel->start_datetime = DateTime::createFromFormat('Y-m-d H:i:s', $streaming_channel->start_datetime)->format('d F Y, H:i');
            }
        }

        $event = Event::find(Input::get("event_id"));

        if (!is_null($event->city_id)) {
            $event->city = (City::select('name')->find($event->city_id))->name;
        } else {
            $event->city = "-";
        }

        if ($event->start_date != null) {
            $new_date = date('d F Y', strtotime($event->start_date));
            $event->start_date = $new_date;
        }

        if ($event->end_date != null) {
            $new_date = date('d F Y', strtotime($event->end_date));
            $event->end_date = $new_date;
        }

        foreach (EventGameConnector::select('game_id')->where('event_id', $event->id)->get() as $event_game_connector) {
            if (is_null($event->game)) {
                $event->game = (Games::select('name')->find($event_game_connector->game_id))->name;
            } else {
                $event->game .= ", " . (Games::select('name')->find($event_game_connector->game_id))->name;
            }
        }

        return view('manage_streaming_channel', ['event' => $event, 'streaming_channels' => $streaming_channels, 'event_id' => Input::get("event_id"), 'event_information' => $this->getEventInformation(Input::get("event_id"))]);
    }

    public function deleteStreamingChannel(Request $request)
    {
        StreamingChannel::find($request->streaming_channel_id)->delete();
        return Redirect()->action('EventController@showManageStreamingChannelPage', ['event_id' => $request->event_id]);
    }

    public function showUpdateStreamingChannelPage()
    {
        $streaming_channel = StreamingChannel::find(Input::get("streaming_channel_id"));
        if (!is_null(StreamingChannel::find(Input::get("streaming_channel_id"))->start_datetime)) {
            $streaming_channel->start_date = DateTime::createFromFormat('Y-m-d H:i:s', StreamingChannel::find(Input::get("streaming_channel_id"))->start_datetime)->format('Y-m-d');
            $streaming_channel->start_time = DateTime::createFromFormat('Y-m-d H:i:s', StreamingChannel::find(Input::get("streaming_channel_id"))->start_datetime)->format('H:i');
        }
        return view('update_streaming_channel', ["streaming_channel" => $streaming_channel, "event_id" => Input::get("event_id"), "event_information" => $this->getEventInformation(Input::get("event_id"))]);
    }

    public function updateStreamingChannel(Request $request)
    {
        $streaming_channel = StreamingChannel::find($request->streaming_channel_id);
        $streaming_channel->title = $request->title;
        $streaming_channel->url = $request->url;
        $streaming_channel->event_id = $request->event_id;
        $success = false;

        if (!is_null($request->start_date)) {
            if (is_null($request->start_time)) {
                $request->start_time = "0:00";
            }
            $streaming_channel->start_datetime = DateTime::createFromFormat('Y-m-d:H:i', $request->start_date . ":" . $request->start_time)->format('Y-m-d H:i:s');
        }

        $success = $streaming_channel->save();

        return view('update_streaming_channel', ["streaming_channel" => $streaming_channel, 'event_id' => Input::get("event_id"), 'event_information' => $this->getEventInformation(Input::get("event_id")), 'success' => $success]);
    }

    public function publishEvent(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->status = "Published";

        $event->save();

        return Redirect::action('EventController@showMyEventPage');
    }

    public function unpublishEvent(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->status = "Draft";

        $event->save();

        return Redirect::action('EventController@showMyEventPage');
    }

    public function openRegistration(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->participant_registration = true;
        $event->save();

        return redirect()->action('EventController@showManageParticipantPage', ['event_id' => $request->event_id]);
    }

    public function closeRegistration(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->participant_registration = false;
        $event->save();

        return redirect()->action('EventController@showManageParticipantPage', ['event_id' => $request->event_id]);
    }

    public function showEventLocationDetailsPage()
    {
        $event_location = EventLocation::find(Input::get('event_location_id'));
        $event_location->city = (City::select('name')->find($event_location->city_id))->name;

        return view('event_location_details', ['event_location' => $event_location, 'event_location_media' => EventLocationMedia::where('event_location_id', Input::get('event_location_id'))->get()]);
    }

    public function showSearchEventLocationPage()
    {
        $event_locations = array();

        if (!is_null(Input::get('q'))) {
            $event_locations = EventLocation::where('name', 'like', '%' . Input::get('q') . '%')->get();

            foreach ($event_locations as $event_location) {
                $event_location->city = (City::select('name')->find($event_location->city_id))->name;
            }
        }

        return view('search_event_location', ['q' => Input::get('q'), 'event_locations' => $event_locations]);
    }

    public function changeVacancyStatus(Request $request)
    {
        $vacancy = Vacancy::find($request->vacancy_id);

        $vacancy->open = $request->action;

        $vacancy->save();

        return view('change_vacancy_status', ['event_id' => $request->event_id]);
    }

    public function sendProposal(Request $request)
    {

        if (($request->file('proposal'))->move('proposal/' . $request->event_id . '/' . (Company::select('user_id')->find($request->company_id))->user_id, 'proposal.pdf')) {
            $sponsorship_managements = new SponsorshipManagement;
            $sponsorship_managements->action = "Invite";
            $sponsorship_managements->proposal = 1;
            $sponsorship_managements->event_id = $request->event_id;
            $sponsorship_managements->company_id = $request->company_id;
            $sponsorship_managements->save();
        }


        return view('broadcast_package', ["event_id" => $request->event_id, "event_information" => $this->getEventInformation($request->event_id), "industry_id" => $request->industry_id, "keyword" => $request->keyword]);
    }
}
