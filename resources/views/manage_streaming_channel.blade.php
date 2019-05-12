<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <script>
        $("#dashboard_nav_bar").show();

        document.getElementById("dashboard").className = 'active';

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
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Manage Streaming Channel</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 50px">{{$event_information}}</h2>
<h1 align="center" style="margin-top: 15%" id="no_streaming_channel"><b>There is still no Streaming Channel for This
        Event</b></h1>

<form action="add_streaming_channel" method="get">
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <a href="#" id="add_streaming_channel" onclick="$(this).closest('form').submit()"><img
                src="/images/ic_add_streaming_channel.png"
                style="width: 250px; margin-right: 1.5%; position:absolute; right:0; margin-top: 13%;"></a>
</form>

<div class="container" style="margin-right: -10px">
    <div class="row">
        <div class="col-md-12" align="right">
            <form action="add_streaming_channel" method="get"
                  style="width: fit-content; display: none;  margin-top: -30px"
                  id="add_streaming_channel_btn">
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 10px; padding-right: 10px"><b>Add Streaming Channel</b></button>
            </form>
        </div>
    </div>
</div>


<div id="datatable" style="display: none;">
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
                            href="{{($streaming_channels[$idx])->url}}">{{($streaming_channels[$idx])->url}}</a></td>
                <td class="center">{{($streaming_channels[$idx])->start_datetime}} @if(is_null(($streaming_channels[$idx])->start_datetime))
                        - @endif </td>
                <td valign="center" class="center" style="width: 1px; height: 96%; padding-top: 2%; padding-bottom: 2%">
                    <form action="update_streaming_channel" method="get">
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="streaming_channel_id" value="{{($streaming_channels[$idx])->id}}"/>
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