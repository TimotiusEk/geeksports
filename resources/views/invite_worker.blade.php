<html>

<body>


{{--{{$subrole_name}}--}}
<form action="worker_search_result" method="post" name="myForm" id="myForm">
    {{csrf_field()}}
    <input type="hidden" name="event_id" value="{{$event_id}}"/>


    @if(is_array($subrole_id))
        @if(count($subrole_id) != 0)
            @for($idx = 0 ; $idx < count($subrole_id) ; $idx++)
                <input type="hidden" name="subrole_id[]" value="{{$subrole_id[$idx]}}"/>
            @endfor
        @endif
    @endif

    @if(is_array($game_id))
        @if(count($game_id) != 0)
            @for($idx = 0 ; $idx < count($game_id) ; $idx++)
                <input type="hidden" name="game_id[]" value="{{$game_id[$idx]}}"/>
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