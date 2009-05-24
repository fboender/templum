<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{{ $apptitle }} - [: block page :]Index[: endblock :]</title>
		<style>
			body { margin: 0px; padding: 0px; }
			h1 { color: #6090F0; background-color: #404040; padding: 10px; margin: 0px; }
			h2 { color: #303030; margin-left: 30px; }
			div#menu { width: 100%; background-color: #A0A0A0; padding: 6px; }
			div#menu a { color: #404040; text-decoration: none; padding-left: 15px;}
			div#contents { margin: 30px; }
		</style>
	</head>
	<body>
		<h1>{{ $apptitle }}</h1>
		<div id="menu">
			<a href="index.php?page=home">Home</a>
			<a href="index.php?page=about">About</a>
			<a href="index.php?page=contact">Contact</a>
		</div>
		<h2>[: block page :]Index[: endblock :]</h2>
		<div id="contents">
			[: block contents :][: endblock :]
		</div>
	</body>
</html>
