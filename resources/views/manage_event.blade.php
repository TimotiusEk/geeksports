<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        $("#dashboard_nav_bar").show();
        document.getElementById("manage_event").className = 'active';
        document.getElementById("dashboard").className = 'active';

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function showModal(eventName, eventId) {
            $("#event_name").text(eventName);
            $("#event_id").val(eventId);
        }
    </script>

    <style>
        /* Style the Image Used to Trigger the Modal */
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal_2 {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }
            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>


<table id="table_id" class="cell-border table-bordered" width="99%" >
    <thead>
    <tr>
        <th>Event</th>
        {{--<th>City</th>--}}
        {{--<th>Date</th>--}}
        {{--<th>Games</th>--}}
        {{--<th>Event Details</th>--}}
        {{--<th>Participant Registration</th>--}}
        {{--<th>Brochure</th>--}}
        <th>Status</th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    @for($idx = 0 ; $idx < count($events) ; $idx++)
        <tr>
            <td id="event_name_{{$idx + 1}}">
                <div class="container"
                     style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%; padding: 1%">
                    <div class="row">
                        <div class="col-md-3 text-center"><img
                                    style=" height: 230px; width: 320px; border-radius: 10px; margin-left: -5px; margin-top: 15px;"
                                    src="/images/event_brochure/{{($events[$idx])->brochure}}"/></div>
                        <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
                            <div style="margin-left: 70px">

                                <div class="row">
                                    <div class="col-md-10" style="margin-top: 15px">
                                        <b><a
                                                    href="/event_details?event_id={{($events[$idx])->id}}">{{($events[$idx])->name}}</a>
                                        </b>
                                    </div>
                                    <div class="col-md-2" align="right">
                                        <form action="update_event_form" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                                        src="/images/edit_btn.png"
                                                        style="width: 95px; height: 40px;"/>
                                            </a>
                                        </form>
                                        <a href="#myModal" onclick="showModal('{{($events[$idx])->name}}', '{{($events[$idx])->id}}')"
                                           data-toggle="modal"><img
                                                    src="/images/delete_btn.png"
                                                    style="width: 95px; height: 40px; margin-top: -30px; margin-bottom: 30px"/></a>

                                    </div>
                                </div>
                                <div class="row" style="margin-top: -20px">
                                    <hr>
                                </div>




                                <div style="font-size: 23px; margin-top: 10px"><img src="images/ic_location.png"
                                                                                     style="width: 30px; margin-right: 10px; margin-bottom: 10px;"> @if($city_name[$idx] != null){{$city_name[$idx]}} @else
                                        - @endif
                                </div>
                                <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                                       style="width: 30px; margin-right: 10px; margin-top: -5px">@if($events[$idx]->start_date != null) {{($events[$idx])->start_date}}
                                    - {{($events[$idx])->end_date}} @else - @endif
                                </div>
                                <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                                    style="width: 30px; margin-right: 10px; margin-top: -5px">
                                    @if(($games[$idx]) != null){{$games[$idx]}} @else - @endif
                                </div>

                            </div>
                        </div>
                    </div>
            </td>


            {{--<td>@if(($events[$idx])->city_id != null){{$city_name[$idx]}} @else - @endif</td>--}}
            {{--            <td id="date_{{$idx + 1}}">{{($events[$idx])->start_date}} - {{($events[$idx])->end_date}}</td>--}}
            {{--<td>@if($games[$idx] != null){{$games[$idx]}} @else - @endif</td>--}}
            {{--            <td>@if(($events[$idx])->details != null){{($events[$idx])->details}} @else - @endif</td>--}}
            {{--<td>@if(($events[$idx])->participant_registration != null && ($events[$idx])->participant_registration == true)--}}
            {{--<form action="manage_participant" method="post">--}}
            {{--{{csrf_field()}}--}}
            {{--<input type="hidden" name="event_id" value="{{($events[$idx])->id}}"/>--}}
            {{--<input type="hidden" name="event_information"--}}
            {{--value="{{($events[$idx])->name}} ({{($events[$idx])->start_date}} - {{($events[$idx])->end_date}})"/>--}}
            {{--<button class="btn btn-primary"><b>Manage Participants</b></button>--}}
            {{--</form>--}}
            {{--@else--}}
            {{--Closed--}}
            {{--@endif--}}
            {{--</td>--}}
            {{--<td>@if(($events[$idx])->brochure == null) - @else <img--}}
            {{--src="/images/event_brochure/{{($events[$idx])->brochure}}" id="{{($events[$idx])->id}}"--}}
            {{--style="display: none" alt="{{($events[$idx])->name}} Brochure"/><a href="#"--}}
            {{--onclick="showBrochure('{{($events[$idx])->id}}')">View--}}
            {{--Brochure</a>@endif</td>--}}
            <td class="center">{{($events[$idx])->status}}
            <td class="center" valign="center">



                <form action="manage_participant" method="post" style="margin-left: 2px; margin-bottom: -5px">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{($events[$idx])->id}}"/>
                    <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_participants.png"
                                                                                style="width: 100px; height: 70px;"/></a>
                </form>
                <form action="manage_vacancy" method="post" style="margin-bottom: -10px">
                    {{csrf_field()}}
                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                    <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_vacancy.png"
                                                                                style="width: 65px; height: 45px;"/></a>
                </form>
                <br>
                <form action="manage_news" method="post"
                      style="margin-bottom: -10px">
                    {{csrf_field()}}
                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                    <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_manage_news.png"
                                                                                style="width: 70px; height: 50px; margin-bottom: 10px; margin-left: 3px;"/></a>
                </form>
                <br>
                <form action="manage_sponsorship_package" method="post" style="margin-left: 2px; margin-bottom: -10px">

                    {{csrf_field()}}
                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                    <a href="#" onclick="$(this).closest('form').submit()"><img
                                src="/images/ic_manage_sponsorship_package.png"
                                style="width: 100px; height: 68px;"/></a>
                </form>
                <br>
                <form action="manage_streaming_channel" method="get" style="margin-left: 2px; margin-bottom: -10px">
                    <input type="hidden" name="event_id" value="{{($events[$idx])->id}}"/>
                    <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_streaming_channels.png"
                                                                                style="width: 90px; height: 68px;"/></a>
                </form>
                @if(($events[$idx])->status == "Draft")
                    <br>
                    <form action="publish_event" method="post"
                          style="margin-left: 2px; margin-bottom: -10px; margin-bottom: 10px">
                        {{csrf_field()}}
                        <input type="hidden" name="event_id" value="{{($events[$idx])->id}}"/>
                        <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_publish.png"
                                                                                    style="width: 80px; height: 50px; margin-left: 3px"/></a>
                    </form>

                @elseif(($events[$idx])->status == "Published")

                    <br>
                    <form action="unpublish_event" method="post"
                          style="margin-left: 2px; margin-bottom: -10px; margin-bottom: 10px">
                        {{csrf_field()}}
                        <input type="hidden" name="event_id" value="{{($events[$idx])->id}}"/>
                        <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_unpublish.png"
                                                                                    style="width: 80px; height: 50px; margin-left: 3px"/></a>
                    </form>
                @endif
            </td>
        </tr>
    @endfor
    </tbody>
</table>

<!-- Delete Event MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <form action="delete_event" method="post">
        {{csrf_field()}}
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="event_id" id="event_id"/>
                    Are you sure you want to delete "<b><span id="event_name"></span></b>"?
                </div>

                {{--<form></form>--}}
                <div class="modal-footer">

                    <button type="submit" class="btn btn-danger">Delete</button>

                    <button type="submit" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>


<!-- Show Image Modal -->
<div id="imageModal" class="modal_2">

    <!-- The Close Button -->
    <span class="close">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>

<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            "bLengthChange": false,
            "dom": '<"pull-left"f><"pull-right"l>tip',
            "columnDefs": [{
                "targets": [-1],
                "orderable": false
            }]
        });

        $("th").addClass("dt-head-center");
        $("td.center").addClass("dt-body-center");
    });
</script>
<script>
    function showBrochure(id) {
        // Get the modal
        var modal = document.getElementById('imageModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById(id);
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[1];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>