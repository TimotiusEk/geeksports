<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
</head>
<body style="background-color: whitesmoke">


<div class="container" style="width: 60%; margin-top: 1%; min-width: 800px">
    <div class="row" style="padding: 1%; background-color: white; border-radius: 10px; ">
        <div class="col-sm-12 text-center">

            @if(!is_null($team->team_logo))
                <img src="/images/profile_picture/{{$team->team_logo}}" width="200px" height="100px"
                     style="margin-top: 20px"/>
            @else
                <img src="/images/default_event_img.png" width="200px" height="100px"
                     style="margin-top: 20px"/>
            @endif
            <br><b style="color: black; font-size: 27px; margin-top: 10px">{{$team->team_name}}</b>
        </div>
    </div>


    <div class="row" style="background-color: white; border-radius: 10px; margin-top: 20px; padding: 1%">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 25px; color: black"><b>Members</b></p>
        <hr>

        <div class="row">
            @foreach($team->members as $member)
                <div class="col-md-4 text-center">
                    <div class="work-inner">
                        @if(!is_null($member->profile_picture))
                            <a href="/view_profile?user_id={{$member->user_id}}" class="work-grid"
                               style="background-image: url(/images/profile_picture/{{$member->profile_picture}});"></a>
                        @else
                            <a href="/view_profile?user_id={{$member->user_id}}" class="work-grid"
                               style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                        @endif


                        <div class="desc">
                            <h3>
                                <a href="/view_profile?user_id={{$member->user_id}}">{{$member->display_name}}</a>
                                @if($member->gender == "m")
                                    <span>
                                <img src="images/ic_male.png" width="20px" height="20px" data-toggle="tooltip"
                                     title="Male"/>
                                </span>
                                    @else
                                    <img src="images/ic_female.png" width="20px" height="20px" data-toggle="tooltip"
                                         title="Female"/>
                                    </span>
                                @endif
                            </h3>
                            <p>  @if(!is_null($member->city)){{$member->city}} @endif   @if(!is_null($member->city) && !is_null($member->age)) - @endif @if(!is_null($member->age)){{$member->age}} Years Old @endif</p>

                            <div style="margin-top: -30px">({{$member->games}})</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
</body>
</html>