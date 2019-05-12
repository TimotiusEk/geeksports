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
<body style="background-color: whitesmoke">


<button type="submit" class="form-control btn btn-primary"
        style="position: relative; left: 88%; padding-left: 70px; padding-right: 70px; width: fit-content;"
        id="filter_btn" data-toggle="modal" data-target="#myModal"><b>Filter</b></button>
@if(count($events) != 0)
    @for($idx = 0 ; $idx < count($events) ; $idx++)
        <div class="container"
             style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img style=" height: 225px; width: 400px; border-radius: 10px; margin-top: 15px;"
                            src="/images/event_brochure/{{($events[$idx])->brochure}}"/>
                </div>
                <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
                    <div style="margin-left: 70px">
                        <p style="margin-top: 20px; margin-left: -30px;"><b><a
                                        href="/event_details?event_id={{($events[$idx])->id}}" style="color: black">{{($events[$idx])->name}}</a></b>
                        </p>
                        <p style="font-size: 18px; margin-top: -10px; margin-left: -23px;">By: <a href="/view_profile?user_id={{($events[$idx]->event_organizer)->user_idnote}}">{{($events[$idx]->event_organizer)->display_name}}</a></p>
                        <hr>
                        <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                             style="width: 30px; margin-right: 10px; margin-bottom: 10px;"> @if($city_name[$idx] != null){{$city_name[$idx]}} @else
                                - @endif
                        </div>
                        <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                               style="width: 30px; margin-right: 10px; margin-top: -5px">@if($events[$idx]->start_date != null) {{($events[$idx])->start_date}}
                            - {{($events[$idx])->end_date}} @else - @endif
                        </div>
                        <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                            style="width: 30px; margin-right: 10px; margin-top: -5px">
                            @if(($games[$idx]) != null){{$games[$idx]}} @else - @endif
                            <form action="event_details" method="GET">
                                <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                <a href="#" onclick="$(this).closest('form').submit()"><img
                                            src="/images/details_btn.png" width="125px" align="right"
                                            style="margin-bottom: 10px"/></a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endfor
@endif

<!-- MODAL -->
<div class="Event Filter">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Filter Event</h4>
                </div>
                <form action="home">
                    <div class="modal-body">
                        <div class="form-group">
                            <p style="font-size: 18px"><b>Games</b></p>
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
                            <p style="font-size: 18px"><b>City</b></p>
                            <select class="city form-control" name="city_id[]" id="mySelect2"
                                    style="width: 100%" multiple="multiple"></select>
                        </div>

                        <input type="text" class="form-control" placeholder="Event keyword (Optional)"
                               name="keyword" style="margin-top: 40px">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" name="filter" value="true">Filter</button>
                        </div>
                </form>
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