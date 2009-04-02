<?php
/*
 * 
 * The MIT License
 * 
 * Copyright (c) 2009, ZX, Ferry Boender
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
*/

/**
 * @brief TemplateEngine errors.
 * 
 * This exception is thrown by the TemplateEngine class when errors occur
 * during instantiation or when loading and parsing templates.
 */
class TemplateEngineException extends Exception {

}

/**
 * @brief Template errors.
 * 
 * This exception is thrown by the Template class when errors occur
 * during the execution of templates. PHP errors, warnings and notices that
 * occur during the template execution are captured by the Template class and
 * are thrown as TemplateException exceptions.
 */
class TemplateException extends Exception {
	
}

/**
 * @brief Simple templating engine.
 * 
 * The TemplateEngine class is an access class for retrieving templates.
 * Templates should be used to seperate the displaying of information from the
 * business logic of the application.
 *
 * The TemplateEngine is used to retrieve Template instances. When
 * instantiating a TemplateEngine class, you pass a path and possibly an array
 * of global variables and a locale to the constructor. You can then ask the
 * TemplateEngine class for Template() instances using the
 * TemplateClass->template() method. This requires you to pass a namespace for
 * the template, and the name of the template. The TemplateEngine class will
 * search the namespace for the template by concatenating the template path,
 * namespace, template name and the provided locale into a path like so:
 * <tt>TEMPLATEPATH/NAMESPACE/TEMPLATENAME.tpl.LOCALE</tt>. For example
 * <tt>views/network/list.tpl.en_UK</tt>.
 *
 * You can also retrieve Template instances by creating a template from a
 * string, using the templateFromString method.
 *
 * <h2>Variables</h2>
 *
 * There are two types of variables in the template engine. Global variables,
 * which are passed as the second parameter to the TemplateEngine constructor,
 * are variables that will be available in all of the templates retrieved using
 * that TemplateEngine instance. Local variables, which are passed to a
 * Template()'s render() method, are only available in that template.
 * 
 * <h2>Translated templated</h2>
 *
 * The template engine supports a crude form of translatable templates. If you
 * pass the TemplateEngine a locale as the third parameter, the template engine
 * will always first look for a template with the extension '.tpl.LOCALE'. For
 * example, say you create the template engine like so: <tt>$te =
 * TemplateEngine('./views/', array(), 'nl_NL');</tt>, it will look for a file
 * './views/NAMESPACE/TEMPLATENAME.tpl.nl_NL'. If such a file does not exist,
 * it will fall back to the default template
 * './views/NAMESPACE/TEMPLATENAME.tpl'.
 *
 * <h2>Template Language</h2>
 *
 * The Template language supported by the template engine is simple. Three
 * kinds of special syntax are suported:
 *
 * <table>
 * <tr><td><tt>{{</tt></td><td>Double opening accolades will be replaced with <tt><?php echo('</tt>.</td></tr>
 * <tr><td><tt>}}</tt></td><td>Double closing accolades will be replaced with <tt>'); ?></tt>.</td></tr>
 * <tr><td><tt>[[</tt></td><td>Double opening brackets will be replaced with <tt><?php </tt>.</td></tr>
 * <tr><td><tt>]]</tt></td><td>Double closing brackets will be replaced with <tt> ?></tt>.</td></tr>
 * <tr><td><tt>@ </tt></td><td>An '@' at the beginning of the line (column 0) will cause the entire line to be enclosed in: <tt>&lt;?php </tt> and <tt> ?&gt;</tt></td></tr>
 * </table>
 *
 * These simple rules allow you more easily embed PHP code in a template. The
 * following is an example of a template which uses all of the available
 * syntaxis:
 *
 * <pre>
 * [[
 * function formatServerCode($serverCode) {
 * 	// Formatting server codes only used in this template.
 * 	return('ZX-'.str_repeat('0', 5-strlen($serverCode)).$serverCode);
 * }
 * ]]
 * &lt;h1&gt;Server Owners&lt;/h1&gt;
 * 
 * &lt;p&gt;Hello {{$username}}. Here's a list of server owners:&lt;/p&gt;
 * 
 * &lt;table&gt;
 * 	&lt;tr&gt;&lt;th&gt;Server code&lt;/th&gt;&lt;th&gt;Owner&lt;/th&gt;&lt;/tr&gt;
 * \@foreach($serverinfo as $serverCode =&gt; $serverOwner):
 * 	  &lt;tr&gt;
 * 	  	&lt;td&gt;{{formatServerCode($serverCode)}}&lt;/td&gt;
 * 	  	&lt;td&gt;{{$serverOwner}}&lt;/td&gt;
 * 	  &lt;/tr&gt;
 * \@endforeach
 * &lt;/table&gt;
 * </pre>
 *
 * This template can be used like this (assuming the template is located in the directory 'views/objects/list.tpl'):
 *
 * <pre>
 * // Define some data.
 * $serverOwners = array(
 * 	'10080' => 'Ferry Boender',
 * 	'10081' => 'ZX Factory',
 * 	'10082' => 'ZX Factory',
 * 	'10083' => 'Customer X',
 * );
 * $username = 'fboender';
 * 
 * // Render the template.
 * $templatePath = 'views/';
 * $tplEngine = new TemplateEngine($templatePath);
 * $tpl = $tplEngine->template('objects', 'list_serverowners');
 * print $tpl->render(compact('username', 'serverOwners'));
 * </pre>
 */
class TemplateEngine {
	/**
	 * @brief Create a new Template Engine instance.
	 * @param $templatePath (string) The full or relative path to the template directory.
	 * @param $varsUniversal (array) An array of key/value pairs that will be exported to every template retrieved using this template engine instance.
	 * @param $locale (string) The locale for the templates to retrieve. If a file with the suffix noted in $locale is available, it will be returned instead of the default .tpl file.
	 * @throw TemplateEngineException if the $templatePath can't be found or isn't a directory.
	 */
	public function TemplateEngine($templatePath, $varsUniversal = array(), $locale = NULL) {
		if (!file_exists($templatePath)) {
			throw new TemplateEngineException('No such file or directory');
		}
		if (!is_dir($templatePath)) {
			throw new TemplateEngineException('Not a directory');
		}
		$this->templatePath = rtrim($templatePath, '/');
		$this->varsUniversal = $varsUniversal;
		$this->locale = $locale;
		$this->cache = array();
	}

	public function setVar($varName, $varValue) {
		$this->varsUniversal[$varName] = $varValue;
	}

	/**
	 * @brief Retrieve a template by namespace and name from disk, caching it in memory for the duration of the TemplateEngine instance lifetime.
	 * @param $namespace (string) Namespace (directory under $templatePath) to retrieve the template from.
	 * @param $name (string) Template name, without the .tpl extension.
	 * @param $varsGlobal (array) Array of key/value pairs that will be exported to the returned template and all templates included by that template.
	 * @throw TemplateException if the namespace isn't valid or the template couldn't be found.
	 */
	public function template($namespace, $name, $varsGlobal = array()) {
		/* FIXME: Add this to this file if needed, or reimplement */
		/*
		if (!Validate::namespace($namespace)) {
			throw new TemplateException('Invalid namespace');
		}
		*/

		$namespace = trim(str_replace('.', '/', $namespace), '/');
		$fpath = $this->templatePath . '/' . $namespace . '/' . $name. '.tpl';

		// Check for translated version of this template.
		if (!empty($this->locale)) {
			$fpathTrans = $fpath.'.'.$this->locale;
			// Check if the translated template exists in the cache. If it
			// does, returned the cached result. Otherwise check the disk for
			// the translated template.
			if (array_key_exists($fpathTrans, $this->cache)) {
				return($this->cache[$fpath]);
			} else {
				if (file_exists($fpathTrans)) {
					$fpath = $fpathTrans;
				}
			}
		// Check the non-translated version of this template
		} else {
			// Check the cache for the non-translated template. 
			if (array_key_exists($fpath, $this->cache)) {
				return($this->cache[$fpath]);
			}
		}

		$fpath = realpath($fpath); // Normalize

		// Check if the template exists. 
		if (!file_exists($fpath)) {
			throw new TemplateException('Template not found: '.$fpath);
		}

		// Load the base or translated template.
		$template = new Template(
				$this,
				$namespace,
				$fpath,
				$this->compile(file_get_contents($fpath)), 
				array_merge($this->varsUniversal, $varsGlobal)
			);
		$this->cache[$fpath] = $template;
		return($template);
	}
	
	/**
	 * @brief Create a Template from a string.
	 * 
	 * Create a Template instance using $contents as the template contents.
	 * This severely limited what you can do with the Template. There will be
	 * no including from the template, no translations, no caching, etc.
	 *
	 * @param $contents (string) The template contents.
	 * @returns (Template) Template class instance.
	 */
	public static function templateFromString($contents) {
		// Load the base or translated template.
		$template = new Template(
				NULL,
				"FROM_STRING",
				TemplateEngine::compile($contents), 
				array()
			);
		return($template);
	}

	/**
	 * @brief Compile a template string to PHP code.
	 * @param $contents (string) String to compile to PHP code.
	 * @note This method is used by the TemplateEngine class itself, and shouldn't be called directly yourself. Use templateFromString() instead.
	 */
	public static function compile($contents) {
		// Parse custom short-hand tags to PHP code.
		$contents = str_replace(
			array(
				'{{', 
				'}}', 
				'[[', 
				']]'),
			array(
				'<?php echo(', 
				'); ?>',
				'<?php ',
				' ?>',
				),
			$contents
		);
		// Parse start-line PHP tags ("@" at the beginning of the line).
		$contents = preg_replace('/^\s*@(.*)$/m', "<?php \\1 ?>", $contents);
		return($contents);
	}
}

class Template {
	public function Template($templateEngine, $namespace, $filename, $contents, $varsGlobal = array()) {
		$this->templateEngine = $templateEngine;
		$this->namespace = $namespace;
		$this->filename = $filename;
		$this->contents = $contents;
		$this->varsGlobal = $varsGlobal;
	}

	public function setVar($varName, $varValue) {
		$this->varsGlobal[$varName] = $varValue;
	}

	public function render($varsLocal = array()) {
		// Extract the Universal (embedded in global), Global and Local
		// variables into the current namespace.
		extract($this->varsGlobal);
		extract($varsLocal);

		// Start output buffering so we can capture the output of the eval.
		ob_start();

		// Temporary set the error handler so we can show the faulty template
		// file. Render the contents, reset the error handler and return the
		// rendered contents.
		$this->errorHandlerOrig = set_error_handler(array($this, 'errorHandler'));
		eval("?>" . $this->contents);
		restore_error_handler();

		// Stop output buffering and return the contents of the buffer
		// (rendered template).
		$result = ob_get_contents();
		ob_end_clean();

		return($result);
	}

	public function errorHandler($nr, $string, $file, $line) {
		// We can restore the old error handler, otherwise this error handler
		// will stay active because we throw an exception below.
		restore_error_handler();

		// If this is reached, it means we were still in Output Buffering mode.
		// Stop output buffering, or we'll end up with template text on the
		// Stdout.
		ob_end_clean();

		// Throw the exception
		throw new TemplateException('Template error: \''.$string.'\' in file '.$this->filename.', line '.$line);
	}


	/* LEFTOFF HERE */
	public function inc($namespace, $name, $varLocals = array()) {
		if (!isset($this->templateEngine)) {
			throw new TemplateException('Cannot include in Template create from string.');
		}
		$template = $this->templateEngine->template($namespace, $name, $this->varsGlobal);
		return($template->render());
	}

}

?>
