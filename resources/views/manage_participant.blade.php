<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        // $("#dashboard_nav_bar").show();
        // document.getElementById("manage_event").className = 'active';
        // document.getElementById("dashboard").className = 'active';
        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function selectAll() {
            $('.industry_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
        }
    </script>

    <style>
        li {
            cursor: pointer;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
</head>
<body style="background-color: whitesmoke">

<div class="container-fluid">
    <div class="row" style="background-color: white; width: 98%; padding: 1%; margin: 1%; border-radius: 10px;">
        <div class="col-md-4 text-center">
            <img style=" height: 225px; width: 400px; border-radius: 10px;"
                 src="/images/event_brochure/{{$event->brochure}}"/>
        </div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <p style="margin-top: 10px; margin-left: -30px;">
                    <b>
                        <a href="event_details?event_id={{$event->id}}" style="color: black">{{$event->name}}</a>
                    </b>
                </p>
                <hr>
                <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                     style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                    @if($event->city != null){{$event->city}} @else - @endif
                </div>
                <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                       style="width: 30px; margin-right: 10px; margin-top: -5px">
                    @if($event->start_date != null) {{$event->start_date}} - {{$event->end_date}} @else - @endif
                </div>
                <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                    style="width: 30px; margin-right: 10px; margin-top: -5px">
                    {{$event->game}}

                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <ul id="dashboard_nav_bar" class="nav nav-pills nav-justified justify-content-center"
            style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">
            <li id="participant" class="active">
                <form action="manage_participant" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_participant">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_participant').submit()">Participant</a>
            </li>
            <li id="manage_event">
                <form action="manage_vacancy" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_vacancy">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_vacancy').submit()">Vacancy</a>
            </li>
            <li id="event_location">
                <form action="manage_news" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_news">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_news').submit()">News</a>
            </li>
            <li id="event_location">
                <form action="manage_sponsorship_package" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_sponsorship_package">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_sponsorship_package').submit()">Sponsorship</a>
            </li>
            <li id="event_location"><a href="/manage_streaming_channel?event_id={{$event->id}}">Streaming Channels</a></li>
        </ul>
    </div>
    <div class="row"
         style="background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">

        <div class="col-md-6" style="margin-top: 10px">
            <h1 style="font-size: 30px;"><b>@if($registration_status == true) Open
                    for
                    Registration @elseif($registration_status == false) Registration Closed @endif </b></h1>
        </div>

        <div class="col-md-6" style="margin-top: 30px" align="right">
            @if($registration_status == true)
                <form action="close_registration" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    <button class="btn btn-primary" type="submit" style="width: 170px;"><b>Close
                            Registration</b></button>
                </form>
            @elseif($registration_status == false)
                <form action="open_registration" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    <button class="btn btn-primary" type="submit" style="width: 170px;"><b>Open
                            Registration</b></button>
                </form>
            @endif
        </div>


    </div>
    <div class="row"
         style="background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">
        <hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%;">
    </div>
    <div class="row"
         style="background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">
        {{--<div class="col-md-9">--}}
        {{--<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 20px">{{$event_information}}</h2>--}}
        {{--</div>--}}
        <div class="col-md-12">
            <span style="float: left">
            <button type="submit" class="form-control btn btn-primary"
                    style="width: 170px; margin-right: 15px"
                    data-toggle="modal" data-target="#myModal"><b>Search Participant</b></button>
            </span>
            <span style="float: left">
            <form action="manage_winner" method="get">
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="width: 170px; "><b>Manage Winner</b></button>
            </form>
</span>

        </div>
    </div>
    <div class="row"
         style="background-color: white; width: 98%; padding: 1%; margin-left: 1%; margin-right: 1%;">
        <ul class="nav nav-pills" style="margin-left: 15px; margin-right: 15px">
            <li class="active" id="Register" onclick="clickedTab('Register')"><a>Registered</a></li>
            <li id="Confirm" onclick="clickedTab('Confirm')"><a>Confirmed</a></li>
            <li id="Decline" onclick="clickedTab('Decline')"><a>Declined</a></li>
            <li id="Invite" onclick="clickedTab('Invite')"><a>Unanswered</a></li>
        </ul>
        <br>
        <div id="Register_participants" style="display: none" class="participant_list">
            <table style="margin: 1%;">
                <tr>
                    <td>
                        <div class="container">
                            <div class="row">
                                @foreach($teams as $team)
                                    @if($team->action == "Register")
                                        <div class="col-md-2"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                            <div align="center">
                                                <img src="/images/team_logo/{{$team->team_logo}}"
                                                     width="100px"
                                                     height="100px"/>
                                            </div>
                                            <div align="center" style="font-size: 20px; margin-top: 10px;">
                                                <b>{{$team->team_name}}</b>
                                            </div>

                                            <div align="center" style="font-size: 20px;">
                                                ({{$team->game}})
                                            </div>

                                            <hr>
                                            <b style="font-size: larger">Roster</b>
                                            <pre style="font-size: 20px; font-family: 'Arial'; margin-top: 5px">@for($idx = 0 ; $idx < count($team->gamer) ; $idx++){{$idx+1 . '. '}}<a href="view_profile?user_id={{(($team->gamer)[$idx])->user_id}}"
                                                   style="color: black">{{(($team->gamer)[$idx])->display_name}}</a>@endfor</pre>

                                            <form action="manage_participant" method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                                                <input type="hidden" name="event_information"
                                                       value="{{$event_information}}"/>
                                                <input type="hidden" name="team_id" value="{{$team->id}}"/>
                                                <input type="hidden" name="event_game_id"
                                                       value="{{$team->event_game_id}}"/>
                                                <button type="submit" class="btn btn-primary" style="width: 160px"
                                                        name="action"
                                                        value="Confirm">
                                                    <b>Confirm</b>
                                                </button>
                                                <button type="submit" class="btn btn-primary"
                                                        style="background-color: darkred; width: 160px;" name="action"
                                                        value="Decline">
                                                    <b>Decline</b>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="Confirm_participants" style="display: none" class="participant_list">
            <table style="margin: 1%;">
                <tr>
                    <td>
                        <div class="container">
                            <div class="row">

                                @foreach($teams as $team)
                                    @if($team->action == "Confirm")
                                        <div class="col-md-2"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                            <div align="center">
                                                <img src="/images/team_logo/{{$team->team_logo}}"
                                                     width="100px"
                                                     height="100px"/>
                                            </div>
                                            <div align="center" style="font-size: 20px; margin-top: 10px;">
                                                <b>{{$team->team_name}}</b>
                                            </div>

                                            <div align="center" style="font-size: 20px;">
                                                ({{$team->game}})
                                            </div>

                                            <hr>
                                            <b style="font-size: larger">Roster</b>
                                            <pre style="font-size: 20px; font-family: 'Arial'; margin-top: 5px">@for($idx = 0 ; $idx < count($team->gamer) ; $idx++){{$idx+1 . '. '}}<a href="view_profile?user_id={{(($team->gamer)[$idx])->user_id}}"
                                                   style="color: black">{{(($team->gamer)[$idx])->display_name}}</a><br>@endfor</pre>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="Decline_participants" style="display: none" class="participant_list">
            <table style="margin: 1%;">
                <tr>
                    <td>
                        <div class="container">
                            <div class="row">

                                @foreach($teams as $team)
                                    @if($team->action == "Decline")
                                        <div class="col-md-2"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                            <div align="center">
                                                <img src="/images/team_logo/{{$team->team_logo}}"
                                                     width="100px"
                                                     height="100px"/>
                                            </div>
                                            <div align="center" style="font-size: 20px; margin-top: 10px;">
                                                <b>{{$team->team_name}}</b>
                                            </div>

                                            <div align="center" style="font-size: 20px;">
                                                ({{$team->game}})
                                            </div>

                                            <hr>
                                            <b style="font-size: larger">Roster</b>
                                            <pre style="font-size: 20px; font-family: 'Arial'; margin-top: 5px">@for($idx = 0 ; $idx < count($team->gamer) ; $idx++){{$idx+1 . '. '}}
                                                <a href="view_profile?user_id={{(($team->gamer)[$idx])->user_id}}"
                                                   style="color: black">{{(($team->gamer)[$idx])->display_name}}</a>@endfor</pre>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Unanswered Participant -->
        <div id="Invite_participants" style="display: none" class="participant_list">
            <table style="margin: 1%;">
                <tr>
                    <td>
                        <div class="container">
                            <div class="row">

                                @foreach($gamers as $gamer)

                                    <div class="col-md-2"
                                         style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                        <div align="center">
                                            <a href="/view_profile?user_id={{$gamer->user_id}}" style="color: black">
                                                <img src="/images/profile_picture/{{$gamer->profile_picture}}"
                                                     width="100px" height="100px"
                                                     class="img-circle" style="margin-top: 10px"/>
                                            </a>
                                        </div>
                                        <div align="center" style="font-size: 20px; margin-top: 10px;">
                                            <b><a href="/view_profile?user_id={{$gamer->user_id}}"
                                                  style="color: black">{{$gamer->display_name}}</a> </b>
                                        </div>

                                        <div align="center" style="font-size: 20px;">
                                            ({{$gamer->games}})
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
        </div>
        </td>
        </tr>
        </table>
    </div>
</div>
</div>

<!-- Search gamer modal-->
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search Gamer</h4>
                </div>
                <form action="gamer_search_result" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 5px">
                            <b><label>Games</label></b><br>
                            <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                                    style="width: 567px">
                                @foreach($games as $game)
                                    <option value="{{$game->id}}" selected>{{$game->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <b><label>Keyword</label></b><br>
                            <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                                   name="keyword">
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="event_information" value="{{$event_information}}"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    clickedTab("Register");

    function clickedTab(id) {
        $(".active").removeClass("active");
        $("#" + id).addClass("active");
        $("#participant").addClass("active");
        $(".participant_list").hide();
        $("#" + id + "_participants").show();
        console.log(id + "_participants");
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.cari').select2({
        placeholder: 'Type its Name',
        ajax: {
            url: '/search_game',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
</body>
</html>