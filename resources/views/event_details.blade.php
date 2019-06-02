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
<div class="container" style="background-color: white; border-radius: 10px; width: 99%; margin-top: 1%">
    <div class="row">
        <div class="col-md-4 text-center" style="margin-top: 25px">
            @if(!is_null($event->brochure))
                <img src="/images/event_brochure/{{$event->brochure}}"
                     style="width: 400px; height: 220px"/>
            @else
                <img style=" height: 225px; width: 400px; border-radius: 10px; margin-left: -5px;"
                     src="/images/default_event_img.png"/>
            @endif
        </div>
        <div class="col-md-8" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <div class="row" style="margin-bottom: -20px">
                    <div class="col-md-7">
                        <p style="margin-top: 30px; color: black; font-size: 35px"><b>{{$event->name}}</b></p>
                        <p style="font-size: 18px; margin-top: -20px">By: <a
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


                <div style="font-size: 23px; margin-bottom: 10px; color: black"><img src="images/ic_location.png"
                                                                                     style="width: 30px;"> {{$city}}
                </div>
                <div style="font-size: 23px; margin-bottom: 10px; color: black"><img src="images/ic_datetime.png"
                                                                                     style="width: 30px;"> {{$event->start_date}}
                    - {{$event->end_date}}
                </div>
                <div style="font-size: 23px; margin-bottom: 15px; color: black"><img src="images/ic_game.png"
                                                                                     style="width: 30px; margin-right: 4px; margin-top: -5px">
                    {{$game_name}}
                </div>
                @if(!is_null($event->details))
                    <div class="work-inner">
                        <div class="desc">
                       <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial'; background-color: white"
                            align="left">{{$event->details}}</pre>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@foreach($participated_teams as $participated_team)
    @if(count($participated_team->teams) != 0)
        <div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
            <b style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black">Participated Teams
                ({{$participated_team->game_name}})</b>
            <hr style="width: 95%">

            <table align="center" style="margin-bottom: 45px; margin-top: 2%">
                @for($idx = 0 ; $idx < count($participated_team->teams); $idx++)
                    @if($idx % 5 == 0)
                        <tr>
                            @endif

                            <td align="center"
                                class="participated_team">
                                <a href="view_profile?team_id={{(($participated_team->teams)[$idx])->id}}">
                                    <div>
                                        @if(!is_null((($participated_team->teams)[$idx])->team_logo))
                                            <img src="/images/team_logo/{{(($participated_team->teams)[$idx])->team_logo}}"
                                                 style="height: 110px; width: 110px;"/>
                                        @else
                                            <img src="/images/default_logo.png" style="height: 110px; width: 110px;"/>
                                        @endif
                                    </div>
                                    <div style="margin-top: 10px; font-size: 20px">
                                        <b>{{(($participated_team->teams)[$idx])->team_name}}</b></div>
                                </a>
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
        <b style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black">Sponsors</b>
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
    <div class="container"
         style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px; padding-top: 2%">
        <b style="margin-left: 10px; font-size: 30px; color: black">Streaming Channels</b>
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
    <div class="container"
         style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px; padding-top: 2%">
        <b style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black">Recent News</b>
        <hr>

        @for($idx = 0 ; $idx < count($news) ; $idx++)
            @if($idx % 3 == 0)
                <div class="row">
                    @endif
                    <div class="col-md-4 text-center">
                        <div class="blog-inner">
                            <form action="read_news">
                                <input type="hidden" name="news_id" value="{{($news[$idx])->id}}"/>
                                <a href="#" onclick="$(this).closest('form').submit()"><img style="width: 100%"
                                                                                            src="/images/news_header/{{($news[$idx])->header_image}}"
                                                                                            alt="Blog"></a>
                                <div class="desc">
                                    <h3><a href="#"
                                           onclick="$(this).closest('form').submit()">{{($news[$idx])->title}}</a></h3>
                                    <p style="font-size: 14px; margin-top: -30px ">{{($news[$idx])->published_on}}</p>
                                    <p>{{($news[$idx])->content}}</p>
                                    <p><a href="#" onclick="$(this).closest('form').submit()"
                                          class="btn btn-primary btn-outline">Read More</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($idx % 3 == 2 || $idx == count($news) - 1)
                </div>
            @endif

            <hr>
        @endfor


    </div>
@endif

<div class="container" style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px;">
    <div class="row" style="padding-top: 25px; padding-bottom: 5px; padding-left: 10px;">
        <b style="margin-left: 10px; font-size: 30px; color: black">Rate this Event</b>
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
        </form>
    </div>
    <hr style="width: 95%">
</div>

<!--COMMENT SECTION-->
<div class="container"
     style="background-color: white; width: 99%; margin-top: 30px; border-radius: 10px; padding-top: 2%">
    <b style="margin-left: 10px; font-size: 30px; color: black">Comments</b>
    <hr>
    <form action="post_comment" method="post">
        {{csrf_field()}}
        <input type="hidden" name="event_id" value="{{$event->id}}"/>
        <div class="container"
             style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 1%">
            <div class="row">
                <div class="col-md-12" style="font-size: 20px; vertical-align: top;">

                <textarea rows="3" style="width: 96%; margin: 1%; padding: 1%"
                          placeholder="Type your comment here . . ." required name="comment"
                          class="form-control"></textarea>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="font-size: 20px; " align="right">
                    <button type="submit" class="btn btn-primary" style="margin-right: 3%;">
                        <b>Post Comment</b>
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


</div>
</body>
</html>