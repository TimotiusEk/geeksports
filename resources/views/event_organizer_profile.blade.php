<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <script>
        document.getElementById("profile").className = 'active';

        $(document).ready(function () {
            $('input[type="file"]').change(function () {
                $("#remove_profile_picture_btn").show()
            });
        });

        function removeProfilePicture() {
            $('#fileToUpload').val("");
            $("#remove_profile_picture_btn").hide()
        }
    </script>
</head>
<body>
<form action="update_profile" method="post" enctype="multipart/form-data"
      style="margin-top: 2%;">
    {{csrf_field()}}

    <div class="form-group">
        <b><label>Display Name</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" class="form-control" placeholder="Type here... " name="display_name" required
                   value="{{$event_organizer->display_name}}"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Contact Person</label></b>
        <textarea class="form-control" rows="3" placeholder="Type here..." name="contact_person"
                  required>{{$event_organizer->contact_person}}</textarea>
    </div>

    <div class="form-group">
        <b><label>Company Name (Optional)</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-briefcase"></i>
            <input type="text" class="form-control" placeholder="Type here..." name="company_name"
                   value="{{$event_organizer->company_name}}"/>
        </div>
    </div>

    <div class="form-group">
        @if(!is_null($event_organizer->profile_picture))
            <b><label>Current Profile Picture</label></b><br>
            <img src="images/profile_picture/{{$event_organizer->profile_picture}}" style="margin-bottom: 20px"
                 width="200px" height="200px"/>
        @endif
        <br><b><label>Add New Profile Picture</label></b><br>
        <input type="file" name="profile_picture" id="fileToUpload">
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_profile_picture_btn"
                onclick="removeProfilePicture()"><b>Cancel</b></button>
    </div>

    <input type="hidden" name="event_organizer_id" value="{{$event_organizer->id}}"/>

    <div class="form-group has-feedback" style="margin-top: 50px">
        <button type="submit" class="form-control btn btn-primary"><b>Save</b></button>
    </div>
</form>
</body>
</html>