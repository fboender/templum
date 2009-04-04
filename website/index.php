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
				<li>Very fast. Renders 10,000 templates in <tt>0.741s</tt> (Native PHP takes <tt>0.633s</tt>).</li>
				<li>I18N (translated) templates.</li>
				<li>Per-session caching of rendered templates.</li>
				<li>Security by automatic encoding of HTML entities.</li>
				<li>Universal, global and local variables.</li>
			</ul>
			<p>Templum's template syntax has the following features:</p>
			<table class='syntax'>
				<tr><th><tt>{{</tt> and <tt>}}</tt></td><td>Echo's the variable, function or other PHP printables between the accolades. Echo'ed contents is automatically escaped using <tt>htmlentities()</tt>.</td></tr>
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

			<p>You can view the output this generates: <a href="examples/all_syntax/output.html">output.html</a></p>

			<a name="download"><h2>Download</h2></a>
			<h3>PEAR</h3>
			<p>You can directly install Templum if you've got PEAR installed:</p>
			<pre>pear install http://templum.electricmonk.nl/releases/templum-0.1.0.tgz</pre>

			<h3>Source</h3>
			<p>You can also download a normal source package. These source packages include the documentation and examples for that release.</p>
			<table class="download">
				<tr><th>Maturity</th><th>Version</th><th>tar.gz</th></tr>
				<tr><td class="alpha">Alpha</td><td>v0.1.0</td><td><a href="releases/templum-src-0.1.0.tar.gz">templum-src-0.1.0.tar.gz</a></td></tr>
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
