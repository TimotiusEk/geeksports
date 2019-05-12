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
    </script>
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Update Winner</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>

@if(!is_null($success))
    @if($success == 1)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>Winner has been updated successfully!</b>
        </div>

        <form method="get" action="manage_winner" name="myForm" id="myForm">
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
        </form>
    @endif
@endif

<form action="update_winner" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Game</label></b>
        <select class="cari form-control" name="game_id" id="game_select2"
                style="width: 100%" required>
            <option value="{{$winner->game_id}}" selected>{{$winner->game_name}}</option>
        </select>
    </div>

    <div class="form-group">
        <b><label>Type</label></b>
        <div class="container">
            <div class="row">

            </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="radio" name="win_type" value="First Place" @if($winner->win_type == 'First Place') checked @endif/> First Place
                </div>
                <div class="col-md-3">
                    <input type="radio" name="win_type" value="Second Place" @if($winner->win_type == 'Second Place') checked @endif/> Second Place
                </div>
                <div class="col-md-3">
                    <input type="radio" name="win_type" value="Third Place" @if($winner->win_type == 'Third Place') checked @endif/> Third Place
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <input type="radio" name="win_type" value="Other" @if($winner->win_type != 'First Place' && $winner->win_type != 'Second Place' && $winner->win_type != 'Third Place') checked @endif/> Other
                </div>
            </div>
        </div>
        <input type="text" class="form-control" name="other_win_type" placeholder="Please specify . . ." value="@if($winner->win_type != 'First Place' && $winner->win_type != 'Second Place' && $winner->win_type != 'Third Place') {{$winner->win_type}} @endif"/>

    </div>

    <div class="form-group">
        <b><label>Team</label></b>
        <select class="team form-control" name="team_id" id="mySelect2"
                style="width: 100%" required>
            <option value="{{$winner->team_id}}" selected>{{$winner->team_name}}</option>
        </select>
    </div>

    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <input type="hidden" name="winner_id" value="{{$winner->id}}"/>

    <button type="submit" class="form-control btn btn-primary" style="margin-top: 35px"><b>Update Winner</b></button>
</form>

@if(isset($success))
    @if($success == true)
        <script type="text/javascript">
            function submitform() {
                document.forms["myForm"].submit();
            }


            setTimeout(submitform, 2000)
        </script>
    @endif
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.cari').select2({
        placeholder: 'Type its Name',
        ajax: {
            url: '/search_game?event_id={{$event_id}}',
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

    $('.team').select2(
        {
            ajax: {

                url: '/search_team?event_id={{$event_id}}&game_id='+{{$winner->game_id}},
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

    $('.cari').on('select2:select', function (e) {
            $('.team').select2(
                {
                    ajax: {

                        url: '/search_team?event_id={{$event_id}}&game_id='+$("#game_select2").val(),
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

            console.log('/search_team?event_id={{$event_id}}&game_id='+$("#game_select2").val());
        }

    );

</script>
</body>
</html>