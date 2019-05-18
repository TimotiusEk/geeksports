<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/home"><img src="/images/pure_logo.png" width="85px"
                                                      style="margin-top: -13px"></a>
        </div>
        <ul class="nav navbar-nav">
            <li id="home"><a href="/home">Home</a></li>


            @if(Cookie::get('role_name') == "Event Organizer")
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dashboard">Dashboard
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li id="create_event"><a href="create_event">Create Event</a></li>
                    <li id="my_event"><a href="my_event">My Events</a></li>
                    <li id="event_location"><a href="search_event_location">Event Locations</a></li>
                </ul>
            </li>
            @endif
            @if(Cookie::get('gamer') === "true")
            <li id="participant_status"><a href="participant_status">Participant Status</a></li>
            @endif
            @if(Cookie::get('worker') === "true")
            <li id="vacancy"><a href="vacancy">Vacancy</a></li>
            @endif
            @if(Cookie::get('role_name') == "Company")
            <li id="sponsorship"><a href="sponsorship">Sponsorship</a></li>
            @endif

            <li id="profile"><a href="/view_profile?user_id={{Cookie::get('user_id')}}">Profile</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="/logout">Logout</a></li>
        </ul>
    </div>
</nav>

<script>
    @if(is_null(Cookie::get('user_id')))
        window.location.href = "/";
    @endif
</script>