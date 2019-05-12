<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <style>
        button {
            width: fit-content
        }

        td.participated_team {
            cursor: pointer
        }

        pre {
            white-space: pre-wrap;
            word-break: keep-all;
        }
    </style>

    <script>
        function expand_description(id) {
            if ($("#description_btn_" + id).hasClass(("down"))) {
                $("#description_btn_" + id).removeClass("down");
                $("#description_btn_" + id).addClass("up");
                $("#description_" + id).show();
            } else {
                $("#description_btn_" + id).removeClass("up");
                $("#description_btn_" + id).addClass("down");
                $("#description_" + id).hide();
            }
        }

        $(document).ready(function () {
            $("#common").attr("disabled", "disabled");
        });

    </script>

    <style>
        i {
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 3px;
            cursor: pointer;
        }

        .up {
            transform: rotate(-135deg);
            -webkit-transform: rotate(-135deg);
        }

        .down {
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
        }

        textarea {
            resize: none;
        }
    </style>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="/themes/fontawesome-stars.css">
    <script src="/js/jquery.barrating.js"></script>


    <script type="text/javascript">
        $(function () {
            $('#event_rating').barrating({
                theme: 'fontawesome-stars',
                'readonly': true
            });
        });


        $(function () {
            $('#user_rating').barrating({
                theme: 'fontawesome-stars'
            });
        });
    </script>


</head>
<body style="background-color: whitesmoke">
<div class="container" style="background-color: white; border-radius: 10px; width: 99%;">
    <div class="row">
        <div class="col-md-4 text-center" style="margin-top: 25px"><img
                    style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px;"
                    src="/images/event_brochure/{{$event->brochure}}"/></div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <div class="row" style="margin-bottom: -20px">
                    <div class="col-md-7">
                        <p style="margin-top: 30px;"><b>{{$event->name}}</b></p>
                        <p style="font-size: 18px; margin-left: 5px; margin-top: -10px">By: <a
                                    href="/view_profile?user_id={{($event->event_organizer)->user_id}}">{{($event->event_organizer)->display_name}}</a>
                        </p>
                        @if($is_register == false && $event->participant_registration == 1)
                            <form action="participant_registration_form" method="get">

                                <a href="#" onclick="$(this).closest('form').submit()">
                                    <img src="images/register_btn.png" width="130px"/>
                                </a>

                                <input type="hidden" name="event_id" value="{{$event->id}}"/>

                            </form>
                        @endif
                    </div>
                    <div class="col-md-5" style="margin-top: 30px;" align="right">
                        <select id="event_rating">
                            <option value=""></option>

                            @for($no = 1 ; $no < 6 ; $no++)
                                <option value="{{$no}}" @if($event->rating == $no) selected @endif>{{$no}}</option>
                            @endfor
                        </select>
                        <p style="font-size: 12px; margin-top: 20px;">
                            <b>( {{$event->no_of_raters}} @if($event->no_of_raters > 1) users @else user @endif )</b>
                        </p>
                    </div>
                </div>
                <hr>


                <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_location.png"
                                                                       style="width: 30px;"> {{$city}} </div>
                <div style="font-size: 23px; margin-bottom: 10px"><img src="images/ic_datetime.png"
                                                                       style="width: 30px;"> {{$event->start_date}}
                    - {{$event->end_date}}
                </div>
                <div style="font-size: 23px; margin-bottom: 15px "><img src="images/ic_game.png"
                                                                        style="width: 30px; margin-right: 4px; margin-top: -5px">
                    {{$game_name}}
                </div>
                @if(!is_null($event->details))
                    <div style="background-color: #f4f4f4; font-size: 23px; padding: 2%; margin: 2%">
                        <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial';">{{$event->details}}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@foreach($participated_teams as $participated_team)
    @if(count($participated_team->teams) != 0)
        <div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
            <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Participated Teams
                    ({{$participated_team->game_name}})</b></p>
            <hr style="width: 95%">

            <table align="center" style="margin-bottom: 45px; margin-top: 2%">
                @for($idx = 0 ; $idx < count($participated_team->teams); $idx++)
                    @if($idx % 5 == 0)
                        <tr>
                            @endif
                            <td align="center" style="padding-right: 110px" data-toggle="modal" data-target="#myModal"
                                class="participated_team">
                                <div><img src="/images/sample_team_logo_1.png" style="height: 110px; width: 110px;"/>
                                </div>
                                <div style="margin-top: 10px; font-size: 20px">
                                    <b>{{(($participated_team->teams)[$idx])->team_name}}</b></div>
                            </td>
                            @if($idx == count($participated_team->teams) - 1|| $idx % 5 == 4)
                        </tr>
                    @endif
                @endfor
            </table>
        </div>
    @endif
@endforeach

@if(count($deal_sponsors) != 0)
    <div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Sponsors</b></p>
        <hr>

        <table align="center" style="margin-bottom: 45px; margin-top: 2%">
            <tr>
                @foreach($deal_sponsors as $deal_sponsor)
                    <td align="center" style="padding-right: 110px">
                        <div><img src="/images/company_logo/{{$deal_sponsor->company_logo}}"
                                  style="height: 110px; width: 110px;"/></div>
                        <div style="margin-top: 10px; font-size: 20px"><b>{{$deal_sponsor->company_name}}</b></div>
                    </td>
                @endforeach
            </tr>

        </table>
    </div>
@endif

@if(count($streaming_channels) != 0)
    <div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Streaming Channels</b></p>
        <hr>

        <table align="center" style="margin-bottom: 30px;">
            <tr>
                @foreach($streaming_channels as $streaming_channel)
                    <td align="center" style="padding-right: 110px">
                        <a href="{{$streaming_channel->url}}" style="color: black">
                            <div>
                                <div>
                                    @if($streaming_channel->platform == "youtube")
                                        <img src="/images/youtube_logo.png" style="height: 70px; width: 70px;"/>
                                    @elseif($streaming_channel->platform == "twitch")
                                        <img src="/images/twitch_logo.png" style="height: 70px; width: 70px;"/>
                                    @endif
                                </div>

                                <div style=" font-size: 20px"><b>{{$streaming_channel->title}}</b></div>
                                <div style="margin-top: 2px; font-size: 15px">{{$streaming_channel->start_datetime}}</div>
                            </div>
                        </a>
                    </td>


                    {{--<td align="center" style="padding-right: 110px;">--}}
                    {{--<div><img src="/images/youtube_logo.png" style="height: 70px; width: 70px;"/></div>--}}
                    {{--<div style="font-size: 20px"><b>Team Liquid vs Cloud 9</b></div>--}}
                    {{--<div style="margin-top: 2px; font-size: 15px">April 4, 2019, 13:00</div>--}}
                    {{--</td>--}}
                @endforeach
            </tr>

        </table>
    </div>
@endif

<!-- NEWS SECTION -->
@if(count($news) != 0)
    <div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Recent News</b></p>
        <hr>

        @foreach($news as $a_news)
            <div class="container"
                 style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img
                                style=" height: 225px; width: 410px; border-radius: 10px; margin-left: -35px; margin-top: 50px;"
                                src="/images/news_header/{{$a_news->header_image}}"/></div>
                    <div class="col-md-8" style="font-size: 40px; vertical-align: top; margin-top: 40px">
                        <div style="margin-left: 70px">
                            <p style="font-size: 14px;">{{$a_news->published_on}}</p>
                            <p style="margin-top: -15px"><b>{{$a_news->title}}</b>
                            </p>
                            <hr>
                            <pre style="font-size: 20px; background-color: white; border-width: 0px; font-family: 'Arial';"
                                 class="text-justify">{{$a_news->content}}
                        </pre>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="read_news">
                            <input type="hidden" name="news_id" value="{{$a_news->id}}"/>
                            <a href="#" onclick="$(this).closest('form').submit()"><img src="/images/read_more_btn.png"
                                                                                        width="150px"
                                                                                        align="right"/></a>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endif

<div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
    <div class="row" style="padding-top: 25px; padding-bottom: 5px; padding-left: 10px;">
        <p style="margin-left: 10px; font-size: 40px;"><b>Rate this Event</b></p>
        <form action="rate_event" method="post" align="center">
            {{csrf_field()}}
            <hr style="width: 95%; margin-bottom: 30px">

            <input type="hidden" name="event_id" value="{{$event->id}}"/>

            <select id="user_rating" name="rating">
                <option value=""></option>

                @for($val=1; $val <6 ; $val++)
                    <option value="{{$val}}" @if($val == $user_rating) selected @endif>{{$val}}</option>
                @endfor

            </select>

            <button class="btn btn-primary" style="margin-top: 35px; padding-left: 30px; padding-right: 30px">
                <b>Submit</b></button>


            {{--<div class="col-md-7" align="center">--}}

            {{--</div>--}}
            {{--<div class="col-md-5" align="center">--}}

            {{--</div>--}}
        </form>
    </div>
    <hr style="width: 95%">
    {{--<hr style="width: 95%">--}}
</div>

<!--COMMENT SECTION-->
<div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
    <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Comments</b></p>
    <hr>
    <form action="post_comment" method="post">
        {{csrf_field()}}
        <input type="hidden" name="event_id" value="{{$event->id}}"/>
        <div class="container"
             style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%">
            <div class="row">
                <div class="col-md-12" style="font-size: 20px; vertical-align: top;">

                <textarea rows="3" style="width: 96%; margin: 1%; padding: 1%"
                          placeholder="Type your comment here . . ." required name="comment"></textarea>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="font-size: 20px; " align="right">
                    <button class="btn btn-primary" style="margin-right: 3%; width: 150px; height: 35px"><b>Post
                            Comment</b>
                    </button>
                    <br>
                </div>
            </div>
        </div>
    </form>
    <div class="container"
         style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%">

        <hr>

        @foreach($comments as $comment)
            <div class="row">
                <div class="col-md-1" align="right">
                    <a href="view_profile?user_id={{$comment->user_id}}">
                        <img src="{{$comment->picture}}" width="60px"
                             height="60px"
                             class="img-circle"
                             style="margin-top: 10px"/>
                    </a>
                </div>
                <div class="col-md-11" align="left" style="vertical-align: center">
                    <b style="font-size: 22px;"><a href="view_profile?user_id={{$comment->user_id}}"
                                                   style="color: black">{{$comment->name}}</a></b>
                    @if($comment->role != null)
                        <b style="background-color: orangered; color: white; padding: 4px; font-size: 14px; margin-left: 5px;">{{$comment->role}}</b>
                    @endif
                    <b style="color: darkgrey; margin-left: 5px;"> &#9679; {{$comment->datetime}}</b>

                    <pre style="font-size: 20px; border-width: 0px; font-family: 'Arial'; background-color: white">{{$comment->comment}}</pre>
                </div>
            </div>
            <hr>
        @endforeach
    </div>

{{--<button type="button" class="btn btn-info btn-lg">Open Modal</button>--}}

<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>G2 Roster</b></h4>
                </div>
                <div class="modal-body" style="font-size: 17px;">
                    <ol>
                        <li style="width: 100px">Perkz <i class="arrow down"
                                                          style="margin-left: 5px; margin-bottom: 2px"
                                                          id="description_btn_1" onclick="expand_description(1)"></i>
                        </li>
                        <p style="background-color: f4f4f4; display: none" id="description_1">Steam:
                            steamcommunity.com/id/sadlife<br> Discord: sadlife#6969</p>
                        <li>Caps <i class="arrow down" style="margin-left: 5px; margin-bottom: 2px"
                                    id="description_btn_2"
                                    onclick="expand_description(2)"></i></li>
                        <p style="background-color: f4f4f4; display: none" id="description_2">Steam:
                            steamcommunity.com/id/sadlife<br> Discord: sadlife#6969</p>
                        <li>Jankos <i class="arrow down" style="margin-left: 5px; margin-bottom: 2px"
                                      id="description_btn_3"
                                      onclick="expand_description(3)"></i></li>
                        <p style="background-color: f4f4f4; display: none" id="description_3">Steam:
                            steamcommunity.com/id/sadlife<br> Discord: sadlife#6969</p>
                        <li>Wunder <i class="arrow down" style="margin-left: 5px; margin-bottom: 2px"
                                      id="description_btn_4"
                                      onclick="expand_description(4)"></i></li>
                        <p style="background-color: f4f4f4; display: none" id="description_4">Steam:
                            steamcommunity.com/id/sadlife<br> Discord: sadlife#6969</p>
                        <li>Mikyx <i class="arrow down" style="margin-left: 5px; margin-bottom: 2px"
                                     id="description_btn_5"
                                     onclick="expand_description(5)"></i></li>
                        <p style="background-color: f4f4f4; display: none" id="description_5">Steam:
                            steamcommunity.com/id/sadlife<br> Discord: sadlife#6969</p>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>