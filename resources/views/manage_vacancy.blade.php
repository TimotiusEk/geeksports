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
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Roles Vacancy</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>
<h1 align="center" style="margin-top: 15%" id="no_vacancy"><b>There is no Roles Vacancy for This Event</b></h1>

<form action="add_vacancy" method="post">
    {{csrf_field()}}
    <input type="hidden" name="event_information" value="{{$event_information}}"/>
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <a href="#" id="add_vacancy" onclick="$(this).closest('form').submit()"><img src="/images/ic_add_vacancy.png"
                                                                                 style="width: 160px; margin-right: 1.5%;  position:absolute; right:0; margin-top: 13%;"></a>
</form>

<div class="container">
    <div class="row">
        <div class="col-md-11"></div>
        <div class="col-md-1" align="right">
            <form action="vacancy_status" method="post">
                {{csrf_field()}}
                <input type="hidden" name="event_information" value="{{$event_information}}"/>
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px; display: none; width: fit-content; margin-right: 1%"
                        id="show_vacancy_status_btn"><b>Vacancy Status</b></button>
            </form>
        </div>
    </div>
</div>


@if($vacancies != null)
    <div id="datatable" style="display: none; margin-left: 10%; margin-right: 10%; margin-top: 1%">
        <table id="table_id" class="cell-border table-bordered">
            <thead>
            <tr>
                <th>Roles</th>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="center">{{$vacant_roles}}</td>
                <td>
                    <pre style="font-size: 17px; background-color: white; border-width: 0px; font-family: 'Arial'">{{$vacancies->description}}</pre>
                </td>
                <td class="center">
                    <form action="update_vacancy" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="event_information" value="{{$event_information}}"/>
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="vacant_roles" value="{{$vacant_roles}}"/>
                        <input type="hidden" name="description" value="{{$vacancies->description}}"/>
                        <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_edit.png"
                                                                                    style="width: 60px; height: 40px; margin-top: 10px"/></a>
                    </form>
                    <br>
                    <a href="#myModal" onclick="showModal('{{$event_information}}', '{{$event_id}}')"
                       data-toggle="modal"><img src="/images/ic_delete.png"
                                                style="width: 65px; height: 45px; margin-bottom: 10px;"/></a><br></td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

<!-- Delete Vacancy MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <form action="delete_vacancy" method="post">
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
                    <input type="hidden" name="event_information" value="{{$event_information}}"/>
                    Are you sure you want to delete vacancy for "<b><span id="event_name"></span></b>" event?
                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-danger">Delete</button>

                    <button class="btn btn-primary" data-dismiss="modal">Cancel</button>
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
                "targets": '_all',
                "orderable": false
            }],
            "paging": false,
            "searching": false,
            "info": false
        });

        $("th").addClass("dt-head-center");
        $("td.center").addClass("dt-body-center");
    });


    <?php
    if($vacancies != null){
    ?>
    $("#no_vacancy").hide();
    $("#add_vacancy").hide();

    $("#datatable").show();
    $("#show_vacancy_status_btn").show();
    <?php
    }
    ?>
</script>
</body>
</html>
