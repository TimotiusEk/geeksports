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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
</head>
<body>

<h1 style="margin-left: 1%; font-size: 35px"><b>Search Result (Gamer)</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%"/>
<h2 style="margin-left: 1%; font-size: 30px; margin-bottom: 25px">{{$event_information}}</h2>

<h2 style="margin-left: 1%; font-size: 25px">Game <span
            style="margin-left: 22px">:</span> @if(count($game_name) != 0) @for($idx = 0 ; $idx < count($game_name) ; $idx++) {{$game_name[$idx]}} @if($idx != count($game_name) - 1) {{","}}@endif @endfor @else {{"-"}} @endif
</h2>
<h2 style="margin-left: 1%; font-size: 25px">Keyword: @if($keyword != ""){{$keyword}} @else{{"-"}}@endif
    <div align="right" style="margin-right: 20px">
        <button type="submit" class="form-control btn btn-primary"
                style="width: 170px; margin-top: -40px" data-toggle="modal"
                data-target="#myModal">
            <b>Change Filter</b>
        </button>
    </div>
</h2>

<hr style="height:0.5px;border:none;color:#333;background-color:#333; width: 99%"/>

<!-- Search gamer modal-->
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search Gamer</h4>
                </div>
                <form action="gamer_search_result" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 5px">
                            <b><label>Games</label></b><br>
                            <select class="cari form-control" name="game_id[]" multiple="multiple" id="mySelect2"
                                    style="width: 567px">
                                @for($idx = 0 ; $idx < count($game_name) ; $idx++)
                                    <option value="{{$game_id[$idx]}}" selected>{{$game_name[$idx]}}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <b><label>Keyword</label></b><br>
                            <input type="text" class="form-control" placeholder="Type your keyword (Optional)"
                                   name="keyword" value="{{$keyword}}">
                        </div>

                        <input type="hidden" name="event_id" value="{{$event_id}}"/>
                        <input type="hidden" name="event_information" value="{{$event_information}}"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--check if all search result has been invited-->
@foreach($status as $s)
    @if($s == null)
        <form action="invite_gamer" method="post">
            {{csrf_field()}}
            <div align="right" style="margin-right: 20px">
                @for($user_idx = 0 ; $user_idx < count($user_id); $user_idx++)
                    @if($status[$user_idx] == null)
                        <input type="hidden" name="user_id[]" value="{{$user_id[$user_idx]}}"/>
                    @endif
                @endfor
                <input type="hidden" name="event_id" value="{{$event_id}}"/>
                <input type="hidden" name="event_information" value="{{$event_information}}"/>

                @if(is_array($game_id) && count($game_id) != 0)
                    @foreach($game_id as $id)
                        <input type="hidden" name="game_id[]"
                               value="{{$id}}"/>
                    @endforeach
                @endif

                @if($keyword != "")
                    <input type="hidden" name="keyword" value="{{$keyword}}"/>
                @endif
                <button type="submit" class="btn btn-primary text-right" style="padding-left: 1%; padding-right: 1%;">
                    <b>Invite All</b></button>
            </div>
            <br>
        </form>
        @break
    @endif
@endforeach

<table style="margin-left: 1%; margin-right:1%;width: 98%">
    <tr>
        <td>
            <div class="container">
                <div class="row">

                    @for($idx = 0 ; $idx < count($display_names) ; $idx++)
                        <div class="col-md-2"
                             style="background-color: #eaffea; border-radius: 5px; border: 1px outset #18253d;width: 561px; padding: 1%; margin: 1%;">
                            <div align="center">
                                <a href="/view_profile?user_id={{$user_id[$idx]}}">
                                    <img src="/images/profile_picture/{{$profile_picture[$idx]}}" width="100px" height="100px"
                                         class="img-circle"/>
                                </a>
                            </div>
                            <div align="center" style="font-size: 20px; margin-top: 10px;">
                                <b><a href="/view_profile?user_id={{$user_id[$idx]}}"
                                      style="color: black">{{$display_names[$idx]}}</a></b>
                            </div>

                            <div align="center" style="font-size: 18px; margin-top: 5px;">
                                ({{$user_games[$idx]}})
                            </div>
                            <div align="center" style="margin-top: 30px">
                                <form method="post" action="invite_gamer">
                                    {{csrf_field()}}
                                    @if($status[$idx] == "Invite")
                                        <button class="form-control btn btn-dark" disabled><b>Invited</b>
                                        </button>
                                    @elseif($status[$idx] == "Register")
                                        <button class="form-control btn btn-dark" disabled><b>Registered</b>
                                        </button>
                                    @else
                                        <button class="form-control btn btn-primary"><b>Invite to Event</b>
                                        </button>
                                    @endif
                                    <input type="hidden" name="user_id" value="{{$user_id[$idx]}}"/>
                                    <input type="hidden" name="event_id" value="{{$event_id}}"/>
                                    <input type="hidden" name="event_information" value="{{$event_information}}"/>

                                    @if(is_array($game_id) && count($game_id) != 0)
                                        @foreach($game_id as $id)
                                            <input type="hidden" name="game_id[]"
                                                   value="{{$id}}"/>
                                        @endforeach
                                    @endif

                                    @if($keyword != "")
                                        <input type="hidden" name="keyword" value="{{$keyword}}"/>
                                    @endif


                                </form>
                            </div>
                        </div>

                    @endfor
                </div>
            </div>
        </td>
    </tr>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $('.cari').select2({
        placeholder: 'Type its Name',
        ajax: {
            url: '/search_game',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
</body>
</html>
