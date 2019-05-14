<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/home"><img src="/images/pure_logo.png" width="85px"
                                                      style="margin-top: -13px"></a>
        </div>
        <ul class="nav navbar-nav">
            <li id="home"><a href="/home">Home</a></li>
            <!--            <li id="search"><a href="/search">Search</a></li>-->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dashboard">Dashboard
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li id="create_event"><a href="create_event">Create Event</a></li>
                    <li id="my_event"><a href="my_event">My Events</a></li>
                    <li id="event_location"><a href="search_event_location">Event Locations</a></li>
                </ul>
            </li>
            <li id="participant_status"><a href="participant_status">Participant Status</a></li>
            <li id="vacancy"><a href="vacancy">Vacancy</a></li>
            <li id="sponsorship"><a href="sponsorship">Sponsorship</a></li>

            <li id="profile"><a href="/view_profile?user_id={{Cookie::get('user_id')}}">Profile</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="/logout">Logout</a></li>
        </ul>
    </div>

    {{--<ul id="dashboard_nav_bar" class="nav nav-pills nav-justified justify-content-center"--}}
        {{--style="margin-top: 1%; display: none;">--}}
        {{--<li id="create_event"><a href="/create_event">Create Event</a></li>--}}
        {{--<!--        <li id="broadcast_news"><a href="/broadcast_news">Broadcast News</a></li>-->--}}
        {{--<li id="manage_event"><a href="/manage_event">Manage Event</a></li>--}}
        {{--<li id="event_location"><a href="/search_event_location">Event Location</a></li>--}}
    {{--</ul>--}}
</nav>

<script>
    @if(Cookie::get('role_name') == "Individual")

    $("#dashboard").hide();
    $("#sponsorship").hide();

    @elseif(Cookie::get('role_name') == "Event Organizer")

    $("#vacancy").hide();
    $("#sponsorship").hide();
    $("#participant_status").hide();

    @elseif(Cookie::get('role_name') == "Company")

    $("#dashboard").hide();
    $("#vacancy").hide();
    $("#participant_status").hide();

    @else
        window.location.href = "/";
    @endif
</script>