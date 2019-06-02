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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
</head>
<body>
<form action="update_profile" method="post" onsubmit="return validateForm();" id="registration_form"
      style="margin-top: 1%" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Display Name</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" class="form-control" placeholder="Type here..." name="display_name" required
                   value="{{$individual->display_name}}"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Gender</label></b><br>
        <div class="container-fluid">
            <div class="col-sm-4"><input type="radio" name="gender" value="m"
                                         @if($individual->gender == "m") checked @endif>Male
            </div>
            <div class="col-sm-4"><input type="radio" name="gender" value="f"
                                         @if($individual->gender == "f") checked @endif>Female
            </div>
        </div>
    </div>

    <div class="form-group">
        <b><label>Date of Birth</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-calendar"></i>
            <input type="date" class="form-control" name="dob" value="{{$individual->dob}}"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>City</label></b><br>
        <select class="city form-control" name="city_id" id="mySelect2"
                style="width: 100%">
            @if(!is_null($individual->city_id))
                <option value="{{$individual->city_id}}" selected>{{$individual->city}}</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <b><label>Who are you?</label></b><br>
        <div class="container-fluid">
            @foreach($all_subroles as $subrole)
                <div class="col-sm-4"><input type="checkbox" value="{{$subrole->id}}" name="subrole_id[]"
                                             class="subrole"
                                             @foreach($individual->user_subrole as $user_subrole) @if($subrole->id == $user_subrole->subrole_id) checked @endif @endforeach> {{$subrole->name}}
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <b><label>Games</label></b><br>
        <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                style="width: 100%" required>
            @for($idx = 0 ; $idx < count($individual->user_game) ; $idx++)
                <option value="{{(($individual->user_game)[$idx])->game_id}}"
                        selected>{{(($individual->game)[$idx])->name}}</option>
            @endfor

        </select>
    </div>

    <div class="form-group">
        @if(!is_null($individual->profile_picture))
            <b><label>Current Profile Picture</label></b><br>
            <img src="images/profile_picture/{{$individual->profile_picture}}" style="margin-bottom: 20px"
                 width="200px" height="200px"/>
        @endif
        <br><b><label>Add New Profile Picture</label></b><br>
        <input type="file" name="profile_picture" id="fileToUpload">
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_profile_picture_btn"
                onclick="removeProfilePicture()"><b>Cancel</b></button>
    </div>

    <div class="form-group">
        <b><label>Description (Optional)</label></b>
        <textarea class="form-control" rows="3"
                  placeholder=" Steam URL / Discord Username / In-Game Username / Describe Yourself"
                  name="description">{{$individual->description}}</textarea>
    </div>

    <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary"><b>Save</b></button>
    </div>
</form>

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