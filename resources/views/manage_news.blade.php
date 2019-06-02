<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>

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

        .nav-pills > li.active > a, .nav-pills > li.active > a:focus {
            color: white;
            background-color: #1FB57B;
        }

        .nav-pills > li.active > a:hover {
            background-color: #1FB57B;
            color: white;
        }

    </style>

    <script>
        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function showModal(eventName, eventId, newsTitle, newsId) {
            $("#event_name").text(eventName);
            $("#event_id").val(eventId);
            $("#news_title").text(newsTitle);
            $("#news_id").val(newsId);
        }
    </script>
</head>
<body style="background-color: whitesmoke">
{{--<h1 style="margin-left: 1%; font-size: 35px"><b>Manage News</b></h1>--}}
{{--<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">--}}
{{--<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>--}}

<div class="container-fluid" style="margin-right: -10px; margin-bottom: 20px">
    <div class="row" style="background-color: white; width: 98%; padding: 1%; margin: 1%; border-radius: 10px;">
        <div class="col-md-4 text-center">
            @if(!is_null($event->brochure))
                <img style=" height: 225px; width: 400px; border-radius: 10px;"
                     src="/images/event_brochure/{{$event->brochure}}"/>
            @else
                <img style=" height: 225px; width: 400px; border-radius: 10px;"
                     src="/images/default_event_img.png"/>
            @endif
        </div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <p style="margin-top: 10px">
                    <b>
                        <a href="event_details?event_id={{$event->id}}" style="color: black; font-size: 30px">{{$event->name}}</a>
                    </b>
                </p>
                <hr>
                <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                     style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                    @if($event->city != null){{$event->city}} @else - @endif
                </div>
                <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                       style="width: 30px; margin-right: 10px; margin-top: -5px">
                    @if($event->start_date != null) {{$event->start_date}} - {{$event->end_date}} @else - @endif
                </div>
                <div style="font-size: 23px; "><img src="images/ic_game.png"
                                                    style="width: 30px; margin-right: 10px; margin-top: -5px">
                    {{$event->game}}

                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <ul id="dashboard_nav_bar" class="nav nav-pills nav-justified justify-content-center"
            style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%;">
            <li id="participant">
                <form action="manage_participant" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_participant">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_participant').submit()">Participant</a>
            </li>
            <li>
                <form action="manage_vacancy" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_vacancy">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_vacancy').submit()">Vacancy</a>
            </li>
            <li class="active">
                <form action="manage_news" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_news">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_news').submit()">News</a>
            </li>
            <li>
                <form action="manage_sponsorship_package" method="post" style="margin-left: 2px; margin-bottom: -5px"
                      id="manage_sponsorship_package">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_sponsorship_package').submit()">Sponsorship</a>
            </li>
            <li id="event_location"><a href="/manage_streaming_channel?event_id={{$event->id}}">Streaming Channels</a>
            </li>
        </ul>
    </div>

    <div class="row"
         style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color:  white">
        <h1 align="center" style="margin-top: 5%; margin-bottom: 5%" id="no_news"><b>No News is Drafted or Published</b>
        </h1>

        <form action="write_news" method="post">
            {{csrf_field()}}
            <a href="#" onclick="$(this).closest('form').submit()">
                <input type="hidden" name="event_id" value="{{$event_id}}"/>

                <img src="/images/ic_add_news.png"
                     style="width: 160px;" align="right"
                     id="write_news"></a>
        </form>
    </div>

    <div class="row"
         style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color:  white"
         align="right">

        <form action="write_news" method="post">
            {{csrf_field()}}
            <input type="hidden" name="event_id" value="{{$event_id}}"/>

            <button type="submit" class="btn btn-primary"
                    style="width: fit-content; padding-left: 30px; padding-right: 30px; display: none"
                    id="write_news_btn">
                <b>Write News</b></button>
        </form>

    </div>

    <div class="row" id="datatable"
         style="display: none; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color:  white">
        <table id="table_id" class="cell-border table-bordered">
            <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Last Modified</th>
                <th>Published On</th>
                <th>Status</th>
                <th>Header Image</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @for($idx = 0 ; $idx < count($news) ; $idx++)
                <tr>
                    <td>{{$idx+1}}</td>
                    <td>{{($news[$idx])->title}}</td>
                    <td>{{($news[$idx])->created_at}}</td>
                    <td>@if(($news[$idx])->last_modified != null){{($news[$idx])->last_modified}} @else - @endif</td>
                    <td>@if(($news[$idx])->published_on != null) {{($news[$idx])->published_on}} @else - @endif</td>
                    <td>{{($news[$idx])->status}}</td>
                    <td>@if(($news[$idx])->header_image == null) - @else <img
                                src="/images/news_header/{{($news[$idx])->header_image}}" id="{{($news[$idx])->id}}"
                                style="display: none" alt="{{($news[$idx])->title}} Header Image"/><a href="#"
                                                                                                      onclick="showHeaderImg('{{($news[$idx])->id}}')">Show
                            Header Image</a>@endif</td>
                    <td>
                        @if(($news[$idx])->status == "Draft")
                            <form action="update_news" method="post" style="margin-bottom: -10px">
                                {{csrf_field()}}
                                <input type="hidden" name="news_id" value="{{($news[$idx])->id}}"/>
                                <input type="hidden" name="event_id" value="{{$event_id}}"/>

                                <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                            style="width: 60px; height: 40px;"/>
                                </a>
                            </form><br>
                            <a href="#myModal"
                               onclick="showModal('{{$event_information}}', '{{$event_id}}', '{{($news[$idx])->title}}', '{{($news[$idx])->id}}')"
                               data-toggle="modal"><img src="/images/ic_delete.png"
                                                        style="width: 65px; height: 45px; margin-bottom: 10px;"/></a>
                            <br>

                            <form action="publish_news" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="news_id" value="{{($news[$idx])->id}}"/>
                                <input type="hidden" name="event_id" value="{{$event_id}}"/>

                                <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_publish.png"
                                                                                            style="width: 65px; height: 45px; margin-left: 3px"/></a>
                            </form>
                        @elseif(($news[$idx])->status == "Published")
                            <a href="#myModal"
                               onclick="showModal('{{$event_information}}', '{{$event_id}}', '{{($news[$idx])->title}}' , '{{($news[$idx])->id}}')"
                               data-toggle="modal"><img src="/images/ic_delete.png"
                                                        style="width: 65px; height: 45px; margin-top: 4px; margin-bottom: 10px;"/></a>
                        @endif
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
</div>


<!-- Delete Vacancy MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <form action="delete_news" method="post">
        {{csrf_field()}}
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="news_id" id="news_id"/>
                    <input type="hidden" name="event_id" id="event_id"/>

                    Are you sure you want to delete news "<b><span id="news_title"></span></b>" for "<b><span
                                id="event_name"></span></b>" event?
                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-danger">Delete</button>

                    <button class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- The Modal -->
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
                "targets": -1,
                "orderable": false
            }]
        });

        $("th").addClass("dt-head-center");
        $("td").addClass("dt-body-center");
    });

    // $("#dashboard_nav_bar").show();


    @if(count($news) != 0)
    $("#no_news").hide();
    $("#datatable").show();
    $("#write_news").hide();
    $("#write_news_btn").show();
    @endif
</script>

<script>
    function showHeaderImg(id) {
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