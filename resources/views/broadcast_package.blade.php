<html>

<body>


{{--{{$subrole_name}}--}}
<form action="sponsor_search_result" method="post" name="myForm" id="myForm">
    {{csrf_field()}}
    <input type="hidden" name="event_id" value="{{$event_id}}"/>


    @if(is_array($industry_id))
        @if(count($industry_id) != 0)
            @for($idx = 0 ; $idx < count($industry_id) ; $idx++)
                <input type="hidden" name="industry_id[]" value="{{$industry_id[$idx]}}"/>
            @endfor
        @endif
    @endif

    @if($keyword != "")
        <input type="hidden" name="keyword" value="{{$keyword}}"/>
    @endif
</form>

<script type="text/javascript">
    function submitForm() {
        document.forms["myForm"].submit();
    }

    submitForm();
</script>
</body>
</html>