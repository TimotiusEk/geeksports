<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <style>
        td {
            text-align: center;
            padding-left: 30px;
            padding-right: 30px;
        }

        tr.highlight {
            border-top: 15px solid;
            border-bottom: 30px solid;
            border-color: transparent;
        }

        /* Tooltip container */
        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
        }

        /* Tooltip text */
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            /* Position the tooltip text - see examples below! */
            position: absolute;
            z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
            visibility: visible;
        }

        .vertical-align {
            display: flex;
            align-items: center;
        }
    </style>

    <script>
        @if($edit == true)
        document.getElementById("profile").className = 'active';
        @endif
    </script>
</head>
<body style="background-color: whitesmoke">


<div class="container" style="width: 60%; padding: 1%; margin-top: 1%; min-width: 800px">
    <div class="row" style="padding: 2%; background-color: white; border-radius: 10px; ">
        <div class="col-sm-5 text-center">

            @if(!is_null($user->profile_picture))
                <img src="/images/profile_picture/{{$user->profile_picture}}" width="300px" height="200px"
                     style="margin-top: 20px"/>
            @else
                <img src="/images/default_event_img.png" width="300px" height="200px"
                     style="margin-top: 20px"/>
            @endif

        </div>
        <div class="col-sm-7" style="margin-top: 10px">
            <b style="color: black; font-size: 30px">{{$user->display_name}} @if($user->gender == "m") <span><img src="images/ic_male.png" width="20px" height="20px" data-toggle="tooltip" title="Male"/> </span> @elseif($user->gender == "f") <span><img src="images/ic_female.png" width="20px" height="20px" data-toggle="tooltip" title="Female"/> @endif @if($edit == true)<a
                        href="update_profile?user_id={{$user->user_id}}"><img src="/images/ic_edit_no_text.png"
                                                                              width="25px"/></a>@endif</b>
            <p style="font-size: 20px; margin-top: -5px; margin-bottom: 5px">{{$user->subrole}}</p>

            @if(!is_null($user->city_id))
            <div style="font-size: 23px; margin-bottom: 10px; color: black"><img src="images/ic_location.png"
                                                                                 style="width: 30px;"> {{$user->city}}
            </div>
            @endif

            @if(!is_null($user->dob))
            <div style="font-size: 23px; margin-bottom: 10px; color: black"><img src="images/ic_birthdate.png"
                                                                                 style="width: 30px; margin-top: -10px"> {{$user->dob}}

            </div>
            @endif


            @if(!is_null($user->description))
                <div class="work-inner" style="margin-top: 4%">
                    <div class="desc">
                       <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial'; background-color: white"
                            align="left">{{$user->description}}</pre>
                    </div>
                </div>
            @endif


        </div>
    </div>

    <div class="row" style="background-color: white; border-radius: 10px; margin-top: 30px; padding: 1%">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black"><b>Games</b></p>
        <hr style="margin-bottom: -30px">

        <div align="center">
            {{--<table>--}}
                @for($idx = 0 ; $idx < count($user->games) ; $idx++)
                    @if($idx % 3 == 0)
                        <div class="row vertical-align">
                            @endif
                            <div class="col-md-4" style="margin-top: 50px">
                                <img src="/images/game_logo/{{(($user->games)[$idx])->logo}}"
                                     style="width: 100%; height: auto; max-width: 120px"/>
                            </div>
                            @if($idx % 3 == 2 || $idx == count($user->games)-1)
                        </div>
                    @endif
                @endfor
            {{--</table>--}}

        </div>
    </div>

    @if(!is_null($user->events))
        <div class="row" style="background-color: white; border-radius: 10px; margin-top: 30px; padding: 1%">
            <p style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black"><b>Participated Event</b></p>
            <hr>

            <div class="col-md-3" align="center" style="margin-bottom: 20px">
                @foreach($user->events as $user_event)
                    <img src="/images/event_brochure/{{$user_event->brochure}}"
                         style="max-width: 200px; max-height: 100px"/>
                    <p style="font-size: 23px; margin-top: 10px; margin-bottom: -5px;"><b><a
                                    href="event_details?event_id={{$user_event->id}}" style="color: black">{{$user_event->name}}</a></b></p>
                    <p style="margin-top: -30px">{{$user_event->subrole}}</p>
                    <p style="margin-top: -30px">{{$user_event->experience_type}}</p>
                @endforeach
            </div>
        </div>
    @endif

</div>
</body>
</html>