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

        function showModal(companyId){
            $("#company_id").val(companyId);
        }
    </script>
</head>
<body>
<!--todo: add event id-->
<h1 style="margin-left: 1%; font-size: 35px; margin-top: 1%"><b>Search Result (Sponsor)</b></h1>
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
                <form action="broadcast_package" method="post" style="margin-bottom: -50px;margin-right: 170px">
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
                        <b>Broadcast All</b></button>
                </form>
                @break
            @endif
        @endforeach

        <button type="submit" class="btn btn-primary"
                style="padding-left: 30px; padding-right: 30px; width: fit-content"
                id="show_sponsor_status_btn" data-toggle="modal" data-target="#myModal"><b>Change Filter</b>
        </button>
    </div>
</h2>

<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>

<div class="row">
    @php $industry_idx = 0;@endphp
    @php $status_idx = 0;@endphp
    @foreach($companies_collection as $companies)
        @for($idx = 0 ; $idx < count($companies) ; $idx++)
            <div class="col-md-4 text-center">
                <div class="work-inner">
                    @if(!is_null(($companies[$idx])->company_logo))
                        <a href="#" class="work-grid"
                           style="background-image: url(/images/company_logo/{{($companies[$idx])->company_logo}}); background-size: contain; background-repeat: no-repeat;"> </a>
                    @else
                        <a href="#" class="work-grid"
                           style="background-image: url(/images/default_logo.png); background-size: contain; background-repeat: no-repeat;"> </a>
                    @endif

                    <div class="desc">
                        <h3><a href="#">{{($companies[$idx])->company_name}}</a></h3>
                        <span>
                            @if(count($industries) != 0)
                                {{$industries[$industry_idx]}}
                                @php $industry_idx++; @endphp
                            @endif
                        </span>

                        @if($status[$status_idx] == null)
                            <button class="btn btn-primary" style="width: 100%; margin-top: 30px" data-toggle="modal" data-target="#sendProposalModal" onclick="showModal({{($companies[$idx])->id}})"><b>Send Proposal</b>
                            </button>
                            <form action="/broadcast_package" method="post" style="">
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


                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 3px">
                                    <b>Broadcast
                                        Package</b>
                                </button>

                            </form>
                        @else
                            <button class="form-control btn btn-dark" disabled style="margin-top: 91px">
                                <b>Broadcasted</b>
                            </button>
                        @endif
                        @php $status_idx++; @endphp

                    </div>
                </div>
            </div>
        @endfor
    @endforeach
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

<!-- Send Proposal Modal -->
<div class="container">
    <div class="modal fade" id="sendProposalModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send Proposal</h4>
                </div>
                <form action="send_proposal" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <input type="file" name="proposal" required/>
                        <input type="hidden" name="company_id" id="company_id"/>

                        @if(is_array($industry_id) && count($industry_id) != 0)
                            @foreach($industry_id as $id )
                                <input type="hidden" name="industry_id[]" value="{{$id}}"/>
                            @endforeach
                        @endif

                        @if($keyword != "")
                            <input type="hidden" name="keyword" value="{{$keyword}}"/>
                        @endif

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
