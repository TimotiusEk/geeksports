<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
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

        function selectAll() {
            $('.subrole_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
        }

        function showModal(user_id) {
            $("#user_id").val(user_id);
        }
    </script>
</head>
<body>

<h1 style="margin-left: 1%; font-size: 30px; margin-top: 1%"><b>Search Result (Worker)</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 25px">{{$event_information}}</h2>
<h2 style="margin-left: 1%; font-size: 25px">Role <span
            style="margin-left: 43px">:</span> @if(count($subrole_name) != 0) @for($idx = 0 ; $idx < count($subrole_name) ; $idx++) {{$subrole_name[$idx]}} @if($idx != count($subrole_name) - 1) {{","}}@endif @endfor @else {{"-"}} @endif
</h2>

<h2 style="margin-left: 1%; font-size: 25px">Game <span
            style="margin-left: 28px">:</span> @if(count($game_name) != 0) @for($idx = 0 ; $idx < count($game_name) ; $idx++) {{$game_name[$idx]}} @if($idx != count($game_name) - 1) {{","}}@endif @endfor @else {{"-"}} @endif
</h2>

<h2 style="margin-left: 1%; font-size: 25px">Gender <span
            style="margin-left: 8px">:</span> {{$gender}}
</h2>

<h2 style="margin-left: 1%; font-size: 25px">City <span
            style="margin-left: 48px">:</span> {{$city->name}}
</h2>

<h2 style="margin-left: 1%; font-size: 25px">
    Keyword: @if($keyword != ""){{$keyword}} @else{{"-"}}@endif
    <div align="right" style="margin-right: 20px; margin-top: -40px">
        <button type="submit" class="btn btn-primary"
                style="padding-left: 30px; padding-right: 30px; width: fit-content"
                id="show_sponsor_status_btn" data-toggle="modal" data-target="#myModal">
            <b>Change Filter</b>
        </button>
    </div>
</h2>

<!-- Filter modal-->
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

                            @foreach($all_subroles as $subrole)
                                <div class="col-sm-4"><input type="checkbox" value={{$subrole->id}} name="subrole_id[]"
                                                             class="subrole_checkbox"
                                                             @foreach($subrole_name as $name) @if($subrole->name == $name) checked @endif @endforeach> {{$subrole->name}}
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group" style="margin-top: 80px">
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
                            <div class="col-sm-4"><input type="radio" name="gender" value="m"
                                                         @if($gender == "Male") checked @endif>Male
                            </div>
                            <div class="col-sm-4"><input type="radio" name="gender" value="f"
                                                         @if($gender == "Female") checked @endif>Female
                            </div>
                        </div>
                        <br>

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
                            <button type="submit" class="btn btn-default">Submit</button>
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
                    <h4 class="modal-title">Invite Worker</h4>
                </div>
                <form action="invite_worker" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 5px">
                            <b><label>Message (Optional)</label></b><br>
                            <textarea class="form-control" rows="5" placeholder="Type here..."
                                      name="message"></textarea>
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>


                        @if(is_array($subrole_id) && count($subrole_id) != 0)
                            @foreach($subrole_id as $id)
                                <input type="hidden" name="subrole_id[]"
                                       value="{{$id}}"/>
                            @endforeach
                        @endif

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
<hr style="height:0.5px;border:none;color:#333;background-color:#333; width: 99%"/>

<!--check if all search result has been invited-->
@foreach($status as $s)
    @if($s == null)
        <form action="invite_worker" method="post">
            {{csrf_field()}}
            <div align="right" style="margin-right: 20px">
                @for($user_idx = 0 ; $user_idx < count($user_id); $user_idx++)
                    @if($status[$user_idx] == null)
                        <input type="hidden" name="user_id[]" value="{{$user_id[$user_idx]}}"/>
                    @endif
                @endfor
                <input type="hidden" name="event_id" value="{{$event_id}}"/>


                @if(is_array($subrole_id) && count($subrole_id) != 0)
                    @foreach($subrole_id as $id)
                        <input type="hidden" name="subrole_id[]"
                               value="{{$id}}"/>
                    @endforeach
                @endif

                @if(is_array($game_id) && count($game_id) != 0)
                    @foreach($game_id as $id)
                        <input type="hidden" name="game_id[]"
                               value="{{$id}}"/>
                    @endforeach
                @endif

                @if($keyword != "")
                    <input type="hidden" name="keyword" value="{{$keyword}}"/>
                @endif
                <button type="submit" class="btn btn-primary text-right" style="padding-left: 1%; padding-right: 1%;">
                    <b>Invite All</b></button>
            </div>
            <br>
        </form>
        @break
    @endif
@endforeach

<div class="row" style="padding: 1%">
    @for($idx = 0 ; $idx < count($display_names) ; $idx++)

        <div class="col-md-4 text-center">
            <div class="work-inner">
                @if(!is_null($profile_picture[$idx]))
                    <a href="/view_profile?user_id={{$user_id[$idx]}}" class="work-grid"
                       style="background-image: url(/images/profile_picture/{{$profile_picture[$idx]}});">
                    </a>
                @else
                    <a href="/view_profile?user_id={{$user_id[$idx]}}" class="work-grid"
                       style="background-image: url(/images/default_event_img.png);">
                    </a>
                @endif
                <div class="desc">
                    <h3>
                        <a href="/view_profile?user_id={{$user_id[$idx]}}">{{$display_names[$idx]}}</a> @if($user_gender[$idx] == "m")
                            <span><img src="images/ic_male.png" width="20px" height="20px" data-toggle="tooltip"
                                       title="Male"/> </span> @elseif($user_gender[$idx] == "f") <span><img
                                        src="images/ic_female.png" width="20px" height="20px" data-toggle="tooltip"
                                        title="Female"/> </span>@endif</h3>
                    <p>@if(!is_null($user_city[$idx])){{$user_city[$idx]." - "}}@endif @if(!is_null($age[$idx])) {{$age[$idx]}}
                        Years Old @endif </p>
                    <div style="margin-top: -20px">{{$subroles[$idx]}}</div>
                    <div style="margin-bottom: 30px">({{$user_games[$idx]}})</div>
                    @if($status[$idx] == "Invite")
                        <button class="form-control btn btn-dark" disabled><b>Invited</b>
                        </button>
                    @elseif($status[$idx] == "Register")
                        <button class="form-control btn btn-dark" disabled><b>Registered</b>
                        </button>
                    @else
                        <button class="btn btn-primary" style="width: 100%" onclick="showModal({{$user_id[$idx]}})"
                                data-toggle="modal" data-target="#inviteModal"><b>Invite to Event</b>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endfor
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.cari').select2({
        placeholder: 'Type Here...',
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
