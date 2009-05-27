[[ $this->inherit('examples/index') ]]
[: block section :]
	<p>{{ $example->description }}</p>
	<p><a href="/examples/{{ $example->name }}">View output</a></p>

	<ul>
		@foreach($example->getFiles() as $exampleFile):
			<li>
				<tt>{{ $exampleFile['path'] }}</tt>
				<blockquote>
				<pre><code>{{ $exampleFile['contents'] }}</code></pre>
				</blockquote>
			</li>
		@endforeach
	</ul>
[: endblock :]
