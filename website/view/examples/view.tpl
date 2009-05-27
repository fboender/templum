[[ $this->inherit('examples/index') ]]
[: block section :]
	<h3><a href="/Examples/">All examples</a> &rarr; {{ $example->title }}</h3>
	<p>{{ $example->description }}</p>
	<p><a href="/examples/{{ $example->name }}">View output</a></p>

	<ul>
		@foreach($example->getFiles() as $exampleFile):
			<li>
				<tt>{{ $exampleFile['path'] }}</tt>
				<pre><code>[[ echo($exampleFile['contents']); ]]</code></pre>
			</li>
		@endforeach
	</ul>
[: endblock :]
