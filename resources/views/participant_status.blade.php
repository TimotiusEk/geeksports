<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <script>
        document.getElementById("participant_status").className = 'active';


        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });
    </script>

    <style>
        li {
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: whitesmoke">
<ul class="nav nav-tabs" style="margin-left: 15px; margin-right: 15px">
    <li class="active" id="Register" onclick="updateList('Register')"><a>Registered</a></li>
    <li id="Confirm" onclick="updateList('Confirm')"><a>Confirmed</a></li>
    <li id="Decline" onclick="updateList('Decline')"><a>Declined</a></li>
    <li id="Invite" onclick="updateList('Invite')"><a>Invited</a></li>
</ul>
<br>

<div id="Register_list" class="list" style="display: none">
    @foreach($participant_managements as $participant_management)
        @if($participant_management->action == "Register")
            <div class="container"
                 style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%; padding-bottom: 1%;">
                <div class="row">

                    <div class="col-md-3 text-center"><img
                                style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px;"
                                src="/images/event_brochure/{{($participant_management->event)->brochure}}"/></div>
                    <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                        <div style="margin-left: 70px">
                            <p style="margin-top: 20px; margin-left: -30px;">
                                <b><a href="/event_details?event_id={{($participant_management->event)->id}}">{{($participant_management->event)->name}}</a></b></p>
                            <hr>
                            <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                                 style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                                {{($participant_management->event)->city}}
                            </div>
                            <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                                   style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{($participant_management->event)->start_date}}
                                - {{($participant_management->event)->end_date}}
                            </div>
                            <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                                style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{$participant_management->game_name}} with {{$participant_management->team_name}}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endforeach
</div>

<div id="Confirm_list" class="list" style="display: none">
    @foreach($participant_managements as $participant_management)
        @if($participant_management->action == "Confirm")
            <div class="container"
                 style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%; padding-bottom: 1%;">

                <div class="row">
                    <div class="col-md-3 text-center"><img
                                style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px;"
                                src="/images/event_brochure/{{($participant_management->event)->brochure}}"/></div>
                    <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                        <div style="margin-left: 70px">
                            <p style="margin-top: 20px; margin-left: -30px;">
                                <b><a href="/event_details?event_id={{($participant_management->event)->id}}">{{($participant_management->event)->name}}</a></b></p>
                            <hr>
                            <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                                 style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                                {{($participant_management->event)->city}}
                            </div>
                            <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                                   style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{($participant_management->event)->start_date}}
                                - {{($participant_management->event)->end_date}}
                            </div>
                            <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                                style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{$participant_management->game_name}} with {{$participant_management->team_name}}
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        @endif
    @endforeach
</div>

<div id="Decline_list" class="list" style="display: none">
    @foreach($participant_managements as $participant_management)
        @if($participant_management->action == "Decline")
            <div class="container"
                 style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%; padding-bottom: 1%;">

                <div class="row">
                    <div class="col-md-3 text-center"><img
                                style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px;"
                                src="/images/event_brochure/{{($participant_management->event)->brochure}}"/></div>
                    <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                        <div style="margin-left: 70px">
                            <p style="margin-top: 20px; margin-left: -30px;">
                                <b><a href="/event_details?event_id={{($participant_management->event)->id}}">{{($participant_management->event)->name}}</a></b></p>
                            <hr>
                            <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                                 style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                                {{($participant_management->event)->city}}
                            </div>
                            <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                                   style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{($participant_management->event)->start_date}}
                                - {{($participant_management->event)->end_date}}
                            </div>
                            <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                                style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{$participant_management->game_name}} with {{$participant_management->team_name}}
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        @endif
    @endforeach
</div>

<div id="Invite_list" class="list" style="display: none">
    @foreach($participant_managements as $participant_management)
        @if($participant_management->action == "Invite")
            <div class="container"
                 style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%; padding-bottom: 1%;">

                <div class="row">
                    <div class="col-md-3 text-center"><img
                                style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px;"
                                src="/images/event_brochure/{{($participant_management->event)->brochure}}"/></div>
                    <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                        <div style="margin-left: 70px">
                            <p style="margin-top: 20px; margin-left: -30px;">
                                <b><a href="/event_details?event_id={{($participant_management->event)->id}}">{{($participant_management->event)->name}}</a></b> <div align="right" style="margin-top: -60px"><form action="/participant_registration_form" method="post"> {{csrf_field()}} <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/register_btn.png" width="130px"/></a> <input type="hidden" name="event_id" value="{{($participant_management->event)->id}}"/></form></div></p>
                            <hr>
                            <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                                 style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                                {{($participant_management->event)->city}}
                            </div>
                            <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                                   style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{($participant_management->event)->start_date}}
                                - {{($participant_management->event)->end_date}}
                            </div>
                            <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                                style="width: 30px; margin-right: 10px; margin-top: -5px">
                                {{$participant_management->game_name}}
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        @endif
    @endforeach
</div>

<script>
    updateList("Register");

    function updateList(id) {
        $(".active").removeClass("active");
        $("#participant_status").addClass("active");
        $("#" + id).addClass("active");
        $(".list").hide();
        $("#" + id + "_list").show();
    }
</script>

</body>
</html>