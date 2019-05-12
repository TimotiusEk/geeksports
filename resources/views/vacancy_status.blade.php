<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    <script>
        $("#dashboard_nav_bar").show();
        document.getElementById("manage_event").className = 'active';
        document.getElementById("dashboard").className = 'active';

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function selectAll() {
            $('.subrole_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
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
<h1 style="font-size: 35px; margin-left: 1%;"><b>Role Vacancy Status</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 20px">{{$event_information}}</h2>

<button type="submit" class="form-control btn btn-primary"
        style="position: relative; left: 88%; padding-left: 30px; padding-right: 30px; width: fit-content"
        id="show_sponsor_status_btn" data-toggle="modal" data-target="#myModal"><b>Search Worker</b></button>


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
                                        <div class="col-md-4"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 250px; height: 250px;margin: 1%; padding: 1%">
                                            <div align="center" style="margin-top: 30px">
                                                <a href="/view_profile?user_id={{$worker->user_id}}"><img
                                                            src="/images/profile_picture/{{$worker->profile_picture}}"
                                                            width="100px"
                                                            height="100px"
                                                            class="img-circle"/>
                                                </a>
                                            </div>
                                            <div align="center" style="font-size: 22px; margin-top: 5px;">
                                                <b><a href="/view_profile?user_id={{$worker->user_id}}" style="color: black">{{$worker->display_name}}</a></b>
                                                <br>
                                                <p style="font-size: 18px">{{$user_subroles[$subrole_idx]}}
                                                    ({{$user_games[$subrole_idx]}})</p>
                                                @php $subrole_idx++; @endphp
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

<div id="registered_list">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($vacancy_managements as $vacancy_management)

                            @if($vacancy_management->action == "Register")

                                @foreach($workers as $worker)
                                    @if($worker->id == $vacancy_management->worker_id)
                                        <div class="col-md-4"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 250px; height: 350px;margin: 1%; padding: 1%">
                                            <div align="center" style="margin-top: 30px">
                                                <a href="/view_profile?user_id={{$worker->user_id}}"><img src="/images/sample_profile_picture_1.jpg" width="100px"
                                                     height="100px"
                                                     class="img-circle"/>
                                                </a>
                                            </div>
                                            <div align="center" style="font-size: 22px; margin-top: 5px;">
                                                <b><a href="/view_profile?user_id={{$worker->user_id}}" style="color: black">{{$worker->display_name}}</a></b>
                                                <br>
                                                <p style="font-size: 18px">{{$user_subroles[$subrole_idx]}}</p>
                                                @php $subrole_idx++; @endphp
                                            </div>
                                            <div style="position: absolute; bottom: 0; margin-left: 10px; margin-bottom: 10px">
                                                <form method="post" action="vacancy_status">
                                                    {{csrf_field()}}

                                                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                                                    <input type="hidden" name="event_information"
                                                           value="{{$event_information}}"/>
                                                    <input type="hidden" name="worker_id" value="{{$worker->id}}"/>
                                                    <input type="hidden" name="subrole_id"
                                                           value="{{$vacancy_management->subrole_id}}"/>
                                                    <button type="submit" class="btn btn-primary" style="width: 100px"
                                                            name="action" value="Confirm">
                                                        <b>Confirm</b>
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"
                                                            style="background-color: darkred; width: 100px;"
                                                            name="action" value="Decline"><b>Decline</b>
                                                    </button>
                                                </form>
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
                                        <div class="col-md-4"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 250px; height: 250px;margin: 1%; padding: 1%">
                                            <div align="center" style="margin-top: 30px">
                                                <a href="/view_profile?user_id={{$worker->user_id}}"><img src="/images/sample_profile_picture_1.jpg" width="100px"
                                                     height="100px"
                                                     class="img-circle"/>
                                                </a>
                                            </div>
                                            <div align="center" style="font-size: 22px; margin-top: 5px;">
                                                <b><a href="/view_profile?user_id={{$worker->user_id}}" style="color: black">{{$worker->display_name}}</a></b>
                                                <br>
                                                <p style="font-size: 18px">{{$user_subroles[$subrole_idx]}}</p>
                                                @php $subrole_idx++; @endphp
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
                                        <div class="col-md-4"
                                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 250px; height: 250px;margin: 1%; padding: 1%">
                                            <div align="center" style="margin-top: 30px">
                                                <a href="/view_profile?user_id={{$worker->user_id}}"><img src="/images/sample_profile_picture_1.jpg" width="100px"
                                                     height="100px"
                                                     class="img-circle"/>
                                                </a>
                                            </div>
                                            <div align="center" style="font-size: 22px; margin-top: 5px;">
                                                <b><a href="/view_profile?user_id={{$worker->user_id}}" style="color: black">{{$worker->display_name}}</a></b>
                                                <br>
                                                <p style="font-size: 18px">{{$user_subroles[$subrole_idx]}}</p>
                                                @php $subrole_idx++; @endphp
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

<!-- Search worker modal-->
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search Worker to Fill Vacancy</h4>
                </div>
                <form action="worker_search_result" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <b><label>Role</label></b><br>
                            <div class="col-sm-4"><input type="checkbox" value="" onchange="selectAll()"
                                                         id="select_all_checkbox"> Select All
                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"></div>

                            @foreach($subroles as $subrole)
                                <div class="col-sm-4"><input type="checkbox" value={{$subrole->id}} name="subrole_id[]"
                                                             class="subrole_checkbox"
                                                             @foreach($vacant_roles as $vacant_role) @if($vacant_role == $subrole->name) checked @endif @endforeach>
                                    {{$subrole->name}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group" style="margin-top: 80px">
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
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
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