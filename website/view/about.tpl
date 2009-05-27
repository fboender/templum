[[ $this->inherit('index'); ]]
[: block section_name :]About[: endblock :]
[: block section :]
	<p>Templum is an extremely <b>lightweight</b>, <b>simple</b> yet <b>powerful</b> and <b>fast</b> templating engine for PHP. It re-uses the power of PHP itself for rendering templates, but provides additional features making it easier to write templating code. Rendering templates using Templum is <em>very</em> fast; it approximates native PHP rendering speed for <tt>include()</tt> statements.</p>

<h2>Features</h2>
<ul>
	<li>Lightweight. About 140 lines of code (excluding &plusmn;140 lines of API comments).</li>
	<li>Re-uses PHP <a href="http://php.net/alternative_syntax">alternative syntax</a> for clarity and full power.</li>
	<li>Very fast. Renders 10,000 templates in <tt>0.741s</tt> (Native PHP takes <tt>0.633s</tt>).</li>
	<li>I18N (translated) templates.</li>
	<li>Per-session caching of rendered templates.</li>
	<li>Inheritance.</li>
	<li>Security by automatic encoding of HTML entities.</li>
	<li>Universal, global and local variables.</li>
	<li>PHP v4 and v5 support.</li>
</ul>
<p>Templum's template syntax has the following features:</p>
<ul>
	<li><tt>{{ $tags['start_echo'] }}</tt> and <tt>{{ $tags['end_echo'] }}</tt><br />Echo's the variable, function or other PHP printables between the accolades. Echo'ed contents is automatically escaped using <tt>htmlspecialchars()</tt> (can be turned off).</li>
	<li><tt>{{ $tags['start_block'] }}</tt> and <tt>{{ $tags['end_block'] }}</tt><br />Start a PHP code block.</li>
	<li><tt>@line</tt><br />Interpret a line starting with an at-sign as a line of PHP code.</li>
</ul>
[: endblock :]
