<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    <script>
        function selectAll() {
            $('.subrole_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
        }

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");

            var allRadios = document.getElementsByName('gender');
            var booRadio;
            var x = 0;
            for(x = 0; x < allRadios.length; x++){
                allRadios[x].onclick = function() {
                    if(booRadio == this){
                        this.checked = false;
                        booRadio = null;
                    } else {
                        booRadio = this;
                    }
                };
            }
        });


    </script>

    <style>
        .nav-pills > li.active > a, .nav-pills > li.active > a:focus {
            color: white;
            background-color: #1FB57B;
        }

        .nav-pills > li.active > a:hover {
            background-color: #1FB57B;
            color: white;
        }
    </style>
</head>
<body style="background-color: whitesmoke">

<div class="container-fluid">
    <div class="row" style="background-color: white; width: 98%; padding: 1%; margin: 1%; border-radius: 10px;">
        <div class="col-md-4 text-center">
            @if(!is_null($event->brochure))
                <img style=" height: 225px; width: 400px; border-radius: 10px;"
                     src="/images/event_brochure/{{$event->brochure}}"/>
            @else
                <img style=" height: 225px; width: 400px; border-radius: 10px;"
                     src="/images/default_event_img.png"/>
            @endif
        </div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <p style="margin-top: 10px">
                    <b>
                        <a href="event_details?event_id={{$event->id}}" style="color: black; font-size: 30px">{{$event->name}}</a>
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
            <li id="participant">
                <form action="manage_participant" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_participant">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_participant').submit()">Participant</a>
            </li>
            <li class="active">
                <form action="manage_vacancy" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_vacancy">
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
                <form action="manage_sponsorship_package" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_sponsorship_package">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_sponsorship_package').submit()">Sponsorship</a>
            </li>
            <li id="event_location"><a href="/manage_streaming_channel?event_id={{$event->id}}">Streaming Channels</a>
            </li>
        </ul>
    </div>

    <div class="row"
         style="background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; padding-top: 1%; margin-left: 1%; margin-right: 1%; display: none"
         id="show_search_worker_btn">
        <div class="col-md-3">
            @if(!is_null($vacancies) && !$vacancies->open) <p style="color: red; font-size: 24px"><b>(Vacancy
                    Closed)</b></p>@endif
        </div>

        <div class="col-md-9" align="right">


            <form action="vacancy_status" method="post" style="float: right">
                {{csrf_field()}}
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px; display: none; width: fit-content; margin-left: 10px"
                        id="show_vacancy_status_btn"><b>Vacancy Status</b></button>
            </form>

            <button type="submit" class="btn btn-primary"
                    style="padding-left: 30px; padding-right: 30px; width: fit-content; float: right;"
                    data-toggle="modal" data-target="#searchWorkerModal"><b>Search Worker</b></button>
        </div>
    </div>
    <div class="row"
         style="background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">
        <h1 align="center" id="no_vacancy" style="margin-top: 5%; margin-bottom: 5%"><b>There is no Roles Vacancy for
                This Event</b></h1>

        <form action="add_vacancy" method="post" align="right">
            {{csrf_field()}}

            <input type="hidden" name="event_id" value="{{$event_id}}"/>
            <a href="#" id="add_vacancy" onclick="$(this).closest('form').submit()"><img
                        src="/images/ic_add_vacancy.png"
                        style="width: 160px;"></a>
        </form>
    </div>
    @if($vacancies != null)

        <div id="datatable" class="row"
             style="display: none; background-color: white; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; padding-bottom: 1%">
            {{--<div class="col-md-12">--}}
            <table id="table_id" class="cell-border table-bordered">
                <thead>
                <tr>
                    <th>Roles</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="center">{{$vacant_roles}}</td>
                    <td>
                        <pre style="font-size: 17px; background-color: white; border-width: 0px; font-family: 'Arial'">{{$vacancies->description}}</pre>
                    </td>
                    <td class="center">
                        <form action="update_vacancy" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="event_id" value="{{$event_id}}"/>
                            <input type="hidden" name="vacant_roles" value="{{$vacant_roles}}"/>
                            <input type="hidden" name="description" value="{{$vacancies->description}}"/>
                            <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                        style="width: 70px; height: 45px; margin-top: 10px"/></a>
                        </form>
                        <br>

                        <form action="/change_vacancy_status" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="event_id" value="{{$event_id}}"/>
                            <input type="hidden" name="vacancy_id" value="{{$vacancies->id}}"/>
                            <a href="#" onclick="$(this).closest('form').submit()">
                                @if(!$vacancies->open)
                                    <input type="hidden" name="action" value="1"/>
                                    <img src="/images/ic_open.png"
                                         style="width: 50px; height: 50px; margin-bottom: 10px;"/>
                                @else
                                    <input type="hidden" name="action" value="0"/>
                                    <img src="/images/ic_close.png"
                                         style="width: 50px; height: 50px; margin-bottom: 10px;"/>
                                @endif
                            </a>

                            <br>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
            {{--</div>--}}
        </div>
    @endif
</div>

<!-- Search worker modal-->
<div class="container">
    <div class="modal fade" id="searchWorkerModal" role="dialog">
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
                                                             @foreach($vacant_roles_arr as $vacant_role) @if($vacant_role == $subrole->name) checked @endif @endforeach>
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
                            <b><label>Gender</label></b><br>
                            <div class="col-sm-4"><input type="radio" name="gender" value="m">Male</div>
                            <div class="col-sm-4"><input type="radio" name="gender" value="f">Female</div>
                        </div><br>

                        <div class="form-group">
                            <b><label>City</label></b><br>
                            <select class="city form-control" name="city_id" id="mySelect2"
                                    style="width: 100%">
                            </select>
                        </div>

                        <div class="form-group">
                            <b><label>Keyword</label></b><br>
                            <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                                   name="keyword">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            "bLengthChange": false,
            "dom": '<"pull-left"f><"pull-right"l>tip',
            "columnDefs": [{
                "targets": '_all',
                "orderable": false
            }],
            "paging": false,
            "searching": false,
            "info": false
        });

        $("th").addClass("dt-head-center");
        $("td.center").addClass("dt-body-center");
    });


    <?php
    if($vacancies != null){
    ?>
    $("#no_vacancy").hide();
    $("#add_vacancy").hide();

    $("#datatable").show();
    $("#show_vacancy_status_btn").show();
    $("#show_search_worker_btn").show();
    <?php
    }
    ?>

    $('.cari').select2({
        placeholder: 'Type here...',
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
