<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmationPassword = document.getElementById("confirmation_password").value;

            if (password != confirmationPassword) {
                alert("Confirmation Password isn't Match!");
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
<form id="registration_form" method="post" action="registration_2" onsubmit="return validateForm();">
    {{csrf_field()}}

    <div class="text-center"><img src="/images/logo.png" class="registration_logo"></div>
    <div class="text-center"><img src="/images/account_step_signup_1.png" class="steps"></div>

    <div class="form-group">
        <b><label>Email</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="Type here..." required/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Username</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" name="username" class="form-control" placeholder="Type here..." required/>
        </div>
    </div>

    <div class="form-group">
        <b><label for="exampleInputPassword1">Password</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Type here..." required id="password"/>
        </div>
    </div>

    <div class="form-group">
        <b><label for="exampleInputPassword1">Confirmation Password</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-lock"></i>
            <input type="password" class="form-control" placeholder="Type here..." id="confirmation_password"/>
        </div>
    </div>
    <div class="form-group">
        <b><label for="exampleInputPassword1">Role in E-Sport</label></b>

        @foreach($roles as $role)
            <div class="radio">
                <label><input type="radio" name="role_id" value="{{$role->id}}" required>{{$role->name}}</label>
            </div>
        @endforeach
    </div>


    <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary"><b>Next Step <span
                        class="glyphicon glyphicon-btn glyphicon-arrow-right form-control-feedback"></span></b></button>
    </div>
</form>


</body>
</html>


