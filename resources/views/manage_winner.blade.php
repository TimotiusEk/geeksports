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

        function showModal(eventName, winnerId, packageName) {
            $("#event_name").text(eventName);
            $("#winner_id").val(winnerId);
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
<h1 style="margin-left: 1%; font-size: 35px"><b>Manage Winner</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 50px">{{$event_information}}</h2>
<h1 align="center" style="margin-top: 15%" id="no_winner"><b>There is still no Winner for This Event</b></h1>

<form action="add_winner" method="get">
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <a href="#" id="add_winner" onclick="$(this).closest('form').submit()"><img src="/images/ic_add_winner.png"
                                                                                style="width: 140px; margin-right: 1.5%; position:absolute; right:0; margin-top: 13%;"></a>
</form>
<span>
    <form action="add_winner" method="get"
          style="width: 166px; position: relative; left: 87%; display: none;  margin-top: -50px"
          id="add_winner_btn">
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
        <button type="submit" class="form-control btn btn-primary" style="padding-left: 30px; padding-right: 30px;"><b>Add Winner</b></button>
        </form>
</span>

<div id="datatable" style="display: none;">
    <table id="table_id" class="cell-border table-bordered responsive" style="width: 99%">
        <thead>
        <tr>
            <th>No</th>
            <th>Win Type</th>
            <th>Team Name</th>
            <th>Game</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @for($idx = 0 ; $idx < count($winners) ; $idx++)
            <tr>
                <td class="center">{{$idx+1}}</td>
                <td class="center">{{($winners[$idx])->win_type}} </td>
                <td class="center">{{($winners[$idx])->team_name}} </td>
                <td class="center">{{($winners[$idx])->game_name}} </td>
                <td valign="center" class="center" style="width: 1px; height: 96%; padding-top: 2%; padding-bottom: 2%">
                    <form action="update_winner" method="get">
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="winner_id" value="{{($winners[$idx])->id}}"/>
                        <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                    style="width: 60px; height: 40px;"/></a>
                    </form>
                    <br>
                    <a href="#myModal"
                       onclick="showModal('{{$event_information}}', '{{($winners[$idx])->id}}', '{{($winners[$idx])->team_name}}')"
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
    <form action="delete_winner" method="post">
        {{csrf_field()}}
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="winner_id" id="winner_id"/>
                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    Are you sure you want to delete Team <b><span id="team_name"></span></b> as a winner in "<b><span
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
    if(count($winners) != 0){
    ?>
    $("#no_winner").hide();
    $("#add_winner").hide();
    $("#datatable").show();
    $("#add_winner_btn").show();

    <?php
    }
    ?>
</script>
</body>
</html>