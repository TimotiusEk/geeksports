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
    </script>

    <style>
        li {
            cursor: pointer;
        }
    </style>
</head>
<body>
<h1 style="font-size: 35px; margin-left: 1%; margin-top: 1%"><b>Sponsor Status</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 20px">{{$event_information}}</h2>

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

                                <div class="col-md-4 text-center">
                                    <div class="work-inner">
                                        @if(!is_null(($companies[$industry_idx])->company_logo))
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/company_logo/{{($companies[$industry_idx])->company_logo}}); background-size: contain; background-repeat: no-repeat;"></a>
                                        @else
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                        @endif

                                        <div class="desc">
                                            <h3>
                                                <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}">{{($companies[$industry_idx])->company_name}}</a>
                                            </h3>

                                            <div style="margin-top: -20px">( {{$company_industries[$industry_idx]}})
                                            </div>
                                            @php $industry_idx++; @endphp
                                        </div>
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
                                <div class="col-md-4 text-center">
                                    <div class="work-inner">
                                        @if(!is_null(($companies[$industry_idx])->company_logo))
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/company_logo/{{($companies[$industry_idx])->company_logo}}); background-size: contain; background-repeat: no-repeat;"></a>
                                        @else
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                        @endif

                                        <div class="desc">
                                            <h3>
                                                <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}">{{($companies[$industry_idx])->company_name}}</a>
                                            </h3>

                                            <div>( {{$company_industries[$industry_idx]}})
                                            </div>
                                            @php $industry_idx++; @endphp

                                            <form action="sponsor_status" method="post" style="margin-top: 20px">
                                                {{csrf_field()}}
                                                <input type="hidden" name="event_id"
                                                       value="{{$sponsor_management->event_id}}"/>
                                                <input type="hidden" name="company_id"
                                                       value="{{$sponsor_management->company_id}}"/>
                                                <button type="submit" class="btn btn-primary" name="action"
                                                        value="Deal" style="width: 100%"><b>Deal</b>
                                                </button>
                                            </form>
                                        </div>
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
                                <div class="col-md-4 text-center">
                                    <div class="work-inner">
                                        @if(!is_null(($companies[$industry_idx])->company_logo))
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/company_logo/{{($companies[$industry_idx])->company_logo}}); background-size: contain; background-repeat: no-repeat;"></a>
                                        @else
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                        @endif

                                        <div class="desc">
                                            <h3>
                                                <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}">{{($companies[$industry_idx])->company_name}}</a>
                                            </h3>

                                            <div>( {{$company_industries[$industry_idx]}})
                                            </div>
                                            @php $industry_idx++; @endphp
                                        </div>
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
                                <div class="col-md-4 text-center">
                                    <div class="work-inner">
                                        @if(!is_null(($companies[$industry_idx])->company_logo))
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/company_logo/{{($companies[$industry_idx])->company_logo}}); background-size: contain; background-repeat: no-repeat;"></a>
                                        @else
                                            <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}"
                                               class="work-grid"
                                               style="background-image: url(/images/default_event_img.png); background-size: contain; background-repeat: no-repeat;"></a>
                                        @endif

                                        <div class="desc">
                                            <h3>
                                                <a href="/view_profile?user_id={{($companies[$industry_idx])->user_id}}">{{($companies[$industry_idx])->company_name}}</a>
                                            </h3>

                                            <div>( {{$company_industries[$industry_idx]}})
                                            </div>
                                            @php $industry_idx++; @endphp
                                        </div>
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