<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        document.getElementById("my_event").className = 'active';

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


<table id="table_id" class="cell-border table-bordered" width="99%">
    <thead>
    <tr>
        <th>My Event</th>
        {{--<th>City</th>--}}
        {{--<th>Date</th>--}}
        {{--<th>Games</th>--}}
        {{--<th>Event Details</th>--}}
        {{--<th>Participant Registration</th>--}}
        {{--<th>Brochure</th>--}}
        {{--<th>Status</th>--}}
        {{--<th></th>--}}
    </tr>
    </thead>
    <tbody>
    <div align="right" style="margin: 1%">
    <a href="/create_event"><input type="button" class="btn btn-primary" value="Create Event"/></a>
    </div>
    @for($idx = 0 ; $idx < count($events) ; $idx++)
        <tr>
            <td id="event_name_{{$idx + 1}}">
                <div class="container"
                     style="background-color: white; border-radius: 10px; border-color: #491217; width: 100%;">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if(!is_null(($events[$idx])->brochure))
                            <img style=" height: 230px; width: 320px; border-radius: 10px; margin-left: -5px; margin-top: 35px;"
                                    src="/images/event_brochure/{{($events[$idx])->brochure}}"/>
                            @else
                                <img style=" height: 230px; width: 320px; border-radius: 10px; margin-left: -5px; margin-top: 35px;"
                                     src="/images/default_event_img.png"/>
                            @endif
                        </div>
                        <div class="col-md-9" style="font-size: 40px; vertical-align: top; padding: 3%">
                            <div style="margin-left: 70px">

                                <div class="row">
                                    <div class="col-md-7">
                                        <b><a
                                                    href="/event_details?event_id={{($events[$idx])->id}}"
                                                    style="color: black">{{($events[$idx])->name}}</a>@if(($events[$idx])->status == "Draft") <p style="color: red; font-size: 20px">(In Draft)</p>@endif
                                        </b>
                                    </div>

                                    <div class="col-md-5" align="right">
                                        <span style="float: right">
                                        <a href="#myModal"
                                           onclick="showModal('{{($events[$idx])->name}}', '{{($events[$idx])->id}}')"
                                           data-toggle="modal"><img
                                                    src="/images/ic_delete.png"
                                                    style="width: 60px; height: 50px;"/></a>
                                        </span>

                                        <span style="float: right">
                                        <form action="update_event_form" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                                        src="/images/ic_edit.png"
                                                        style="width: 60px; height: 50px; margin-right: 10px"/>
                                            </a>
                                        </form>
                                        </span>

                                        <span style="float: right">
                                        <form action="manage_participant" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                                        src="/images/ic_manage.png"
                                                        style="width: 60px; height: 50px; margin-right: 10px"/>
                                            </a>
                                        </form>
                                            </span>

                                        <span style="float: right">
                                            @if(($events[$idx])->status == "Published")
                                                <form action="unpublish_event" method="post">
                                            {{csrf_field()}}
                                                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                                        src="/images/ic_unpublish.png"
                                                        style="width: 70px; height: 50px; margin-right: 10px"/>
                                            </a>
                                        </form>
                                            @else
                                                <form action="publish_event" method="post">
                                            {{csrf_field()}}
                                                    <input type="hidden" value="{{($events[$idx])->id}}" name="event_id"/>
                                            <a href="#" onclick="$(this).closest('form').submit()"><img
                                                        src="/images/ic_publish.png"
                                                        style="width: 70px; height: 50px; margin-right: 10px"/>
                                            </a>
                                        </form>
                                            @endif
                                            </span>
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
                </div>
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
</body>
</html>