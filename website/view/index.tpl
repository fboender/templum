<html>
	<head>
		<title>Templum: Simple PHP Templating</title>
		<link rel="stylesheet" rev="stylesheet" href="/style/default/index.css" type="text/css"/>
	</head>
	<body>
		<h1 class="title"><img src="/style/default/logo.png" alt="Templum" title="Templum" /></h1>
		<h1 class="subtitle">{{ $tags['start_echo'] }}"Simple PHP Templating"{{ $tags['end_echo'] }}</h1>

		<ul class="menu">
			<li><a href="/About/">About</a></li>
			<li><a href="/Examples/">Examples</a></li>
			<li><a href="/Documentation/">Documentation</a></li>
			<li><a href="/Download/">Download</a></li>
			<li><a href="/Development/">Development</a></li>
			<li><a href="/License/">License / Copright</a></li>
		</ul>
		
		<h2>[: block section_name :][: endblock :]</h2>
		[: block section :][: endblock :]
	</body>
</html>
