<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <script>
        document.getElementById("profile").className = 'active';

        $(document).ready(function () {
            $('input[type="file"]').change(function () {
                $("#remove_company_logo_btn").show()
            });
        });

        function removeCompanyLogo() {
            $('#fileToUpload').val("");
            $("#remove_company_logo_btn").hide()
        }
    </script>
</head>
<body>
<form action="update_profile" method="post" onsubmit="return validateForm();" enctype="multipart/form-data">
    {{csrf_field()}}

    <div class="form-group">
        <b><label>Company Name</label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="company_name" required value="{{$company->company_name}}"/>
    </div>

    <div class="form-group">
        @if(!is_null($company->company_logo))
            <b><label>Current Company Logo</label></b><br>
            <img src="images/company_logo/{{$company->company_logo}}" style="margin-bottom: 20px"
                 width="200px" height="200px"/>
        @endif
        <br><b><label>Add New Company Logo (Optional)</label></b><br>
        <input type="file" name="company_logo" id="fileToUpload">
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_company_logo_btn"
                onclick="removeCompanyLogo()"><b>Cancel</b></button>
    </div>


    <div class="form-group" id="industry">
        <b><label>Industry</label></b><br>

        <div class="container-fluid">
            @foreach($all_industries as $industry)
                <div class="col-sm-4"><input type="checkbox" value="{{$industry->id}}" name="industry_id[]" @foreach($company_industries as $company_industry) @if($company_industry->industry_id == $industry->id) checked @endif @endforeach > {{$industry->name}}</div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <b><label>Contact Person</label></b>
        <textarea class="form-control" rows="3" placeholder="Type here..." name="contact_person" required>{{$company->contact_person}}</textarea>
    </div>

    <input type="hidden" name="company_id" value="{{$company->id}}"/>

    <div class="form-group has-feedback">
        <button type="submit" class="btn btn-primary" style="margin-top: 30px"><b>Save</b></button>
    </div>


</form>
</body>
</html>