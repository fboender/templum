[[ $this->inherit('index'); ]]
[: block section_name :]About[: endblock :]
[: block section :]
	<p>Templum is an extremely <b>lightweight</b>, <b>simple</b> yet <b>powerful</b> and <b>fast</b> templating engine for PHP. It re-uses the power of PHP itself for rendering templates, but provides additional features making it easier to write templating code. Rendering templates using Templum is <em>very</em> fast; it approximates native PHP rendering speed for <tt>include()</tt> statements.</p>

<h2>Features</h2>
<ul>
	<li>Lightweight. About 140 lines of code (excluding &plusmn;140 lines of API comments).</li>
	<li>Re-uses PHP <a href="http://php.net/alternative_syntax">alternative syntax</a> for clarity and full power. (You can also use normal syntax)</li>
	<li>Very fast. Renders 10,000 templates in <tt>0.741s</tt> (Native PHP takes <tt>0.633s</tt>).</li>
	<li>I18N (translated) templates.(<a href="/Examples/I18N">Example</a>, <a href="/docs/userguide/userguide.html#_internationalisation_i18n">Documentation</a>)</li>
	<li>Per-session caching of rendered templates.</li>
	<li>Inheritance. (<a href="/Examples/Inheritance">Example</a>, <a href="/docs/userguide/userguide.html#_inheritance">Documentation</a>)</li>
	<li>Security by automatic encoding of HTML entities. (<a href="/docs/userguide/userguide.html#_security">Documentation</a>)</li>
	<li>Universal, global and local variables. (<a href="/docs/userguide/userguide.html#_variables">Documentation</a>)</li>
	<li>PHP v4 and v5 support.</li>
</ul>
<p>Templum's template syntax has the following features:</p>
<ul>
	<li><tt>{{ $tags['start_echo'] }}</tt> and <tt>{{ $tags['end_echo'] }}</tt><br />Echo's the variable, function or other PHP printables between the accolades. Echo'ed contents is automatically escaped using <tt>htmlspecialchars()</tt> (can be turned off).</li>
	<li><tt>{{ $tags['start_block'] }}</tt> and <tt>{{ $tags['end_block'] }}</tt><br />Start a PHP code block.</li>
	<li><tt>@line</tt><br />Interpret a line starting with an at-sign as a line of PHP code.</li>
</ul>

<h2>Example</h2>
<p>The following example shows most of Templum's custom syntax. You can also <a href="/examples/all_syntax">view its output</a>.</p>
<ul>
	@foreach($syntaxExample->getFiles() as $exampleFile):
		<li>
			<tt>{{ $exampleFile['path'] }}</tt>
			<pre><code>[[ echo($exampleFile['contents']); ]]</code></pre>
		</li>
	@endforeach
</ul>
<p><a href="/Examples/">View more examples</a></p>
[: endblock :]
