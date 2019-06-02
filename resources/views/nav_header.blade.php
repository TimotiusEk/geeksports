
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="favicon.ico">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700,900' rel='stylesheet' type='text/css'>

    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="css/icomoon.css">
    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="css/simple-line-icons.css">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Theme style  -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Modernizr JS -->
    <script src="js/modernizr-2.6.2.min.js"></script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->


<div id="fh5co-page">
    <header id="fh5co-header" role="banner">
        <div class="container">
            <div class="header-inner">
                <h1><a href="/home"><img src="images/pure_logo.png" width="80%"/></a></h1>
                <nav role="navigation">
                    <ul>
                        @if(Cookie::get('role_name') != "Event Organizer")
                        <li><a id="home" href="/home">Home</a></li>
                        @endif
                        @if(Cookie::get('role_name') == "Event Organizer")
                            {{--<li class="dropdown">--}}
                            {{--<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dashboard">Dashboard--}}
                            {{--<span class="caret"></span></a>--}}
                            {{--<ul class="dropdown-menu">--}}
                            {{--<li id="create_event"><a href="create_event">Create Event</a></li>--}}
                            <li><a href="my_event" id="my_event">My Event</a></li>
                            <li><a id="home" href="/home">Upcoming Event</a></li>
                            <li><a href="search_event_location" id="event_location">Event Locations</a></li>
                            {{--</ul>--}}
                            </li>
                        @endif

                        @if(Cookie::get('gamer') === "true")
                            <li><a href="participant_status" id="participant_status">Participant Status</a></li>
                        @endif
                        @if(Cookie::get('worker') === "true")
                            <li><a href="vacancy"  id="vacancy">Vacancy</a></li>
                        @endif
                        @if(Cookie::get('role_name') == "Company")
                            <li><a href="sponsorship" id="sponsorship">Sponsorship</a></li>
                        @endif

                        <li><a href="/view_profile?user_id={{Cookie::get('user_id')}}" id="profile">Profile</a></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</div>


    <script>
        $(document).ready(function () {
            var active = false;
            $("#fh5co-offcanvas").hide();
            $(".js-fh5co-nav-toggle").click(function () {
                console.log(active);
                if(!active){
                    $("#fh5co-offcanvas").show();
                    active = true;
                } else {
                    $("#fh5co-offcanvas").hide();
                    active = false;
                }
            });
        });
    </script>
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<!-- Waypoints -->
<script src="js/jquery.waypoints.min.js"></script>
<!-- MAIN JS -->
<script src="js/main.js"></script>


