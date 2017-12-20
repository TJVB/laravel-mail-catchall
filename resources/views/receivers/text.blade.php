----------------------------------------
Original receivers
@if (!empty($receivers['to']))
    TO:
    @foreach ($receivers['to'] as $key => $value)
    	@if(is_numeric($key))
    		{{$value}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	,
    @endforeach
@endif
@if (!empty($receivers['cc']))
    CC:
    @foreach ($receivers['cc'] as $key => $value)
    	@if(is_numeric($key))
    		{{$value}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	,
    @endforeach
@endif
@if (!empty($receivers['bcc']))
    BCC:
    @foreach ($receivers['bcc'] as $key => $value)
    	@if(is_numeric($key))
    		{{$value}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	,
    @endforeach
@endif