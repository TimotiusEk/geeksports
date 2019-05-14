<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

        function showModal(eventName, streamingChannelId, packageName) {
            $("#event_name").text(eventName);
            $("#streaming_channel_id").val(streamingChannelId);
            $("#team_name").text(packageName);
        }
    </script>
    <style>
        /*no horizontal scrolling*/
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        pre {
            white-space: pre-wrap;
        }
    </style>

</head>
<body style="background-color: whitesmoke">
{{--<h1 style="margin-left: 1%; font-size: 35px"><b>Manage Streaming Channel</b></h1>--}}
{{--<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">--}}
{{--<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 50px">{{$event_information}}</h2>--}}



<div class="container-fluid" style="margin-right: -10px">
    <div class="row" style="background-color: white; width: 98%; padding: 1%; margin: 1%; border-radius: 10px;">
        <div class="col-md-4 text-center">
            <img style=" height: 225px; width: 400px; border-radius: 10px;"
                 src="/images/event_brochure/{{$event->brochure}}"/>
        </div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <p style="margin-top: 10px; margin-left: -30px;">
                    <b>
                        <a href="event_details?event_id={{$event->id}}" style="color: black">{{$event->name}}</a>
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
            <li>
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
            <li>
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
            <li class="active"><a href="/manage_streaming_channel?event_id={{$event->id}}">Streaming Channels</a>
            </li>
        </ul>
    </div>

    <div class="row" style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; padding-top: 1%; background-color: white">
        <h1 align="center" style="margin-top: 5%; margin-bottom: 5%" id="no_streaming_channel"><b>There is still no
                Streaming Channel for This
                Event</b></h1>

        <form action="add_streaming_channel" method="get" align="right">
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
            <a href="#" id="add_streaming_channel" onclick="$(this).closest('form').submit()"><img
                        src="/images/ic_add_streaming_channel.png"
                        style="width: 250px; margin-right: 20px"></a>
        </form>
    </div>

    <div class="row" align="right" style="display: none; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color: white" id="add_streaming_channel_btn">
        <div class="col-md-12">
            <form action="add_streaming_channel" method="get"
                  style="width: fit-content">
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 10px; padding-right: 10px"><b>Add Streaming Channel</b></button>
            </form>
        </div>
    </div>

    <div id="datatable" style="display:none; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color: white" class="row">
        <table id="table_id" class="cell-border table-bordered responsive" style="width: 99%">
            <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>URL</th>
                <th>Start Datetime</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @for($idx = 0 ; $idx < count($streaming_channels) ; $idx++)
                <tr>
                    <td class="center">{{$idx+1}}</td>
                    <td class="center">{{($streaming_channels[$idx])->title}} </td>
                    <td class="center"><a
                                href="{{($streaming_channels[$idx])->url}}">{{($streaming_channels[$idx])->url}}</a>
                    </td>
                    <td class="center">{{($streaming_channels[$idx])->start_datetime}} @if(is_null(($streaming_channels[$idx])->start_datetime))
                            - @endif </td>
                    <td valign="center" class="center"
                        style="width: 1px; height: 96%; padding-top: 2%; padding-bottom: 2%">
                        <form action="update_streaming_channel" method="get">
                            <input type="hidden" name="event_id" value="{{$event_id}}"/>
                            <input type="hidden" name="streaming_channel_id"
                                   value="{{($streaming_channels[$idx])->id}}"/>
                            <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                        style="width: 60px; height: 40px;"/></a>
                        </form>
                        <br>
                        <a href="#myModal"
                           onclick="showModal('{{$event_information}}', '{{($streaming_channels[$idx])->id}}', '{{($streaming_channels[$idx])->title}}')"
                           data-toggle="modal"><img src="/images/ic_delete.png" style="width: 65px; height: 45px;"/><br></a>
                    </td>
                </tr>
                @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!-- Delete winner MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <form action="delete_streaming_channel" method="post">
        {{csrf_field()}}
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="streaming_channel_id" id="streaming_channel_id"/>
                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    Are you sure you want to delete <b><span id="team_name"></span></b> from "<b><span
                                id="event_name"></span></b>" event?
                </div>

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
                "targets": -1,
                "orderable": false
            },]

        });

        $("th").addClass("dt-head-center");
        $("td.center").addClass("dt-body-center");
    });

    <?php
    if(count($streaming_channels) != 0){
    ?>
    $("#no_streaming_channel").hide();
    $("#add_streaming_channel").hide();
    $("#datatable").show();
    $("#add_streaming_channel_btn").show();

    <?php
    }
    ?>
</script>
</body>
</html>