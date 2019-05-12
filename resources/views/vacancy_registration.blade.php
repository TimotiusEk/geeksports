<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        document.getElementById("vacancy").className = 'active';
    </script>
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Register to Event</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>

@if(isset($success))
@if($success == true)
<div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
<b>Registration Successful!</b>
</div>

<form method="get" action="vacancy" name="myForm" id="myForm"></form>
@endif
@endif

<form action="vacancy_registration" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Roles</label></b>

        @foreach($subroles as $subrole)

            <div class="checkbox">
                <label style="margin-bottom: 5px"><input type="checkbox" value="{{$subrole->id}}"
                                                         name="subrole_id[]" id="{{$subrole->id}}"
                                                         onclick="showOrHideGame({{$subrole->id}})">{{$subrole->name}}
                </label><br>
            </div>
            {{--@if(count($games) > 1)--}}
                {{--<div class="form-group" id="game_{{$subrole->id}}" style="display: none">--}}
                    {{--<b><label>Games</label></b>--}}
                    {{--@foreach($games as $game)--}}
                        {{--<div class="checkbox">--}}
                            {{--<label style="margin-bottom: 5px"><input type="checkbox" value="{{$game->id}}"--}}
                                                                     {{--name="game_id_{{$subrole->id}}[]"--}}
                                                                     {{--class="game_id_{{$subrole->id}}">{{$game->name}}--}}
                            {{--</label><br>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                    {{--<hr>--}}
                {{--</div>--}}
            {{--@endif--}}
        @endforeach
    </div>

    {{--@if(count($games) == 1)--}}
        {{--<div class="form-group">--}}
            {{--<b><label>Games</label></b>--}}
            {{--<div class="checkbox">--}}
                {{--<label style="margin-bottom: 5px"><input type="checkbox" value="{{($games[0])->id}}"--}}
                                                         {{--name="game_id[]" checked disabled>{{($games[0])->name}}--}}
                {{--</label><br>--}}
            {{--</div>--}}
            {{--<hr>--}}
        {{--</div>--}}
    {{--@endif--}}

    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <input type="hidden" name="event_information" value="{{$event_information}}"/>

    <button type="submit" class="form-control btn btn-primary" style="margin-top: 35px"><b>Register</b></button>
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

<script>
    function showOrHideGame(id) {
        if (document.getElementById(id).checked) {
            $("#game_" + id).show();
        } else {
            $("#game_" + id).hide();
            $('.game_id_' + id).prop('checked', false);
        }
    }
</script>

</body>
</html>