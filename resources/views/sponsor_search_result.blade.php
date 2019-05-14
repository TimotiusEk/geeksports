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

        function selectAll() {
            $('.industry_checkbox').prop('checked', $("#select_all_checkbox").is(":checked"));
        }
    </script>
</head>
<body>
<!--todo: add event id-->
<h1 style="margin-left: 1%; font-size: 35px"><b>Search Result (Sponsor)</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 25px">{{$event_information}}</h2>
<h2 style="margin-left: 1%; font-size: 25px">Industry
    : @if(count($industry_name) != 0)@for($idx = 0 ; $idx < count($industry_name) ; $idx++) {{$industry_name[$idx]}} @if($idx != count($industry_name) - 1) {{"-"}}@endif @endfor @else {{"-"}} @endif</h2>
<h2 style="margin-left: 1%; font-size: 25px">Keyword:
    @if($keyword != ""){{$keyword}} @else{{"-"}}@endif

    <div align="right" style="margin-right: 20px; margin-top: -40px">
        @php $user_idx = 0; @endphp
        @foreach($status as $s)
            @if($s == null)
                <form action="broadcast_package" method="post" style="margin-bottom: -33px;margin-right: 170px">
                    {{csrf_field()}}

                    @foreach($companies_collection as $companies)

                        @for($idx = 0 ; $idx < count($companies) ; $idx++)
                            @if($status[$user_idx] == null)
                                <input type="hidden" name="company_id[]" value="{{($companies[$idx])->id}}"/>
                                @php $user_idx++; @endphp
                            @endif
                        @endfor
                    @endforeach

                    @if(is_array($industry_id) && count($industry_id) != 0)
                        @foreach($industry_id as $id )
                            <input type="hidden" name="industry_id[]" value="{{$id}}"/>
                        @endforeach
                    @endif

                    @if($keyword != "")
                        <input type="hidden" name="keyword" value="{{$keyword}}"/>
                    @endif

                    <input type="hidden" name="event_id" value="{{$event_id}}"/>



                    <button type="submit" class="btn btn-primary text-right"
                            style="padding-left: 1%; padding-right: 1%; width: 150px">
                        <b>Broadcast to All</b></button>
                </form>
                @break
            @endif
        @endforeach

        <button type="submit" class="form-control btn btn-primary"
                style="padding-left: 30px; padding-right: 30px; width: fit-content"
                id="show_sponsor_status_btn" data-toggle="modal" data-target="#myModal"><b>Change Filter</b>
        </button>
    </div>
</h2>

<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>

{{--<div align="right" style="margin-right: 20px;">--}}

{{--</div>--}}

<table style="width: 98%; margin-left: 1%; margin-right: 1%; margin-top: 2%">
    <tr>
        <td>
            <div class="container">
                <div class="row">
                    @php $industry_idx = 0;@endphp
                    @php $status_idx = 0;@endphp
                    @foreach($companies_collection as $companies)
                        @for($idx = 0 ; $idx < count($companies) ; $idx++)

                            <div class="col-md-2"
                                 style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 500px; margin: 1%; padding: 1%">
                                <div align="center">
                                    <a href="view_profile?user_id={{($companies[$idx])->user_id}}">
                                        <img src="/images/company_logo/{{($companies[$idx])->company_logo}}"
                                             width="100px" height="100px"/>
                                    </a>
                                </div>
                                <div align="center" style="font-size: 22px; margin-top: 10px;">
                                    <b><a href="view_profile?user_id={{($companies[$idx])->user_id}}"
                                          style="color: black">{{($companies[$idx])->company_name}}</a></b>

                                </div>

                                <div align="center" style="font-size: 15px; margin-top: 5px;">
                                    @if(count($industries) != 0)
                                        {{$industries[$industry_idx]}}
                                        @php $industry_idx++; @endphp
                                    @endif
                                </div>

                                <div align="center" style="margin-top: 30px">
                                    <form action="/broadcast_package" method="post">
                                        {{csrf_field()}}

                                        @if(is_array($industry_id) && count($industry_id) != 0)
                                            @foreach($industry_id as $id )
                                                <input type="hidden" name="industry_id[]" value="{{$id}}"/>
                                            @endforeach
                                        @endif

                                        @if($keyword != "")
                                            <input type="hidden" name="keyword" value="{{$keyword}}"/>
                                        @endif
                                        <input type="hidden" name="company_id"
                                               value="{{($companies[$idx])->id}}"/>
                                        <input type="hidden" name="event_id" value="{{$event_id}}"/>

                                        @if($status[$status_idx] == null)
                                            <button type="submit" class="form-control btn btn-primary"><b>Broadcast
                                                    Package</b>
                                            </button>
                                        @else
                                            <button type="submit" class="form-control btn btn-dark" disabled><b>Broadcasted</b>
                                            </button>
                                        @endif
                                    </form>
                                    @php $status_idx++; @endphp

                                </div>
                            </div>

                        @endfor
                    @endforeach
                </div>
            </div>

        </td>
    </tr>
</table>

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
                            @foreach($all_industries as $industry)
                                <div class="col-sm-4"><input type="checkbox"
                                                             value={{$industry->id}} class="industry_checkbox"
                                                             name="industry_id[]"
                                                             @for($idx = 0 ; $idx < count($industry_name) ; $idx++) @if($industry->name == $industry_name[$idx]) checked @endif @endfor> {{$industry->name}}
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                               name="keyword" value="{{$keyword}}">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
