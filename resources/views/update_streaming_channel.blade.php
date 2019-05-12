<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <?php include 'php/datatables.php'; ?>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-clockpicker.min.css">
    <script>
        $("#dashboard_nav_bar").show();

        document.getElementById("dashboard").className = 'active';
    </script>
</head>
<body>
<h1 style="margin-left: 1%; font-size: 35px"><b>Update Streaming Channel</b></h1>
<hr style="height:1px;border:none;color:#333;background-color:#333; width: 99%">
{{--<h2 style="margin-left: 1%; font-size: 30px">{{$event_information}}</h2>--}}
<h2 style="margin-left: 1%; font-size: 30px">The International 2019 ( - )</h2>

@if(isset($success))
    @if($success == true)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>Streaming Channel has been updated successfully!</b>
        </div>

        <form method="get" action="manage_streaming_channel" name="myForm" id="myForm">
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
        </form>
    @endif
@endif

<form action="update_streaming_channel" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Title</label></b>
        <input type="text" class="form-control" name="title" value="{{$streaming_channel->title}}" required/>
    </div>

    <div class="form-group">
        <b><label>URL</label></b>
        <input type="text" class="form-control" name="url" value="{{$streaming_channel->url}}" required/>
    </div>

    <div class="form-group">
        <b><label>Start Date (Optional)</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-calendar"></i>
            <input type="date" class="form-control" name="start_date" value="{{$streaming_channel->start_date}}"/>
        </div>
    </div>

    <div class="form-group clockpicker">
        <b><label>Start Time (Optional)</label></b>
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-time"></i>
            <input type="text" class="form-control" name="start_time"
                   @if($streaming_channel->start_time != null) value="{{$streaming_channel->start_time}}" @endif/>
        </div>
    </div>

    <!--todo: set event id by previous page-->
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <input type="hidden" name="streaming_channel_id" value="{{$streaming_channel->id}}"/>

    <button type="submit" class="form-control btn btn-primary" style="margin-top: 35px"><b>Update Streaming Channel</b>
    </button>
</form>

@if(isset($success))
    @if($success == true)
        <script type="text/javascript">
            function submitform() {
                document.forms["myForm"].submit();
            }

            setTimeout(submitform, 2000)
        </script>
    @endif
@endif

<script type="text/javascript" src="/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker({
        donetext: 'Done'
    });
</script>
</body>
</html>