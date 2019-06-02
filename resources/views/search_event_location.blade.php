<html>
<head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        document.getElementById("event_location").className = 'active';
    </script>
</head>
<body style="background-color: whitesmoke;">
<div class="container">
    <div class="row">
        <form action="search_event_location" style="margin-top: 2%">
            <div class="container">
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Type here . . ."
                                   value="{{$q}}" style="background-color: white">
                            <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" style="height: 34px"><span
                                class="glyphicon glyphicon-search"></span></button>
                </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @foreach($event_locations as $event_location)
        <div class="row" style="background-color: white; margin-top: 20px">
            <div class="col-md-3 text-center"><img
                        style="height: 150px; width: 225px; border-radius: 10px; margin-top: 15px; padding-top: 45px; padding-left: 30px"
                        src="/images/{{$event_location->logo}}"/></div>
            <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                <div style="margin-left: 70px">
                    <p style="margin-top: 20px;">
                        <b>
                            <a href="#" style="color: black; font-size: 30px">
                                {{$event_location->name}}
                            </a>
                        </b>
                    </p>
                    <hr>
                    <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                         style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                        {{$event_location->city}}
                    </div>

                    <div style="font-size: 23px;">
                        <a href="{{$event_location->gmaps_url}}">See in Google Maps</a>
                    </div>

                    <div style="font-size: 23px; ">
                        <form action="event_location_details" method="get">
                            <input type="hidden" name="event_location_id" value="{{$event_location->id}}"/>
                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                        src="/images/details_btn.png" width="125px" align="right"
                                        style="margin-bottom: 10px"/></a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
