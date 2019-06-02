<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>

    <script>
        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function showModal(action, worker_id, subrole_id) {
            $("#worker_id").val(worker_id);
            $("#subrole_id").val(subrole_id);
            $("#action").val(action);
            $("#action_btn").text(action);
        }
    </script>

    <style>
        li {
            cursor: pointer;
        }

        td {
            padding: 15px;
            font-size: 20px;
        }
    </style>
</head>
<body>
<h1 style="font-size: 35px; margin-left: 1%; margin-top: 1%"><b>Role Vacancy Status</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 20px">{{$event_information}}</h2>


<ul class="nav nav-tabs" style="margin-left: 15px; margin-right: 15px">
    <li class="active" id="unanswered"><a>Unanswered</a></li>
    <li id="registered"><a>Registered</a></li>
    <li id="confirmed"><a>Confirmed</a></li>
    <li id="denied"><a>Denied</a></li>
</ul>
<br>
@php $subrole_idx = 0; @endphp

<div id="unanswered_list">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($vacancy_managements as $vacancy_management)
                            @if($vacancy_management->action === "Invite")
                                @foreach($workers as $worker)

                                    @if($worker->id == $vacancy_management->worker_id)
                                        <div class="col-md-4 text-center">
                                            <div class="work-inner">
                                                @if(!is_null($worker->profile_picture))
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/profile_picture/{{$worker->profile_picture}});"></a>
                                                @else
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                                @endif

                                                <div class="desc">
                                                    <h3>
                                                        <a href="/view_profile?user_id={{$worker->user_id}}">{{$worker->display_name}}</a> @if($worker->gender == "m")
                                                            <span><img src="images/ic_male.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Male"/> </span> @elseif($worker->gender == "f")
                                                            <span><img src="images/ic_female.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Female"/> </span>@endif
                                                    </h3>
                                                    <p>@if(!is_null($worker->city)){{$worker->city." -
                                                        "}}@endif @if(!is_null($worker->age)) {{$worker->age}} Years
                                                        Old @endif </p>

                                                    <div style="margin-top: -20px">({{$user_subroles[$subrole_idx]}})
                                                    </div>
                                                    @php $subrole_idx++; @endphp
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

            </td>
        </tr>
    </table>
</div>

<div id="registered_list" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($vacancy_managements as $vacancy_management)
                            @if($vacancy_management->action == "Register")
                                @foreach($workers as $worker)
                                    @if($worker->id == $vacancy_management->worker_id)
                                        <div class="col-md-4 text-center">
                                            <div class="work-inner">
                                                @if(!is_null($worker->profile_picture))
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/profile_picture/{{$worker->profile_picture}});"></a>
                                                @else
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                                @endif

                                                <div class="desc">
                                                    <h3>
                                                        <a href="/view_profile?user_id={{$worker->user_id}}">{{$worker->display_name}}</a> @if($worker->gender == "m")
                                                            <span><img src="images/ic_male.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Male"/> </span> @elseif($worker->gender == "f")
                                                            <span><img src="images/ic_female.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Female"/> </span>@endif
                                                    </h3>
                                                    <p>@if(!is_null($worker->city)){{$worker->city." -
                                                        "}}@endif @if(!is_null($worker->age)) {{$worker->age}} Years
                                                        Old @endif </p>

                                                    <div style="margin-top: -20px">({{$user_subroles[$subrole_idx]}})
                                                    </div>
                                                    @php $subrole_idx++; @endphp

                                                    <div class="row" style="margin-top: 20px">
                                                        <div class="col-md-6">
                                                            <button class="btn btn-primary"
                                                                    style="width: 100%"
                                                                    onclick="showModal('Confirm', {{$worker->id}}, {{$vacancy_management->subrole_id}})"
                                                            data-toggle="modal" data-target="#inviteModal">
                                                                <b>Confirm</b>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <button class="btn btn-danger"
                                                                    style="width: 100%;"
                                                                    onclick="showModal('Decline', {{$worker->id}}, {{$vacancy_management->subrole_id}})" data-toggle="modal" data-target="#inviteModal">
                                                                <b>Decline</b>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="confirmed_list" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($vacancy_managements as $vacancy_management)
                            @if($vacancy_management->action == "Confirm")
                                @foreach($workers as $worker)
                                    @if($worker->id == $vacancy_management->worker_id)
                                        <div class="col-md-4 text-center">
                                            <div class="work-inner">
                                                @if(!is_null($worker->profile_picture))
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/profile_picture/{{$worker->profile_picture}});"></a>
                                                @else
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                                @endif

                                                <div class="desc">
                                                    <h3>
                                                        <a href="/view_profile?user_id={{$worker->user_id}}">{{$worker->display_name}}</a> @if($worker->gender == "m")
                                                            <span><img src="images/ic_male.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Male"/> </span> @elseif($worker->gender == "f")
                                                            <span><img src="images/ic_female.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Female"/> </span>@endif
                                                    </h3>
                                                    <p>@if(!is_null($worker->city)){{$worker->city." -
                                                        "}}@endif @if(!is_null($worker->age)) {{$worker->age}} Years
                                                        Old @endif </p>

                                                    <div style="margin-top: -20px">({{$user_subroles[$subrole_idx]}})
                                                    </div>
                                                    @php $subrole_idx++; @endphp
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="denied_list" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($vacancy_managements as $vacancy_management)
                            @if($vacancy_management->action == "Decline")
                                @foreach($workers as $worker)
                                    @if($worker->id == $vacancy_management->worker_id)
                                        <div class="col-md-4 text-center">
                                            <div class="work-inner">
                                                @if(!is_null($worker->profile_picture))
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/profile_picture/{{$worker->profile_picture}});"></a>
                                                @else
                                                    <a href="/view_profile?user_id={{$worker->user_id}}"
                                                       class="work-grid"
                                                       style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                                @endif

                                                <div class="desc">
                                                    <h3>
                                                        <a href="/view_profile?user_id={{$worker->user_id}}">{{$worker->display_name}}</a> @if($worker->gender == "m")
                                                            <span><img src="images/ic_male.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Male"/> </span> @elseif($worker->gender == "f")
                                                            <span><img src="images/ic_female.png" width="20px"
                                                                       height="20px"
                                                                       data-toggle="tooltip"
                                                                       title="Female"/> </span>@endif
                                                    </h3>
                                                    <p>@if(!is_null($worker->city)){{$worker->city." -
                                                        "}}@endif @if(!is_null($worker->age)) {{$worker->age}} Years
                                                        Old @endif </p>

                                                    <div style="margin-top: -20px">({{$user_subroles[$subrole_idx]}})
                                                    </div>
                                                    @php $subrole_idx++; @endphp
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Optional Message when Confirming/Declining MODAL-->
<div class="container">
    <div class="modal fade" id="inviteModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Optional Message</h4>
                </div>
                <form action="vacancy_status" method="post">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group" style="margin-top: 5px">
                            <b><label>Message (Optional)</label></b><br>
                            <textarea class="form-control" rows="5" placeholder="Type here..."
                                      name="message"></textarea>
                        </div>
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="worker_id" id="worker_id"/>
                        <input type="hidden" name="subrole_id"
                               id="subrole_id"/>
                        <input type="hidden" name="action"
                               id="action"/>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default" id="action_btn"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("#confirmed").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#registered").removeClass("active");
            $("#denied").removeClass("active");
            $("#unanswered").removeClass("active");


            $("#confirmed_list").show();
            $("#registered_list").hide();
            $("#denied_list").hide();
            $("#unanswered_list").hide();

        }
    });
    $("#registered").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#confirmed").removeClass("active");
            $("#denied").removeClass("active");
            $("#unanswered").removeClass("active");

            $("#confirmed_list").hide();
            $("#registered_list").show();
            $("#denied_list").hide();
            $("#unanswered_list").hide();
        }
    });
    $("#denied").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#confirmed").removeClass("active");
            $("#registered").removeClass("active");
            $("#unanswered").removeClass("active");

            $("#confirmed_list").hide();
            $("#registered_list").hide();
            $("#denied_list").show();
            $("#unanswered_list").hide();
        }
    });

    $("#unanswered").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#confirmed").removeClass("active");
            $("#registered").removeClass("active");
            $("#denied").removeClass("active");

            $("#confirmed_list").hide();
            $("#registered_list").hide();
            $("#denied_list").hide();
            $("#unanswered_list").show();
        }
    });
</script>
</body>
</html>