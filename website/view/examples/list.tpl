[[ $this->inherit('examples/index') ]]
[: block section :]
<ul>
@foreach($examples as $example):
	<li><a href="{{$example->name}}">{{$example->title}}</a><br /><p>{{$example->description}}</p></li>
@endforeach
</ul>
[: endblock :]
