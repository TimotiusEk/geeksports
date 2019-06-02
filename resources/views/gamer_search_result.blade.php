<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");

            var allRadios = document.getElementsByName('gender');
            var booRadio;
            var x = 0;
            for (x = 0; x < allRadios.length; x++) {
                allRadios[x].onclick = function () {
                    if (booRadio == this) {
                        this.checked = false;
                        booRadio = null;
                    } else {
                        booRadio = this;
                    }
                };
            }
        });

        function showModal(user_id) {
            $("#user_id").val(user_id);
        }
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
</head>
<body>

<h1 style="margin-left: 1%; font-size: 30px; margin-top: 1%"><b>Search Result (Gamer)</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 25px">{{$event_information}}</h2>

<h2 style="margin-left: 1%; font-size: 25px">Game <span
            style="margin-left: 29px">:</span> @if(count($game_name) != 0) @for($idx = 0 ; $idx < count($game_name) ; $idx++) {{$game_name[$idx]}} @if($idx != count($game_name) - 1) {{","}}@endif @endfor @else {{"Any"}} @endif
</h2>
<h2 style="margin-left: 1%; font-size: 25px">Gender <span
            style="margin-left: 8px">:</span> {{$gender}}
</h2>

<h2 style="margin-left: 1%; font-size: 25px">City <span
            style="margin-left: 48px">:</span> {{$city->name}}
</h2>
<h2 style="margin-left: 1%; font-size: 25px">Keyword: @if($keyword != ""){{$keyword}} @else{{"-"}}@endif
    <div align="right" style="margin-right: 20px">
        <button type="submit" class="btn btn-primary"
                style="width: 170px; margin-top: -40px" data-toggle="modal"
                data-target="#myModal">
            <b>Change Filter</b>
        </button>
    </div>
</h2>

<hr style="height:0.5px;border:none;color:#333;background-color:#333; width: 99%"/>

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
                                @for($idx = 0 ; $idx < count($game_name) ; $idx++)
                                    <option value="{{$game_id[$idx]}}" selected>{{$game_name[$idx]}}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <b><label>Gender</label></b><br>
                            <input type="radio" class="radio-inline" name="gender" value="m"
                                   @if($gender == "Male") checked @endif> Male
                            <input type="radio" class="radio-inline" name="gender" value="f"
                                   @if($gender == "Female") checked @endif> Female
                        </div>


                        <div class="form-group">
                            <b><label>City</label></b><br>
                            <select class="city form-control" name="city_id" id="mySelect2"
                                    style="width: 100%">
                                @if(!is_null($city))
                                    <option value="{{$city->id}}" name="city_id" selected>{{$city->name}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <b><label>Keyword</label></b><br>
                            <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                                   name="keyword" value="{{$keyword}}">
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Optional Message when Invite MODAL -->
<div class="container">
    <div class="modal fade" id="inviteModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invite Gamer</h4>
                </div>
                <form action="invite_gamer" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 5px">
                            <b><label>Message (Optional)</label></b><br>
                            <textarea class="form-control" rows="5" placeholder="Type here..."
                                      name="message"></textarea>
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>


                        @if(is_array($game_id) && count($game_id) != 0)
                            @foreach($game_id as $id)
                                <input type="hidden" name="game_id[]"
                                       value="{{$id}}"/>
                            @endforeach
                        @endif

                        @if($keyword != "")
                            <input type="hidden" name="keyword" value="{{$keyword}}"/>
                        @endif

                        <input type="hidden" name="user_id" id="user_id"/>

                        <input type="hidden" name="city_id"
                               value="@if(!is_null($city)) {{$city->id}} @else null @endif"/>
                        <input type="hidden" name="gender" value="{{$gender}}"/>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Invite</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--check if all search result has been invited-->
@foreach($status as $s)
    @if($s == null)
        <form action="invite_gamer" method="post">
            {{csrf_field()}}
            <div align="right" style="margin-right: 20px">
                @for($user_idx = 0 ; $user_idx < count($user_id); $user_idx++)
                    @if($status[$user_idx] == null)
                        <input type="hidden" name="user_id[]" value="{{$user_id[$user_idx]}}"/>
                    @endif
                @endfor
                <input type="hidden" name="event_id" value="{{$event_id}}"/>


                @if(is_array($game_id) && count($game_id) != 0)
                    @foreach($game_id as $id)
                        <input type="hidden" name="game_id[]"
                               value="{{$id}}"/>
                    @endforeach
                @endif

                @if($keyword != "")
                    <input type="hidden" name="keyword" value="{{$keyword}}"/>
                @endif

                <input type="hidden" name="city_id" value="@if(!is_null($city)) {{$city->id}} @else null @endif"/>
                <input type="hidden" name="gender" value="{{$gender}}"/>
                <button type="submit" class="btn btn-primary text-right">
                    <b>Invite All</b></button>
            </div>
            <br>
        </form>
        @break
    @endif
@endforeach

<div class="row">
    @for($idx = 0 ; $idx < count($display_names) ; $idx++)
        <div class="col-md-4 text-center">
            <div class="work-inner">
                @if(!is_null($profile_picture[$idx]))
                    <a href="/view_profile?user_id={{$user_id[$idx]}}" class="work-grid"
                       style="background-image: url(/images/profile_picture/{{$profile_picture[$idx]}});"></a>
                @else
                    <a href="/view_profile?user_id={{$user_id[$idx]}}" class="work-grid"
                       style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                @endif

                <div class="desc">
                    <h3>
                        <a href="/view_profile?user_id={{$user_id[$idx]}}">{{$display_names[$idx]}}</a> @if($user_gender[$idx] == "m")
                            <span><img src="images/ic_male.png" width="20px" height="20px" data-toggle="tooltip"
                                       title="Male"/> </span> @elseif($user_gender[$idx] == "f") <span><img
                                        src="images/ic_female.png" width="20px" height="20px" data-toggle="tooltip"
                                        title="Female"/> </span>@endif</h3></h3>
                    <p>@if(!is_null($user_city[$idx])){{$user_city[$idx]." - "}}@endif @if(!is_null($age[$idx])) {{$age[$idx]}}
                        Years Old @endif</p>

                    <div style="margin-top: -20px">({{$user_games[$idx]}})</div>

                    @if(!is_null($user_city[$idx]) || !is_null($age[$idx]))
                        {{--<form method="post" action="invite_gamer" style="margin-top: 30px">--}}
                        <div style="margin-top: 30px">
                            @else
                                <div style="margin-top: 70px">
                                    @endif

                                    {{csrf_field()}}
                                    @if($status[$idx] == "Invite")
                                        <button class="form-control btn btn-dark" disabled><b>Invited</b>
                                        </button>
                                    @elseif($status[$idx] == "Register")
                                        <button class="form-control btn btn-dark" disabled><b>Registered</b>
                                        </button>
                                    @else
                                        <button class="btn btn-primary" style="width: 100%"
                                                onclick="showModal({{$user_id[$idx]}})" data-toggle="modal"
                                                data-target="#inviteModal"><b>Invite to Event</b>
                                        </button>
                                    @endif
                                </div>
                        </div>
                </div>
            </div>
            @endfor
        </div>


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

            $('.city').select2({
                placeholder: 'Type here...',
                allowClear: true,
                ajax: {
                    url: '/search_city',
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
