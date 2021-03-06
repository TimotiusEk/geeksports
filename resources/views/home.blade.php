<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        document.getElementById("home").className = 'active';

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
</head>
<body style="background-color: whitesmoke;">

<div class="container"
     style="width: 60%; margin-top: 2%; min-width: 900px">
    <div class="row" align="right">
        <button type="submit" class="btn btn-primary"
                style="padding-left: 70px; padding-right: 70px; width: fit-content;"
                id="filter_btn" data-toggle="modal" data-target="#myModal"><b>Filter</b></button>
    </div>
    @if(count($events) != 0)
        @for($idx = 0 ; $idx < count($events) ; $idx++)

            <div class="row" style="background-color: white; margin-top: 2%; border-radius: 10px;">
                <div class="col-md-5 text-center">
                    @if(!is_null(($events[$idx])->brochure))
                    <img style=" height: 225px; width: 400px; margin-top: 23px;"
                         src="/images/event_brochure/{{($events[$idx])->brochure}}"/>
                    @else
                        <img style=" height: 225px; width: 400px; margin-top: 23px;"
                             src="/images/default_event_img.png"/>
                    @endif
                </div>
                <div class="col-md-7" style="font-size: 40px; vertical-align: top;">
                    <div style="margin-left: 70px">
                        <p style="margin-top: 20px; margin-left: -25px;"><b><a
                                        href="/event_details?event_id={{($events[$idx])->id}}"
                                        style="color: black; font-size: 35px">{{($events[$idx])->name}}</a></b>
                        </p>
                        <p style="font-size: 18px; margin-top: -30px; margin-left: -23px;">By: <a
                                    href="/view_profile?user_id={{($events[$idx]->event_organizer)->user_id}}">{{($events[$idx]->event_organizer)->display_name}}</a>
                        </p>
                        <hr>
                        <div style="font-size: 23px; margin-top: -10px" class="row">
                            <div class="col-md-9">
                                <img src="images/ic_location.png"
                                     style="width: 30px; margin-right: 10px; margin-bottom: 10px;"> @if($city_name[$idx] != null){{$city_name[$idx]}} @else
                                    - @endif
                            </div>
                        </div>
                        <div style="font-size: 23px; margin-bottom: 10px" class="row">
                            <div class="col-md-9">
                                <img src="images/ic_datetime.png"
                                     style="width: 30px; margin-right: 10px; margin-top: -5px">@if($events[$idx]->start_date != null) {{($events[$idx])->start_date}}
                                - {{($events[$idx])->end_date}} @else - @endif
                            </div>
                        </div>
                        <div style="font-size: 23px;" class="row">

                            <div class="col-sm-8">
                                <img src="images/ic_game.png"
                                     style="width: 30px; margin-top: -5px">
                                @if(($games[$idx]) != null){{$games[$idx]}}@else - @endif
                            </div>

                            <div class="col-sm-4" align="right">
                                <form action="event_details" method="GET">
                                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                    <a href="#" onclick="$(this).closest('form').submit()"><img
                                                src="/images/details_btn.png" width="130px"/></a>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @endfor
    @endif
</div>
<!-- MODAL -->
<div class="Event Filter">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-4">
                            <h4>Filter Event</h4>
                        </div>

                        <div class="col-sm-8" align="right">
                            <button type="button" class="close" data-dismiss="modal" style="width: fit-content">
                                &times;
                            </button>
                        </div>
                    </div>


                </div>
                <div class="modal-body">
                    <form action="home" style="padding: 0; margin: 0; width: 100%">

                        <div class="form-group">
                            <b style="font-size: 18px">Games</b>
                            <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                                    style="width: 100%">
                                @if($filter != "true")
                                    @foreach($user_games as $user_game)
                                        <option value="{{$user_game->game_id}}" selected>{{$user_game->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <b style="font-size: 18px">City</b>
                            <select class="city form-control" name="city_id[]" id="mySelect2"
                                    style="width: 100%" multiple="multiple"></select>
                        </div>

                        <input type="text" class="form-control" placeholder="Event keyword (Optional)"
                               name="keyword" style="margin-top: 20px; height: 40px; margin-bottom: 30px">
                        {{--<div class="modal-footer">--}}
                        <button type="submit" class="btn btn-primary" name="filter" value="true">Filter</button>
                        {{--</div>--}}

                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
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