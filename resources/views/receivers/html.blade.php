<div id="mailcatchallreceivers">
@if (!empty($receivers['to']))
    <div>
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
    </div>
@endif
@if (!empty($receivers['cc']))
    <div>
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
    </div>
@endif
@if (!empty($receivers['bcc']))
    <div>
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
    </div>
@endif
</div>