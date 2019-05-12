<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>

    <script>

    </script>
</head>
<body>
<form action="login" method="post">
    {{ csrf_field() }}
    <div class="text-center"><img src="/images/logo.png" class="logo"></div>
    <div class="form-group">
        <b><label for="exampleInputEmail1">Username</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-user"></i>
            <input type="text" class="form-control" placeholder="Type here..." name="username" required/>
        </div>
    </div>
    <div class="form-group">
        <b><label for="exampleInputPassword1">Password</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-lock"></i>
            <input type="password" class="form-control" placeholder="Type here..." name="password" required/>
        </div>
    </div>
    <div class="form-group">
       <div class="text-right">Don't have account? <a href="/registration">Register here!</a></div>
    </div>
    <button type="submit" class="btn btn-primary"><b>LOGIN</b></button>
</form>

</body>
</html>