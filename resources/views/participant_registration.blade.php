<html lang="en">
<head>
    <title>Event Registration</title>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    {{--<script>--}}
        {{--function validateForm() {--}}
            {{--var x = document.forms["registration_form"]["game"].value;--}}
            {{--if (x == -1) {--}}
                {{--alert("Game has not selected")--}}

                {{--return false;--}}
            {{--}--}}
        {{--}--}}
    {{--</script>--}}
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Participant Registration</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>

@if(isset($success) && $success == true)
    {{--<meta http-equiv="refresh" content="2; url=manage_event"/>--}}
    <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
        <b>Registration Successful!</b>
    </div> @endif

<form name="registration_form" action="participant_registration_form" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Team Name</label></b><br>
        <input type="text" name="team_name" class="form-control" placeholder="Type here..."/>
    </div>

    <div class="form-group">
        <b><label>Upload Team Logo (Optional)</label></b><br>
        <input type="file" name="team_logo" id="fileToUpload">
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_brochure_btn"
                onclick="removeBrochure()"><b>Cancel</b></button>
    </div>

    <div class="form-group">
        <b><label>Members</label></b><br>
        <select class="cari form-control" name="gamer_id[]" multiple="multiple" id="mySelect2">
            <option value="{{$logged_in_gamer->id}}" selected>{{$logged_in_gamer->display_name}}</option>
        </select>
    </div>

    <div class="form-group">
        <b><label>Games</label></b><br>


        @for($idx = 0 ; $idx < count($event_games) ; $idx++)
            <input type="radio" name="game_id" value="{{($event_games[$idx])->game_id}}" @if(count($event_games) == 1) checked @endif> {{$game_names[$idx]}}<br>
        @endfor
    </div>


    <input type="hidden" name="event_id" value="{{$event_id}}"/>


    <div class="form-group">
        <button type="submit" class="form-control btn btn-primary"><b>Register</b></button>
    </div>
</form>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.cari').select2({
        placeholder: 'Type Display Name',
        minimumInputLength: 3,
        ajax: {
            url: '/search_player',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.display_name,
                            id: item.user_id
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