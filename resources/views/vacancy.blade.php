<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        document.getElementById("vacancy").className = 'active';

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });
    </script>

    <style>
        li {
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: whitesmoke">

<ul class="nav nav-tabs" style="margin-left: 15px; margin-right: 15px; margin-top: 1%">
    <li class="active" id="all" onclick="updateList(this.id)"><a>All</a></li>
    <li id="invited" onclick="updateList(this.id)"><a>Invited</a></li>
    <li id="Register" onclick="updateList(this.id)"><a>Registered</a></li>
    <li id="Confirm" onclick="updateList(this.id)"><a>Confirmed</a></li>
    <li id="Decline" onclick="updateList(this.id)"><a>Declined</a></li>
</ul>

<div class="container"
     style="width: 60%; margin-top: 2%; min-width: 900px" id="vacancies">

</div>

<script>
    updateList("all");

    function updateList(id) {

        $(".active").removeClass("active");
        $("#vacancy").addClass("active");
        $("#" + id).addClass("active");

        var events = <?php echo json_encode($events); ?>;
        var event_organizers = <?php echo json_encode($event_organizers); ?>;
        var vacant_roles = <?php echo json_encode($vacant_roles); ?>;
        var city_names = <?php echo json_encode($city_names); ?>;
        var games = <?php echo json_encode($games); ?>;
        var tab = id;
        var content = "";


        for (idx = 0; idx < events.length; idx++) {

            tab = id;
            var no_register_button = false;
            //tab 'all' but already registered
            if ((events[idx])["status"] === "all_registered") {
                no_register_button = true;
                (events[idx])["status"] = "all";
            }


            if ((events[idx])["status"] === "all" && !no_register_button && !((events[idx])["open_vacancy"])) {
                console.log(((events[idx])["open_vacancy"]));
                (events[idx])["status"] = "ignore";
            }
            console.log((events[idx])["status"]);
            if ((events[idx])["status"] === id) {

                console.log(id);
                if (no_register_button) {
                    tab = "all_registered";
                }


                content += '<div class="row" style="margin-bottom: 15px; background-color: white; border-radius: 10px; padding-bottom: 1%">' +
                    '            <div class="col-md-5 text-center">';

                if ((events[idx])["brochure"] != null) {
                    content += '                        <img style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px; "' +
                    '                        src="/images/event_brochure/' + (events[idx])["brochure"] + '"/>';
                } else {
                    content += '                        <img style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px; "' +
                        '                        src="/images/default_event_img.png"/>';
                }

                content += '            </div><div class="col-md-7" style="font-size: 40px; vertical-align: top;">' +
                    '                <div style="margin-left: 70px">' +
                    '                    <p style="margin-top: 20px;"><b><a href="/event_details?event_id=' + (events[idx])["id"] + '" style="color: black; font-size: 30px">' + (events[idx])["name"] + '</a></b></p>'+
                        '<p style="margin-top: -20px">By: <a href="view_profile?user_id=' + (event_organizers[idx])["user_id"] + '">' + (event_organizers[idx])["display_name"] +'</a></p>';
                if ((tab === "all" || tab === "invited")) {
                    content += '                    <form action="vacancy_registration_form" method="GET" style="margin-top: -60px">' +
                        '                        <input type="hidden" name="event_id" value="' + (events[idx])["id"] + '"/>' +
                        '                        <a href="#" onclick="$(this).closest(\'form\').submit()"><img' +
                        '                                    src="/images/register_btn.png" width="125px" align="right"/></a>' +
                        '                    </form>' +
                        '<hr style="margin-top: 70px">';
                } else {
                    content += '<hr style="margin-top: 10px">';
                }


                content += '                    <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_person_dark_grey.png"' +
                    '                                                                         style="width: 23px; margin: 10px 8px 20px 3px;">' +
                    '                        ' + vacant_roles[idx] + '' +
                    '                    </div>' +
                    '                    <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"' +
                    '                                                                         style="width: 30px; margin-right: 10px; margin-bottom: 10px;"> ';
                if (city_names[idx] === undefined) {
                    content += " - ";
                } else {
                    content += city_names[idx];
                }

                content += '                    </div>' +
                    '                    <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"' +
                    '                                                                           style="width: 30px; margin-right: 10px; margin-top: -5px">';
                if ((events[idx])["start_date"] != null) {
                    content += (events[idx])["start_date"];
                }
                content += ' - ';

                if ((events[idx])["end_date"] != null) {
                    content += (events[idx])["end_date"];
                }

                content += '</div>' +
                    '<div style="font-size: 23px; "><img src="images/ic_game.png" style="width: 30px; margin-right: 10px; margin-top: -5px">';

                if (games[idx] == "") {
                    content += " - ";
                } else {
                    content += games[idx];
                }
                content += '</div>';


                if ((events[idx])["details"] != null) {
                    content += '                        <div style="background-color: #f4f4f4; margin: 2%; padding: 2%">' +
                        '                            <pre style="font-size: 23px; border-width: 0px; font-family: \'Arial\';">' + (events[idx])["details"] + '</pre>' +
                        '                        </div>';
                }
                content += '                </div>' +
                    '            </div>' +
                    '        </div>';
            }
            $('#vacancies').html(content);


        }
    }


</script>


</body>
</html>