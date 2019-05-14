<html>

<body>

<form action="manage_sponsorship_package" method="post" name="myForm" id="myForm">
    {{csrf_field()}}
    <input type="hidden" name="event_id" value="{{$event_id}}"/>

</form>

<script type="text/javascript">
    function submitForm() {
        document.forms["myForm"].submit();
    }

    submitForm();
</script>
</body>
</html>