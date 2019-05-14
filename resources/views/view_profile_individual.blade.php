<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <style>
        td {
            padding-left: 100px;
            text-align: center;
        }

        tr.highlight {
            border-top: 30px solid;
            border-bottom: 30px solid;
            border-color: transparent;
        }
    </style>

    <script>
        document.getElementById("profile").className = 'active';
    </script>
</head>
<body style="background-color: whitesmoke">
<div class="container" style="width: 98%; padding: 1%; margin: 1%">
    <div class="row" align="center" style="background-color: white; border-radius: 10px;">
        <img src="/images/profile_picture/{{$user->profile_picture}}" width="150px" height="150px" class="img-circle"
             style="margin-top: 20px"/>
        <p style="font-size: 50px">{{$user->display_name}} @if($edit == true)<img src="/images/ic_edit_no_text.png"
                                                                                  width="25px"/>@endif</p>
        <p style="font-size: 25px; margin-top: -15px; margin-bottom: 45px">{{$user->subrole}}</p>
        <div style="background-color: #f4f4f4; font-size: 23px; padding: 2%; margin: 1%">
            <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial';"
                 align="left">{{$user->description}}</pre>
        </div>
    </div>

    <div class="row" style="background-color: white; border-radius: 10px; margin-top: 30px; padding: 1%">
        <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Games</b></p>
        <hr>
        <div align="center" style="padding: 1%; vertical-align: middle">
            <table>
                <tbody>
                @for($idx = 0 ; $idx < count($user->games) ; $idx++)
                    @if($idx % 5 == 0)
                        <tr class="highlight">
                            @endif
                            <td>
                                <img src="/images/game_logo/{{(($user->games)[$idx])->logo}}"
                                     style="max-width: 200px; max-height: 100px"/>
                            </td>
                            @if($idx % 5 == 4 || $idx == count($user->games)-1)
                        </tr>
                    @endif
                @endfor
                </tbody>
            </table>

        </div>
    </div>

    @if(!is_null($user->events))
        <div class="row" style="background-color: white; border-radius: 10px; margin-top: 30px; padding: 1%">
            <p style="margin-top: 20px; margin-left: 10px; font-size: 40px;"><b>Participated Event</b></p>
            <hr>

            <div class="col-md-3" align="center" style="margin-bottom: 20px">
                @foreach($user->events as $user_event)
                    <img src="/images/event_brochure/{{$user_event->brochure}}"
                         style="max-width: 200px; max-height: 100px"/>
                    <p style="font-size: 23px; margin-top: 10px; margin-bottom: -5px"><b><a
                                    href="event_details?event_id={{$user_event->id}}">{{$user_event->name}}</a></b></p>
                    <p>{{$user_event->subrole}}</p>
                    <p>{{$user_event->experience_type}}</p>
                @endforeach
            </div>
        </div>
    @endif

</div>
</body>
</html>