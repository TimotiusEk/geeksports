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

        function showModal(eventName, packageId, packageName) {
            $("#event_name").text(eventName);
            $("#package_id").val(packageId);
            $("#package_name").text(packageName);
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
<h1 style="margin-left: 1%; font-size: 35px"><b>Manage Sponsorship Package</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 50px">{{$event_information}}</h2>
<h1 align="center" style="margin-top: 15%" id="no_package"><b>There is no Sponsorship Package for This Event</b></h1>

<form action="add_package" method="post">
    {{csrf_field()}}
    <input type="hidden" name="event_information" value="{{$event_information}}"/>
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <a href="#" id="add_package" onclick="$(this).closest('form').submit()"><img src="/images/ic_add_package.png"
                                                                                 style="width: 160px; margin-right: 1.5%; position:absolute; right:0; margin-top: 9%;"></a>
</form>

<div class="container">
    <div class="row" style="margin-right: 60px">
        <div class="col-md-9"></div>
        <div class="col-md-2" align="right">
            <form action="add_package" method="post"
                  style="width: 166px; display: none; "
                  id="add_package_btn">
                {{csrf_field()}}
                <input type="hidden" name="event_information" value="{{$event_information}}"/>
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px;"><b>Add Package</b></button>
            </form>
        </div>
        <div class="col-md-1" align="right">
            <form action="sponsor_status" method="post"
                  id="add_package_btn">
                {{csrf_field()}}

                <input type="hidden" name="event_information" value="{{$event_information}}"/>
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px; display: none; width: fit-content; "
                        id="show_sponsor_status_btn" onclick="window.location.href='/sponsor_status'"><b>Sponsor
                        Status</b></button>
            </form>
        </div>
    </div>
</div>


<div id="datatable" style="display: none;">
    <table id="table_id" class="cell-border table-bordered responsive" style="width: 99%">
        <thead>
        <tr>
            <th>No</th>
            <th>Package Name</th>
            <th>Sponsor Rights</th>
            <th>Sponsor Obligations</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @for($idx = 0 ; $idx < count($packages) ; $idx++)
            <tr>
                <td class="center">{{$idx+1}}</td>
                <td class="center">{{($packages[$idx])->package_name}} </td>
                <td valign="top">
                    <pre style="font-size: 17px; background-color: white; border-width: 0px; font-family: 'Arial'; width: fit-content">{{($packages[$idx])->sponsor_rights}}</pre>
                </td>
                <td valign="top">
                    <pre style="font-size: 17px; background-color: white; border-width: 0px; font-family: 'Arial'; width: fit-content ">{{($packages[$idx])->sponsor_obligations}}</pre>
                </td>
                <td valign="center" class="center" style="width: 1px; height: 96%; padding-top: 2%; padding-bottom: 2%">
                    <form action="update_package" method="post">
                        <input type="hidden" name="event_information" value="{{$event_information}}"/>
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="package_id" value="{{($packages[$idx])->id}}"/>
                        {{csrf_field()}}
                        <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                    style="width: 60px; height: 40px;"/></a>
                    </form>
                    <br>
                    <a href="#myModal"
                       onclick="showModal('{{$event_information}}', '{{($packages[$idx])->id}}', '{{($packages[$idx])->name}}')"
                       data-toggle="modal"><img src="/images/ic_delete.png" style="width: 65px; height: 45px;"/><br></a>
                </td>
            </tr>
            @endfor
            </tr>
        </tbody>
    </table>
</div>


<!-- Delete Event MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <form action="delete_package" method="post">
        {{csrf_field()}}
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="package_id" id="package_id"/>
                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    <input type="hidden" name="event_information" value="{{$event_information}}"/>
                    Are you sure you want to delete <b><span id="package_name"></span></b> package in "<b><span
                                id="event_name"></span></b>" event?
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
                "targets": -1,
                "orderable": false
            },]

        });

        $("th").addClass("dt-head-center");
        $("td.center").addClass("dt-body-center");
    });

    <?php
    if(count($packages) != 0){
    ?>
    $("#no_package").hide();
    $("#add_package").hide();
    $("#datatable").show();
    $("#show_sponsor_status_btn").show();
    $("#add_package_btn").show();

    <?php
    }
    ?>
</script>
</body>
</html>