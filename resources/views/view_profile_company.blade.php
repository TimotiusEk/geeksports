<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        document.getElementById("profile").className = 'active';
    </script>
</head>
<body style="background-color: whitesmoke">
<div class="container" style="width: 98%; padding: 1%; margin: 1%">
    <div class="row" align="center" style="background-color: white; border-radius: 10px;">
        <img src="/images/company_logo/{{$user->company_logo}}" width="150px" height="150px"
             style="margin-top: 20px"/>
        <p style="font-size: 50px">{{$user->company_name}} @if($edit == true)<a
                    href="update_profile?user_id={{$user->user_id}}"><img src="/images/ic_edit_no_text.png" width="25px"/></a>@endif</p>
        <p style="font-size: 25px; margin-top: -15px; margin-bottom: 45px">{{$user->industry}}</p>
        <div style="background-color: #f4f4f4; font-size: 23px; padding: 2%; margin: 1%">
            <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial';"
                 align="left">{{$user->contact_person}}</pre>
        </div>
    </div>


    <div class="row" style="background-color: white; border-radius: 10px; margin-top: 30px; padding: 1%">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Sponsored Event</b></p>
        <hr>
        @if(!is_null($user->events))
            @foreach($user->events as $user_event)
                <a href="event_details?event_id={{$user_event->id}}" style="color: black">
                    <div class="col-md-3" align="center" style="margin-bottom: 20px">
                        <img src="/images/event_brochure/{{$user_event->brochure}}"
                             style="max-width: 200px; max-height: 100px"/>
                        <p style="font-size: 23px; margin-top: 10px; margin-bottom: -5px"><b>{{$user_event->name}}</b>
                        </p>
                        <p style="margin-top: 5px"><b style="font-size: 18px">{{$user_event->rating}}</b> <img
                                    src="/images/star_grey.png" width="15px" height="15px" style="margin-top: -6px"/>
                            ({{$user_event->no_of_user_rate}})</p>
                    </div>
                </a>
            @endforeach
        @endif
    </div>


</div>
</body>
</html>