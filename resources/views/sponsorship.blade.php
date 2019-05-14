<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        document.getElementById("sponsorship").className = 'active';

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function fillActionInputAndSubmit(action) {
            alert(action);
            $("#action").val(action);
            document.getElementById("sponsorship_response_form").submit();
        }
    </script>


    <style>
        li {
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: whitesmoke">

<ul class="nav nav-tabs" style="margin-left: 15px; margin-right: 15px">
    <li class="active" id="all" onclick="updateList(this.id)"><a>All</a></li>
    <li id="Invited" onclick="updateList(this.id)"><a>Invited</a></li>
    <li id="Interested" onclick="updateList(this.id)"><a>Interested</a></li>
    <li id="Deal" onclick="updateList(this.id)"><a>Deal</a></li>
</ul>

<div class="container"
     style="border-color: #491217; width: 99%; margin: 1%" id="events">

</div>

<script>
    updateList("all");

    function updateList(id) {

        $(".active").removeClass("active");
        $("#" + id).addClass("active");
        $("#sponsorship").addClass("active");

        var events = <?php echo json_encode($events); ?>;
        var city_names = <?php echo json_encode($city_names); ?>;
        var games = <?php echo json_encode($games); ?>;
        var tab = id;
        var content = "";

        for (idx = 0; idx < events.length; idx++) {
            tab = id;
            var no_interested_button = false;
            //tab 'all' but already registered
            if ((events[idx])["status"] === "all_interested" || (events[idx])["status"] === "all_deal") {
                console.log((events[idx])["status"]);
                no_interested_button = true;
                (events[idx])["status"] = "all";
            }

            if ((events[idx])["status"] === id) {

                if (no_interested_button) {
                    tab = "all_interested";
                }
                console.log(tab);
                content += '<div class="row" style="margin-bottom: 15px; background-color: white; border-radius: 10px; padding-bottom: 1%">' +
                    '            <div class="col-md-3 text-center"><img' +
                    '                        style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px; margin-top: 15px; "' +
                    '                        src="/images/event_brochure/' + (events[idx])["brochure"] + '"/></div>' +
                    '            <div class="col-md-9" style="font-size: 40px; vertical-align: top;">' +
                    '                <div style="margin-left: 70px">' +
                    '                    <p style="margin-top: 20px; margin-left: -30px;"><b><a href="/event_details?event_id=' + (events[idx])["id"] + '">' + (events[idx])["name"] + '</a></b></p>';
                if (tab === "all" || tab === "Invited") {
                    content += '                    <form  align="right" action="sponsorship" method="post" style="margin-top: -60px" id="sponsorship_response_form">' +
                        '{{csrf_field()}}' +
                        '                        <input type="hidden" name="event_id" value="' + (events[idx])["id"] + '"/>' +
                        '<input type="hidden" name="action" id="action"/>' +
                        '                        <a href="#" onclick="fillActionInputAndSubmit(\'Interested\'); "><img' +
                        '                                    src="/images/interested_btn.png" width="150px"/></a>' +
                        '<a href="#" onclick="fillActionInputAndSubmit(\'Not Interested\');"><img src="/images/not_interested_btn.png" width="150px" style="margin-left: 10px"/></a>' +
                        '                    </form>';
                }


                content += '<hr style="margin-top: 10px"><div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"' +
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

                if (games[idx] === "") {
                    content += " - ";
                } else {
                    content += games[idx];
                }
                content += '</div>';


                if ((events[idx])["details"] != null) {
                    content += '<button class="btn btn-primary" style="margin-top: 15px" onclick="showPackage(' + (events[idx])["id"] + ')" id="package_btn_' + (events[idx])["id"] + '">Show Package</button><div style="background-color: #f4f4f4; margin: 2%; padding: 2%; display: none" id="package_' + (events[idx])["id"] + '">' +
                        '                            <pre style="font-size: 23px; border-width: 0px; font-family: \'Arial\';">' + (events[idx])["details"] + '</pre>' +
                        '                        </div>';
                }
                content += '                </div>' +
                    '            </div>' +
                    '        </div>';
            }
            $('#events').html(content);


        }
    }

    function showPackage(id) {
        if ($("#package_" + id).is(":visible")) {
            $("#package_btn_" + id).html("Show Package");
            $("#package_" + id).hide();
        } else {
            $("#package_btn_" + id).html("Hide Package");
            $("#package_" + id).show();
        }
    }


</script>


</body>
</html>