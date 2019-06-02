<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        $("#fh5co-page").hide();
    </script>
    <script>
        function validateForm() {
            checked = $("input[type=checkbox]:checked").length;

            if (checked == 0) {
                alert('At Least 1 Industry Must be Picked!');
                return false;
            }

            var allowedFiles = [".png", ".jpg", ".jpeg"];
            var form_valid = document.getElementById("fileToUpload");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
            if (!regex.test(form_valid.value.toLowerCase())) {
                alert('only PNG/JPG/JPEG are allowed');
                return false;
            }
        }
    </script>

</head>
<body>
<form action="registration_done" method="post" onsubmit="return validateForm();" enctype="multipart/form-data">
    {{csrf_field()}}

    <div class="text-center"><img src="/images/logo.png" class="registration_logo"></div>
    <div class="text-center"><img src="/images/account_step_signup_2.png" class="steps"></div>

    <div class="form-group">
        <b><label>Company Name</label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="company_name" required/>
    </div>

    <div class="form-group">
        <b><label>Company Logo (Optional)</label></b><br>
        <input type="file" name="company_logo" id="fileToUpload">
    </div>

    <div class="form-group" id="industry">
        <b><label>Industry</label></b><br>

        <div class="container-fluid">
            @foreach($industries as $industry)
                <div class="col-sm-4"><input type="checkbox" value="{{$industry->id}}"
                                             name="industry_id[]"> {{$industry->name}}</div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <b><label>Contact Person</label></b>
        <textarea class="form-control" rows="3" placeholder="Type here..." name="contact_person" required></textarea>
    </div>

    <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary" style="margin-top: 20px"><b>Register</b></button>
    </div>

    <input type="hidden" name="email" value="{{$email}}"/>
    <input type="hidden" name="username" value="{{$username}}"/>
    <input type="hidden" name="password" value="{{$password}}"/>
    <input type="hidden" name="role_id" value="{{$role->id}}"/>
    <input type="hidden" name="role_name" value="{{$role->name}}"/>

</form>
</body>
</html>