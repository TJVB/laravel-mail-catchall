----------------------------------------
Original receivers
@if (!empty($receivers['to']))
    TO:
    @foreach ($receivers['to'] as $key => $value)
    	@if(is_null($key) || is_numeric($key))
    		{{$value}}
		@elseif(is_null($value))
			{{$key}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	@if(!$loop->last)
    	,
    	@endif
    @endforeach
@endif
@if (!empty($receivers['cc']))
    CC:
    @foreach ($receivers['cc'] as $key => $value)
    	@if(is_null($key) || is_numeric($key))
    		{{$value}}
		@elseif(is_null($value))
			{{$key}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	@if(!$loop->last)
    	,
    	@endif
    @endforeach
@endif
@if (!empty($receivers['bcc']))
    BCC:
    @foreach ($receivers['bcc'] as $key => $value)
    	@if(is_null($key) || is_numeric($key))
    		{{$value}}
		@elseif(is_null($value))
			{{$key}}
    	@else
    		{{$value . '<' . $key . '>'}}
    	@endif
    	@if(!$loop->last)
    	,
    	@endif
    @endforeach
@endif