<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <script>
        $("#dashboard_nav_bar").show();
        document.getElementById("manage_event").className = 'active';
        document.getElementById("dashboard").className = 'active';
    </script>

    <script type="text/javascript">
        function validateForm() {
            var allowedFiles = [".png", ".jpg", ".jpeg"];
            var form_valid = document.getElementById("fileToUpload");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");

            if (form_valid.files.length != 0) {
                if (!regex.test(form_valid.value.toLowerCase())) {
                    alert('Only PNG/JPG/JPEG are allowed!');
                    return false;
                }
            }
        }

        $(document).ready(function () {
            $('input[type="file"]').change(function () {
                $("#remove_news_header_btn").show()
            });
        });
    </script>

    <script>
        function removeNewsHeader() {
            $('#fileToUpload').val("");
            $("#remove_news_header_btn").hide()
        }
    </script>
</head>
<body>

@if(isset($success))
    @if($success == true)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>News draft has been succesfully updated!</b>
        </div>

        <form method="post" action="manage_news" name="myForm" id="myForm">
            {{csrf_field()}}
            <input type="hidden" name="event_id" value="{{$event_id}}"/>
            <input type="hidden" name="event_information" value="{{$event_information}}"/>
        </form>
    @endif
@endif

<form action="/update_news" method="post" enctype="multipart/form-data" onsubmit="validateForm()">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Title</label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="title" value="{{$news->title}}"/>
    </div>
    <div class="form-group">
        <b><label>Content</label></b>
        <textarea class="form-control" rows="10" id="location" placeholder="Type here..."
                  name="news_content">{{$news->content}}</textarea>
    </div>
    <div class="form-group">
        @if($news->header_image != null)
            <b><label>Current News Header</label></b><br>
            <img src="images/news_header/{{$news->header_image}}" style="margin-bottom: 10px"/><br>
        @endif
        <b><label>Update News Header</label></b><br>
        <input type="file" name="header_image" id="fileToUpload"/>
        <input type="button" style="width: 20%; margin-top: 1%; display: none" class="btn btn-danger" value="Cancel"
               id="remove_news_header_btn" onclick="removeNewsHeader()"/>
    </div>

    @if(isset($event_id))
        <input type="hidden" name="event_id" value="{{$event_id}}"/>
        <input type="hidden" name="event_information" value="{{$event_information}}"/>
    @endif

    <input type="hidden" name="news_id" value="{{$news->id}}"/>
    <button type="submit" class="form-control btn btn-primary" style="margin-top: 3%" name="status" value="Draft"><b>Update
            Draft</b></button>
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
</body>
</html>