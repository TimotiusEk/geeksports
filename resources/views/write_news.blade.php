<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
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
    s
</head>
<body>

@if(isset($success))
    @if($success == true)
        <div style="width: 48%; margin-left: 26%; margin-right: 26%; margin-top:2%;background-color: #D1EED9; color: #930027; padding: 1%;">
            <b>News has been succesfully submitted!</b>
        </div>

        <form method="post" action="manage_news" name="myForm" id="myForm" onsubmit="validateForm()">
            {{csrf_field()}}
            <input type="hidden" name="event_id" value="{{$event_id}}"/>

        </form>
    @endif
@endif

<form action="/write_news" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group">
        <b><label>Title</label></b>
        <input type="text" class="form-control" placeholder="Type here..." name="title"/>
    </div>
    <div class="form-group">
        <b><label>Content</label></b>
        <textarea class="form-control" rows="10" id="location" placeholder="Type here..."
                  name="news_content"></textarea>
    </div>
    <div class="form-group">
        <b><label>Add News Header (Optional)</label></b><br>
        <input type="file" name="header_image" id="fileToUpload"/>
        <button class="btn btn-danger" style="width: 20%; margin-top: 1%; display: none" id="remove_news_header_btn"
                onclick="removeNewsHeader()"><b>Cancel</b></button>
    </div>

    @if(isset($event_id))
        <input type="hidden" name="event_id" value="{{$event_id}}"/>

    @endif
    <button type="submit" class="btn btn-primary" style="margin-top: 3%" name="status" value="Draft"><b>Save
            as Draft</b></button>
    <button type="submit" class="btn btn-primary" style="margin-top: 1%" name="status" value="Published">
        <b>Publish</b></button>
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