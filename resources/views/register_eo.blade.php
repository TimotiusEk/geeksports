<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
</head>
<body>

<form action="registration_done" method="post">
    {{csrf_field()}}

    <div class="text-center"><img src="/images/logo.png" class="registration_logo"></div>
    <div class="text-center"><img src="/images/account_step_signup_2.png" class="steps"></div>
    <div class="form-group">
        <b><label>Display Name</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" class="form-control" placeholder="Type here... " name="display_name" required/>
        </div>
    </div>

    <div class="form-group">
        <b><label>Contact Person</label></b>
        <textarea class="form-control" rows="3" placeholder="Type here..." name="contact_person" required></textarea>
    </div>



    <div class="form-group">
        <b><label>Company Name (Optional)</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-briefcase"></i>
            <input type="text" class="form-control" placeholder="Type here..." name="company_name"/>
        </div>
    </div>

    <input type="hidden" name="email" value="{{$email}}"/>
    <input type="hidden" name="username" value="{{$username}}"/>
    <input type="hidden" name="password" value="{{$password}}"/>
    <input type="hidden" name="role_id" value="{{$role->id}}"/>
    <input type="hidden" name="role_name" value="{{$role->name}}"/>

    <div class="form-group has-feedback">
        <button type="submit" class="form-control btn btn-primary"><b>Next Step <span
                        class="glyphicon glyphicon-btn glyphicon-arrow-right form-control-feedback"></span></b></button>
    </div>
</form>
</body>
</html>


