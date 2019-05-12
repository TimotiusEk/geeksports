<!DOCTYPE html>
<html>
<head>
    <title>Update Event</title>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    <script>
        $(function () {
            $("#others").on('change', function (e) {
                if ($(this).is(':checked')) {
                    $("#others-textbox").show();
                } else {
                    $("#others-textbox").hide();
                }
            });
        });
        $("#dashboard_nav_bar").show();
        document.getElementById("manage_event").className = 'active';
        document.getElementById("dashboard").className = 'active';

        function validateForm() {
            var allowedFiles = [".png", ".jpg", ".jpeg"];
            var form_valid = document.getElementById("fileToUpload");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");

            if (form_valid.files.length != 0) {
                if (!regex.test(form_valid.value.toLowerCase())) {
                    alert('Only PNG/JPG/JPEG are allowed!');
                    return false;
                }
            }
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('input[type="file"]').change(function () {
                $("#remove_brochure_btn").show()
            });
        });
    </script>

    <script>
        function removeBrochure() {
            $('#fileToUpload').val("");
            $("#remove_brochure_btn").hide()
        }
    </script>
</head>
<body>

@if(isset($success) && $success == true)
    <meta http-equiv="refresh" content="2; url=manage_event"/>
    <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
        <b>Event has been updated successfully!</b>
    </div> @endif


<form action="update_event_form" onsubmit="return validateForm()" enctype="multipart/form-data" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Event Name <span style="color: red">(Required)</span></label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="event_name" value="{{$event->name}}"/>
    </div>

    <div class="form-group">
        <b><label>City</label></b><br>
        <select class="city form-control" name="city_id" id="mySelect2"
                style="width: 100%">

            @if($city_name != null)
                <option value={{$event->city_id}} selected>{{$city_name}}</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <b><label>Start Date</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-calendar"></i>
            <input type="date" class="form-control" name="start_date" value="{{$event->start_date}}"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>End Date</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-calendar"></i>
            <input type="date" class="form-control" name="end_date" value="{{$event->end_date}}"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Games</label></b><br>
        <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                style="width: 100%">
            @for($idx = 0 ; $idx < count($game_id) ; $idx++)
                <option value={{$game_id[$idx]}} selected>{{$game_name[$idx]}}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <b><label>Event Details</label></b>
        <textarea class="form-control" rows="5" placeholder="Type here..." name="details">{{$event->details}}</textarea>
    </div>

    <div class="form-group">
        <b><label>Open Participant Registration:</label></b> <input type="checkbox" name="participant_registration" value="true" style="margin-left: 10px" @if($event->participant_registration == 1) checked @endif/>
    </div>

    <div class="form-group">
        @if($event->brochure != null)
            <b><label>Current Brochure</label></b><br>
            <img src="images/event_brochure/{{$event->brochure}}" style="margin-bottom: 20px" width="60%"
                 name="brochure"/>
        @endif
        <br><b><label>Update Brochure</label></b><br>
        <input type="file" name="brochure" id="fileToUpload">
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_brochure_btn"
                onclick="removeBrochure()"><b>Remove Brochure</b></button>
    </div>

    <input type="hidden" name="event_id" value="{{$event->id}}"/>

    <button class="form-control btn btn-primary"><b>Update</b></button>
</form>


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