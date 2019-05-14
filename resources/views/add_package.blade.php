<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Add Sponsorship Package</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>

@if(isset($success))
    @if($success == true)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>Package has been added successfully!</b>
        </div>

        <form method="post" action="manage_sponsorship_package" name="myForm" id="myForm">
            {{csrf_field()}}
            <input type="hidden" name="event_id" value="{{$event_id}}"/>

        </form>
    @endif
@endif

<form action="add_package" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Package Name</label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="package_name"/>
    </div>

    <div class="form-group">
        <b><label>Sponsor Rights</label></b>
        <textarea class="form-control" rows="5" id="address" placeholder="Type here..." name="sponsor_rights"></textarea>
    </div>

    <div class="form-group">
        <b><label>Sponsor Obligations</label></b>
        <textarea class="form-control" rows="5" id="address" placeholder="Type here..." name="sponsor_obligations"></textarea>
    </div>

    <input type="hidden" name="event_id" value="{{$event_id}}"/>


    <button type="submit" class="form-control btn btn-primary" style="margin-top: 35px"><b>Add Package</b></button>
</form>

@if(isset($success))
    @if($success == true)
        <script type="text/javascript">
            function submitform() {
                document.forms["myForm"].submit();
            }


            setTimeout(submitform, 2000)
        </script>
    @endif
@endif

</body>
</html>