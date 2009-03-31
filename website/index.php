<html>
	<head>
		<title>Templum: Simple PHP Templating</title>
		<link rel="stylesheet" rev="stylesheet" href="style/default/index.css" type="text/css"/>
	</head>
	<body>
		<div class="header">
			<h1 class="title"><img src="style/default/logo.png" alt="Templum" title="Templum" /></h1>
			<p class="subtitle">{{"Simple PHP Templating"}}</p>
		</div>

		<div class="menu">
			<a href="#about">About</a>
			<a href="#features">Features</a>
			<a href="#example">Example</a>
			<a href="#download">Download</a>
			<a href="#develop">Development</a>
			<a href="#licensecopy">License / Copright</a>
		</div>
		
		<div class="contents">
			<a name="about"><h2>About</h2></a>
			<p>Templum is an extremely <b>lightweight</b>, <b>simple</b> yet <b>powerful</b> and <b>fast</b> templating engine for PHP. It re-uses the power of PHP itself for rendering templates, but provides additional features making it easier to write templating code. Rendering templates using Templum is <em>very</em> fast; it approximates native PHP rendering speed for <tt>include()</tt> statements.</p>

			<a name="features"><h2>Features</h2></a>
			<ul>
				<li>Lightweight. Only a single file; about 120 lines of code (excluding &plusmn;130 lines of API comments).</li>
				<li>Re-uses PHP <a href="http://php.net/alternative_syntax">alternative syntax</a> for clarity and full power.</li>
				<li>Very fast. Renders 100,000 templates in <tt>7.41s</tt> (Native PHP takes <tt>6.33s</tt>).</li>
				<li>Namespaces.</li>
				<li>I18N (translated) templates.</li>
				<li>Per-session caching of rendered templates.</li>
				<li>Universal, global and local variables.</li>
			</ul>
			<p>Templum's template syntax has the following features:</p>
			<table class='syntax'>
				<tr><th><tt>{{</tt> and <tt>}}</tt></td><td>Echo's the variable, function or other PHP code between the accolades.</td></tr>
				<tr><th><tt>[[</tt> and <tt>]]</tt></td><td>Start a PHP code block.</td></tr>
				<tr><th><tt>@code</tt></td><td>Interpret a line starting with an at-sign as a line of PHP code.</td></tr>
			</table>

			<a name="example"><h2>Example</h2></a>
			<p>The following is an example template that uses all available Templum syntax (but not necessarily in the best possible way). It greets the current user (using a universal variable) and then renders a list of all the known users. This template would normally be wrapped in header and footer templates.</p>
			<p class="code_title">Template <tt>view/account/list.tpl</tt></p>
			<?php include('examples/all_syntax/list.tpl'); ?>

			<p>And here is the code to render the template.</p>
			<p class="code_title">Controller <tt>account_list.php</tt></p>
			<?php include('examples/all_syntax/list.phps'); ?>

			<a name="download"><h2>Download</h2></a>
			<table class="download">
				<tr><th>Maturity</th><th>Version</th><th>tar.gz</th><th>PEAR package</th><th>Documentation</th></tr>
				<tr><td class="stable">Stable</td><td>v0.1</td><td><a href="download/templum-0.1.tar.gz">templum-0.1.tar.gz</a></td><td><a href="download/templum-0.1.tar.gz">templum.zip</a></td><td><a href="">Documentation</a></td></tr>
				<tr><td class="beta">Beta</td><td>v0.1</td><td><a href="download/templum-0.1.tar.gz">templum-0.1.tar.gz</a></td><td><a href="download/templum-0.1.tar.gz">templum.zip</a></td><td><a href="">Documentation</a></td></tr>
				<tr><td class="alpha">Alpha</td><td>v0.1</td><td><a href="download/templum-0.1.tar.gz">templum-0.1.tar.gz</a></td><td><a href="download/templum-0.1.tar.gz">templum.zip</a></td><td><a href="">Documentation</a></td></tr>
			</table>

			<a name="develop"><h2>Development</h2></a>
			<p>Want to help out with Templum development? Or just want to run the latest, hot-off-the-press, bleeding edge version? Or just have a quick look at the code?</p>
			<table>
				<tr><td><img src="style/default/ico_source.png" alt="Source" valign="top" /></td><td><a href="https://svn.electricmonk.nl/svn/">WebSVN Subversion frontend</a></td></tr>
				<tr><td><img src="style/default/ico_bug.png" alt="Bugreport" valign="top" /></td><td><a href="https://svn.electricmonk.nl">Bug reports</a></td></tr>
				<tr><td><img src="style/default/ico_svn.png" alt="Subversion" valign="top" /></td><td><a href="https://svn.electricmonk.nl">https://svn.electricmonk.nl/svn/templum/trunk/</a></td></tr>
			</table>

			<a name="licensecopy"><h2>License / Copright</h2></a>
			<p class="sponsor"><a href="http://www.zx.nl"><img src="style/default/sponsor_zx.png" alt="ZX" border="0" align="middle" /></a></p>
			<p>Templum is Open Source and is licensed under the <a href="http://www.opensource.org/licenses/mit-license.html">MIT License</a>. This means you can use, change and redistribute it in both non-commercial and commercial scenarios. For more information, read the <a href="http://www.opensource.org/licenses/mit-license.html">whole license</a>.</p>
			<p>Templum is copyright &copy; <a href="http://www.zx.nl">ZXFactory</a> and <a href="http://www.electricmonk.nl">Ferry Boender</a> - 2009.</p>
		</div>
	</body>
</html>
