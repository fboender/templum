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
 * @brief Templum Templating Engine.
 * 
 * This is the main Templum class. It takes care of retrieval, caching and
 * compiling of (translated) templates.
 */
class Templum {
	/**
	 * @brief Create a new Templum instance.
	 * @param $templatePath (string) The full or relative path to the template directory.
	 * @param $varsUniversal (array) An array of key/value pairs that will be exported to every template retrieved using this template engine instance.
	 * @param $locale (string) The locale for the templates to retrieve. If a file with the suffix noted in $locale is available, it will be returned instead of the default .tpl file.
	 * @throw TemplumError if the $templatePath can't be found or isn't a directory.
	 */
	function Templum($templatePath, $varsUniversal = array(), $locale = NULL) {
		if (!file_exists($templatePath)) {
			trigger_error("No such file or directory: $templatePath", E_USER_ERROR); die();
		}
		if (!is_dir($templatePath)) {
			trigger_error("Not a directory: $templatePath", E_USER_ERROR); die();
		}
		$this->templatePath = rtrim(realpath($templatePath), '/');
		$this->varsUniversal = $varsUniversal;
		$this->locale = $locale;
		$this->autoEscape = True;
		$this->cache = array();
	}

	/**
	 * @brief Set a universal variable which will available in each template created with this Templum instance.
	 * @param $varName (string) The name of the variable. This will become available in the template as $VARNAME.
	 * @param $varValue (mixed) The value of the variable.
	 */
	function setVar($varName, $varValue) {
		$this->varsUniversal[$varName] = $varValue;
	}

	/**
	 * @brief Turn the auto escape on or off. If on, all content rendered using {{ and }} will automatically be escaped with htmlspecialchars().
	 * @param $escape (boolean) True of False. If True, auto escaping is turned on (this is the default). If False, it is turned off for templates retrieved with this Templum engine.
	 * @note Auto escaping can be overridden by passing the $autoEscape option to the template() and templateFromString() methods.
	 */
	function setAutoEscape($escape = True) {
		$this->autoEscape = $escape;
	}

	/**
	 * @brief Set the locale for templates.
	 * @param $locale (string) The locale for the templates to retrieve. If a file with the suffix noted in $locale is available, it will be returned instead of the default .tpl file.
	 */
	function setLocale($locale) {
		$this->locale = $locale;
	}

	/**
	 * @brief Retrieve a template by from disk (caching it in memory for the duration of the Templum instance lifetime) or from cache.
	 * @param $path (string) TemplumTemplate path, without the .tpl extension, relative to the templatePath.
	 * @param $varsGlobal (array) Array of key/value pairs that will be exported to the returned template and all templates included by that template.
	 * @param $autoEscape (boolean) Whether to auto escape {{ and }} output with htmlspecialchars()
	 * @throw TemplumError if the template couldn't be read.
	 */
	function template($path, $varsGlobal = array(), $autoEscape = NULL) {
		$fpath = $this->templatePath . '/' . trim($path, '/').'.tpl';
		if ($autoEscape === NULL) {
			$autoEscape = $this->autoEscape;
		}

		// Check for translated version of this template.
		if (!empty($this->locale)) {
			// Check if the translated template exists in the cache. If it
			// does, returned the cached result. Otherwise check the disk for
			// the translated template.
			$fpathTrans = realpath($fpath.'.'.$this->locale);
			if ($fpathTrans !== False) {
				if (array_key_exists($fpathTrans, $this->cache)) {
					return($this->cache[$fpathTrans]);
				} else {
					if (file_exists($fpathTrans)) {
						$fpath = $fpathTrans;
					}
				}
			}
		// Check the non-translated version of this template
		} else {
			// Check the cache for the non-translated template. 
			$rpath = realpath($fpath);
			if($rpath === False) {
				trigger_error("Template not found or not a file: $fpath", E_USER_ERROR); die();
			}
			if (array_key_exists($rpath, $this->cache)) {
				return($this->cache[$rpath]);
			}
			$fpath = $rpath;
		}

		// Check if the template exists. 
		if (!is_file($fpath)) {
			trigger_error("Template not found or not a file: $fpath", E_USER_ERROR); die();
		}
		if (!is_readable($fpath)) {
			trigger_error("Template not readable: $fpath", E_USER_ERROR); die();
		}

		// Load the base or translated template.
		$template = new TemplumTemplate(
				$this,
				$fpath,
				$this->compile(file_get_contents($fpath), $autoEscape), 
				array_merge($this->varsUniversal, $varsGlobal)
			);
		$this->cache[$fpath] = $template;
		return($template);
	}
	
	/**
	 * @brief Create a TemplumTemplate from a string.
	 * 
	 * Create a TemplumTemplate instance using $contents as the template contents.
	 * This severely limited what you can do with the TemplumTemplate. There will be
	 * no including from the template, no translations, no caching, etc.
	 *
	 * @param $contents (string) The template contents.
	 * @param $autoEscape (boolean) Whether to auto escape {{ and }} output with htmlspecialchars()
	 * @returns (TemplumTemplate) TemplumTemplate class instance.
	 */
	function templateFromString($contents, $autoEscape = Null) {
		if ($autoEscape === Null) {
			$autoEscape = $this->autoEscape;
		}

		// Load the base or translated template.
		$template = new TemplumTemplate(
				NULL,
				"FROM_STRING",
				$this->compile($contents, $autoEscape), 
				array()
			);
		return($template);
	}

	/**
	 * @brief Compile a template string to PHP code.
	 * @param $contents (string) String to compile to PHP code.
	 * @param $autoEscape (boolean) Whether to auto escape {{ and }} output with htmlspecialchars()
	 * @note This method is used by the Templum class itself, and shouldn't be called directly yourself. Use templateFromString() instead.
	 */
	function compile($contents, $autoEscape = True) {
		// Parse custom short-hand tags to PHP code.
		$contents = str_replace(
			array(
				"{{", 
				"}}\n", 
				"}}", 
				"[[", 
				"]]"),
			array(
				$autoEscape ? "<?php echo(htmlspecialchars(" : "<?php echo(", 
				$autoEscape ? ")); ?>\n\n" : "); ?>\n\n",
				$autoEscape ? ")); ?>" : "); ?>",
				"<?php ",
				" ?>",
				),
			$contents
		);
		// Parse start-line PHP tags ("@" at the beginning of the line).
		$contents = preg_replace('/^\s*@(.*)$/m', "<?php \\1 ?>", $contents);
		return($contents);
	}
}

/**
 * @brief Template class
 *
 * This is the TemplumTemplate class. It represents a template and handles the
 * actual rendering of the template, as well as catching errors during
 * rendering. It also contains helper methods which can be used in templates.
 */
class TemplumTemplate {
	/**
	 * @brief Create a new TemplumTemplate instance. You'd normally get an instance from a Templum class instance.
	 * @param $templum (Templum instance) The Templum class instance that generated this TemplumTemplate instance.
	 * @param $filename (string) The filename of this template.
	 * @param $contents (string) The compiled contents of this template.
	 * @param $varsGlobal (array) An array of key/value pairs which represent the global variables for this template and the templates it includes.
	 */
	function TemplumTemplate($templum, $filename, $contents, $varsGlobal = array()) {
		$this->templum = $templum;
		$this->filename = $filename;
		$this->contents = $contents;
		$this->varsGlobal = $varsGlobal;
	}

	/**
	 * @brief Add an global variable. The global variable will be available to this templates and all the templates it includes.
	 * @param $varName (string) The name of the variable.
	 * @param $varValue (mixed) The value of the variable.
	 */
	function setVar($varName, $varValue) {
		$this->varsGlobal[$varName] = $varValue;
	}

	/**
	 * @brief Render the contents of the template and return it as a string.
	 * @param $varsLocal (array) An array of key/value pairs which represent the local variables for this template. 
	 * @return (string) The rendered contents of the template.
	 */
	function render($varsLocal = array()) {
		// Extract the Universal (embedded in global), Global and
		// Localvariables into the current scope.
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

	/**
	 * @brief The error handler that handles errors during the parsing of the template. 
	 * @param $nr (int) Error code
	 * @param $string (string) Error message
	 * @param $file (string) Filename of the file in which the erorr occurred.
	 * @param $line (int) Linenumber of the line on which the error occurred.
	 * @note Do not call this yourself. It is used internally by Templum but must be public.
	 */
	function errorHandler($nr, $string, $file, $line) {
		// We can restore the old error handler, otherwise this error handler
		// will stay active because we throw an exception below.
		restore_error_handler();

		// If this is reached, it means we were still in Output Buffering mode.
		// Stop output buffering, or we'll end up with template text on the
		// Stdout.
		ob_end_clean();

		// Throw the exception
		trigger_error("$string (file: {$this->filename}, line $line)", E_USER_ERROR); die();
	}


	/* LEFTOFF HERE */
	function inc($name, $varLocals = array()) {
		if (!isset($this->templateEngine)) {
			trigger_error("Cannot include in TemplumTemplate create from string.", E_USER_ERROR); die();
		}
		$template = $this->templateEngine->template($name, $this->varsGlobal);
		return($template->render());
	}

}

?>
