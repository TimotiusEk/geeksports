<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
</head>
<body>

<h1 style="margin-left: 1%; font-size: 35px"><b>Update Vacancy</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 2%">{{$event_information}}</h2>

@if(isset($success))
    @if($success == true)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>Vacancy has been updated successfully!</b>
        </div>

        <form method="post" action="manage_vacancy" name="myForm" id="myForm">
            {{csrf_field()}}
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
            <input type="hidden" name="event_information" value="{{$event_information}}"/>
        </form>
    @endif
@endif


<form style="margin-left: 1%; font-size: 20px" action="update_vacancy" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Vacant Roles</label></b><br>
        <div class="container-fluid">
            @foreach($subroles as $subrole)
                <div class="col-sm-4"><input type="checkbox"
                                             value={{$subrole->id}} name="subrole_id[]"
                                             @foreach($vacant_roles as $vacant_role) @if($vacant_role == $subrole->name) checked @endif @endforeach> {{$subrole->name}}
                </div>
            @endforeach

        </div>
    </div>

    <div class="form-group">
        <b><label>Description</label></b>
        <textarea class="form-control" rows="5" placeholder="Type here..."
                  name="description">{{$description}}</textarea>
    </div>


    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <input type="hidden" name="event_information" value="{{$event_information}}"/>

    <button type="submit" class="form-control btn btn-primary"><b>Update</b></button>
</form>

@if(isset($success))
    @if($success == true)
        <script type="text/javascript">
            function submitform() {
                document.forms["myForm"].submit();
            }

            setTimeout(submitform, 2000);
        </script>
    @endif
@endif
</body>
</html>
