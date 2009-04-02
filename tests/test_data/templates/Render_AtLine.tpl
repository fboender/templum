Hello, here's a list of some names:

@foreach($names as $name):
	The name is {{$name}}.
@endforeach

	@if (in_array('piet', $names)):
		Piet was here
	@endif
