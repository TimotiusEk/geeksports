<html>

<body>

<form action="manage_news" method="post" name="myForm" id="myForm">
    {{csrf_field()}}
    <input type="hidden" name="event_id" value="{{$event_id}}"/>
    <input type="hidden" name="event_information" value="{{$event_information}}"/>
</form>

<script type="text/javascript">
    function submitForm() {
        document.forms["myForm"].submit();
    }

    submitForm();
</script>
</body>
</html>