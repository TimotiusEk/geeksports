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

        function selectAll() {
            $('.industry_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
        }
    </script>

    <style>
        li {
            cursor: pointer;
        }
    </style>
</head>
<body>
<h1 style="font-size: 35px; margin-left: 1%;"><b>Sponsor Status</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 20px">{{$event_information}}</h2>

<button type="submit" class="form-control btn btn-primary"
        style="position: relative; left: 88%; padding-left: 30px; padding-right: 30px; width: fit-content"
        id="show_sponsor_status_btn" data-toggle="modal" data-target="#myModal"><b>Search Sponsor</b></button>


<ul class="nav nav-tabs" style="margin-left: 15px; margin-right: 15px">
    <li class="active" id="deal"><a>Deal</a></li>
    <li id="interested"><a>Interested</a></li>
    <li id="not_interested"><a>Not Interested</a></li>
    <li id="unanswered"><a>Unanswered</a></li>
</ul>
<br>
@php $industry_idx = 0; @endphp
<div id="deal_companies">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">

                        @foreach($sponsor_managements as $sponsor_management)
                            @if($sponsor_management->action == "Deal")


                                <div class="col-md-2"
                                     style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                    <div align="center">
                                        <img src="/images/company_logo/{{($companies[$industry_idx])->company_logo}}"
                                             width="100px"
                                             height="100px"/>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        <b>{{($companies[$industry_idx])->company_name}}</b>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        {{$company_industries[$industry_idx]}}
                                        @php $industry_idx++; @endphp
                                    </div>
                                </div>

                            @endif
                        @endforeach


                    </div>
            </td>
        </tr>
    </table>
</div>

<div id="interested_companies" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">

                        @foreach($sponsor_managements as $sponsor_management)
                            @if($sponsor_management->action == "Interested")
                                <div class="col-md-2"
                                     style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                    <div align="center">
                                        <img src="/images/company_logo/{{($companies[$industry_idx])->company_logo}}"
                                             width="100px" height="100px"/>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        <b>{{($companies[$industry_idx])->company_name}}</b>
                                    </div>

                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        {{$company_industries[$industry_idx]}}
                                        @php $industry_idx++; @endphp
                                    </div>
                                    <div align="center" style="margin-top: 30px">
                                        <form action="sponsor_status" method="post">
                                            {{csrf_field()}}
                                            <input type="hidden" name="event_id"
                                                   value="{{$sponsor_management->event_id}}"/>
                                            <input type="hidden" name="company_id"
                                                   value="{{$sponsor_management->company_id}}"/>
                                            <input type="hidden" name="event_information"
                                                   value="{{$event_information}}"/>
                                            <button type="submit" class="form-control btn btn-primary" name="action"
                                                    value="Deal"><b>Deal</b>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="not_interested_companies" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($sponsor_managements as $sponsor_management)
                            @if($sponsor_management->action == "Not Interested")
                                <div class="col-md-2"
                                     style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                    <div align="center">
                                        <img src="/images/company_logo/{{($companies[$industry_idx])->company_logo}}"
                                             width="100px"
                                             height="100px"/>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        <b>{{($companies[$industry_idx])->company_name}}</b>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        {{$company_industries[$industry_idx]}}
                                        @php $industry_idx++; @endphp
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="unanswered_companies" style="display: none">
    <table style="margin: 1%;">
        <tr>
            <td>
                <div class="container">
                    <div class="row">
                        @foreach($sponsor_managements as $sponsor_management)
                            @if($sponsor_management->action == "Invite")
                                <div class="col-md-2"
                                     style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 350px; margin: 1%; padding: 1%">
                                    <div align="center">
                                        <img src="/images/company_logo/{{($companies[$industry_idx])->company_logo}}"
                                             width="100px"
                                             height="100px"/>
                                    </div>
                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        <b>{{($companies[$industry_idx])->company_name}}</b>
                                    </div>

                                    <div align="center" style="font-size: 20px; margin-top: 10px;">
                                        {{$company_industries[$industry_idx]}}
                                        @php $industry_idx++; @endphp
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>


                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Search Sponsor Modal -->
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
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
    $("#deal").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#interested").removeClass("active");
            $("#not_interested").removeClass("active");
            $("#unanswered").removeClass("active");

            $("#deal_companies").show();
            $("#interested_companies").hide();
            $("#not_interested_companies").hide();
            $("#unanswered_companies").hide();
        }
    });

    $("#interested").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#deal").removeClass("active");
            $("#not_interested").removeClass("active");
            $("#unanswered").removeClass("active");

            $("#deal_companies").hide();
            $("#interested_companies").show();
            $("#not_interested_companies").hide();
            $("#unanswered_companies").hide();
        }
    });
    $("#not_interested").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#deal").removeClass("active");
            $("#interested").removeClass("active");
            $("#unanswered").removeClass("active");

            $("#deal_companies").hide();
            $("#interested_companies").hide();
            $("#not_interested_companies").show();
            $("#unanswered_companies").hide();
        }
    });
    $("#unanswered").click(function () {
        if ($(this).attr('class') != "active") {
            $(this).addClass("active");
            $("#deal").removeClass("active");
            $("#interested").removeClass("active");
            $("#not_interested").removeClass("active");

            $("#deal_companies").hide();
            $("#interested_companies").hide();
            $("#not_interested_companies").hide();
            $("#unanswered_companies").show();
        }
    });
</script>
</body>
</html>