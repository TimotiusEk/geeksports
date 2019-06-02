<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <style>
        td {
            padding-left: 100px;
            text-align: center;
        }
    </style>

    <script>
        document.getElementById("profile").className = 'active';
    </script>
</head>
<body style="background-color: whitesmoke">
<div class="container" style="width: 60%; margin-top: 2%">

    <div style="background-color: white; border-radius: 10px;">
        <div class="row" style="padding: 2%">
            <div class="col-md-5 text-center">
                <img src="/images/profile_picture/{{$user->profile_picture}}" width="300px" height="200px"
                     style="margin-top: 20px"/>
            </div>
            <div class="col-md-7" style="margin-top: 10px">
                <b style="color: black; font-size: 30px">{{$user->display_name}} @if($edit == true)<a
                            href="update_profile?user_id={{$user->user_id}}"><img src="/images/ic_edit_no_text.png"
                                                                                  width="25px"/></a>@endif</b>
                <p style="font-size: 20px; margin-top: -5px; margin-bottom: 5px">Event
                    Organizer @if($user->company_name !== "")({{$user->company_name}})@endif</p>


                @if(!is_null($user->contact_person))
                    <div class="work-inner" style="margin-top: 4%">
                        <div class="desc">
                       <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial'; background-color: white"
                            align="left">{{$user->contact_person}}</pre>

                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
</div>

<div class="row"
     style="background-color: white; border-radius: 10px; width:58%; padding: 1%; margin-top: 1%; margin-left: 21%; margin-right: 21%">
    <p style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black"><b>Managed Event</b></p>
    <hr>
    @foreach($user->events as $user_event)
        <a href="event_details?event_id={{$user_event->id}}" style="color: black">
            <div class="col-md-3" align="center" style="margin-bottom: 20px">
                @if(!is_null($user_event->brochure))
                    <img src="/images/event_brochure/{{$user_event->brochure}}"
                         style="width: 200px; height: 100px"/>
                @else
                    <img style="width: 200px; height: 100px; border-radius: 10px;"
                         src="/images/default_event_img.png"/>
                @endif

                <p style="font-size: 23px; margin-top: 10px; margin-bottom: -5px"><b>{{$user_event->name}}</b></p>
                <p style="margin-top: 5px"><b style="font-size: 18px">{{$user_event->rating}}</b> <img
                            src="/images/star_grey.png" width="15px" height="15px" style="margin-top: -6px"/>
                    ({{$user_event->no_of_user_rate}})</p>
            </div>
        </a>
    @endforeach
</div>


</body>
</html>