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

        function showModal(eventName, packageId, packageName) {
            $("#event_name").text(eventName);
            $("#package_id").val(packageId);
            $("#package_name").text(packageName);
        }

        function selectAll() {
            $('.industry_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
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
{{--<h1 style="margin-left: 1%; font-size: 35px"><b>Manage Sponsorship Package</b></h1>--}}
{{--<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">--}}
{{--<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 50px">{{$event_information}}</h2>--}}


<div class="container-fluid">
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
            <li>
                <form action="manage_news" method="post" style="margin-left: 2px; margin-bottom: -5px" id="manage_news">
                    {{csrf_field()}}
                    <input type="hidden" name="event_id" value="{{$event->id}}"/>
                </form>
                <a href="#" onclick="$('#manage_news').submit()">News</a>
            </li>
            <li class="active">
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

    <div class="row" id="no_package" style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; padding-top: 1%; background-color: white">
        <h1 align="center" style="margin-top: 5%; margin-bottom: 5%"><b>There is no Sponsorship Package for This
                Event</b></h1>

        <form action="add_package" method="post" align="right">
            {{csrf_field()}}
            <input type="hidden" name="event_information" value="{{$event_information}}"/>
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
            <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/ic_add_package.png"
                                                                        style="width: 160px;"></a>
        </form>
    </div>

    <div class="row"
         style="width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; padding-top: 1%; background-color: white; display: none" id="hidden_if_package_is_null">
            <span style="float: right;">
            <form action="add_package" method="post"
                  style="width: 166px;">
                {{csrf_field()}}
                <input type="hidden" name="event_information" value="{{$event_information}}"/>
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px;"><b>Add Package</b></button>
            </form>
                </span>

            <span style="float: right; margin-right: 10px">
                <form action="sponsor_status" method="post">
                {{csrf_field()}}

                    <input type="hidden" name="event_information" value="{{$event_information}}"/>
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <button type="submit" class="form-control btn btn-primary"
                        style="padding-left: 30px; padding-right: 30px; width: fit-content; "
                        onclick="window.location.href='/sponsor_status'"><b>Sponsor
                        Status</b></button>
            </form>
            </span>

        <span style="float: right; margin-right: 10px">
        <button type="submit" class="form-control btn btn-primary"
                style="padding-left: 30px; padding-right: 30px; width: fit-content"
                id="show_sponsor_status_btn" data-toggle="modal" data-target="#searchSponsorModal"><b>Search Sponsor</b></button>
        </span>
    </div>

    <div id="datatable" class="row" style="display:none; width: 98%; padding-left: 1%; padding-right: 1%; margin-left: 1%; margin-right: 1%; background-color: white">
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
                    <td valign="center" class="center"
                        style="width: 1px; height: 96%; padding-top: 2%; padding-bottom: 2%">
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
            </tbody>
        </table>
    </div>
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

<!-- Search Sponsor Modal -->
<div class="container">
    <div class="modal fade" id="searchSponsorModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search for Sponsor</h4>
                </div>
                <form action="sponsor_search_result" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <p style="font-size: 22px"><b>Industry</b></p>
                        <div class="container-fluid" style="margin-bottom: 35px;">

                            <div class="col-sm-4"><input type="checkbox" value="" onchange="selectAll()"
                                                         id="select_all_checkbox"> Select All
                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"></div>
                            @foreach($industries as $industry)
                                <div class="col-sm-4"><input type="checkbox"
                                                             value={{$industry->id}} class="industry_checkbox"
                                                             name="industry_id[]"> {{$industry->name}}
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="event_information" value="{{$event_information}}"/>
                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                               name="keyword">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
    $("#datatable").show();
    $("#hidden_if_package_is_null").show();

    <?php
    }
    ?>
</script>
</body>
</html>