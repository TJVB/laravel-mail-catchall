<div id="mailcatchallreceivers">
@if (!empty($receivers['to']))
    <div>
        TO:
        @foreach ($receivers['to'] as $key => $value)
        	@if(is_numeric($key))
        		{{$value}}
        	@else
        		{{$value . '<' . $key . '>'}}
        	@endif
        	,
        @endforeach
    </div>
@endif
@if (!empty($receivers['cc']))
    <div>
        CC:
        @foreach ($receivers['cc'] as $key => $value)
        	@if(is_numeric($key))
        		{{$value}}
        	@else
        		{{$value . '<' . $key . '>'}}
        	@endif
        	,
        @endforeach
    </div>
@endif
@if (!empty($receivers['bcc']))
    <div>
        BCC:
        @foreach ($receivers['bcc'] as $key => $value)
        	@if(is_numeric($key))
        		{{$value}}
        	@else
        		{{$value . '<' . $key . '>'}}
        	@endif
        	,
        @endforeach
    </div>
@endif
</div>