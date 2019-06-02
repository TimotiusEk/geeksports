<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
    <script>
        function validateForm() {
            subroleChecked = $(".subrole:checkbox:checked").length;

            if (subroleChecked == 0) {
                alert("Who are you?");
                return false;
            }
        }
    </script>

    @include('nav_header')

    <script>
        $("#fh5co-page").hide();
    </script>
</head>

<body>
<form action="registration_done" method="post" onsubmit="return validateForm();" id="registration_form">
    {{csrf_field()}}

    <div class="text-center"><img src="/images/logo.png" class="registration_logo"></div>
    <div class="text-center"><img src="/images/account_step_signup_2.png" class="steps"></div>

    <div class="form-group">
        <b><label>Display Name</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" class="form-control" placeholder="Type here..." name="display_name" required/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Gender</label></b><br>
        <div class="container-fluid">
            <div class="col-sm-4"><input type="radio" name="gender" value="m">Male</div>
            <div class="col-sm-4"><input type="radio" name="gender" value="f">Female</div>
        </div>
    </div>

    <div class="form-group">
        <b><label>Date of Birth</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-calendar"></i>
            <input type="date" class="form-control" name="dob"/>
        </div>
    </div>

    <div class="form-group">
        <b><label>City</label></b><br>
        <select class="city form-control" name="city_id" id="mySelect2"
                style="width: 100%">
        </select>
    </div>

    <div class="form-group">
        <b><label>Who are you?</label></b><br>
        <div class="container-fluid">
            @foreach($subroles as $subrole)
                <div class="col-sm-4"><input type="checkbox" value="{{$subrole->id}}" name="subrole_id[]"
                                             class="subrole"> {{$subrole->name}}</div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <b><label>Games</label></b><br>
        <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                style="width: 100%" required></select>
    </div>


    <div class="form-group">
        <b><label>Description (Optional)</label></b>
        <textarea class="form-control" rows="3"
                  placeholder=" Steam URL / Discord Username / In-Game Username / Describe Yourself"
                  name="description"></textarea>
    </div>

    <input type="hidden" name="email" value="{{$email}}"/>
    <input type="hidden" name="username" value="{{$username}}"/>
    <input type="hidden" name="password" value="{{$password}}"/>
    <input type="hidden" name="role_id" value="{{$role->id}}"/>
    <input type="hidden" name="role_name" value="{{$role->name}}"/>

    <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary" style="margin-top: 20px"><b>Register</b></button>
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