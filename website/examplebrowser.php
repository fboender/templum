<?php

class Example {
	public $allowedExtensions = array('php', 'tpl', 'css');
	public function Example($name) {
		$this->name = basename($name);
		$this->readme = explode("\n", file_get_contents('examples/'.$this->name.'/README.txt'));
		$this->title = $this->readme[0];
		$this->description = implode("\n", array_slice($this->readme, 1));
	}

	public function getFiles($highlight = True) {
		$exampleFiles = array();

		$stripPos = strlen('examples/'.$this->name);
		$inspectPaths = array('examples/'.$this->name);
		while ($inspectPaths) {
			$path = array_shift($inspectPaths);
			if (is_dir($path)) {
				$dh = opendir($path);
				while (($file = readdir($dh)) !== False) {
					if ($file[0] == '.') {
						continue;
					}
					$inspectPaths[] = $path . '/' . $file;
				}
				closedir($dh);
			} else {
				$pathparts = pathinfo($path);
				if (
					!array_key_exists('extension', $pathparts) || 
					!in_array($pathparts['extension'], $this->allowedExtensions)
				) {
					continue;
				}
				if ($highlight) {
					$contents = $this->highlight($path);
				} else {
					$contents = file_get_contents($path);
				}
				$exampleFile = array(
					'path' => substr($path, $stripPos),
					'contents' => $contents
				);
				// Very ugly sorting.
				if ($pathparts['extension'] == 'php') {
					array_unshift($exampleFiles, $exampleFile);
				} else {
					$exampleFiles[] = $exampleFile;
				}
			}
		}
		return($exampleFiles);
	}

	public function highlight($path) {
		$contents = file_get_contents($path);
		$contents = htmlentities($contents);
		if (substr($path, -4) == '.php') {
			// Parse start-line PHP tags ("@" at the beginning of the line).
			$contents = preg_replace('/^(\/\/.*)$/m', "<font color=\"#C0C0C0\">\\1</font>", $contents);
		} else {
			$contents = preg_replace(
				array(
					"/{{/", 
					"/}}\n/", 
					"/}}/", 
					"/\[\[/", 
					"/\]\]/",
					'/(^\s*@(.*)$)/m',
					'/\[:/',
					'/:\]/',
					),
				array(
					'<font color="#FFFF00">{{',
					'}}</font>',
					'}}</font>',
					'<font color="#D0D0D0"><font color="#FFFF00">[[</font>',
					'<font color="#FFFF00">]]</font></font>',
					'<font color="#FFFF00">\\1</font>',
					'<font color="#FFFF00">[:',
					':]</font>',
					),
				$contents
			);
		}
		return($contents);
	}
}

class ExampleManager {
	public static function getList() {
		$dh = opendir('examples/');
		$examples = array();
		while (($file = readdir($dh)) !== False) {
			if ($file[0] == '.') {
				continue;
			}
			$examples[] = new Example($file);
		}
		closedir($dh);
		return($examples);
	}
}

class ExampleBrowserController {
	public function ExampleBrowserController() {
		$this->templum = new Templum('view');
	}

	public function list_() {
		$examples = ExampleManager::getList();

		$tpl = $this->templum->template('list');
		return($tpl->render(compact('examples')));
	}

	public function view() {
		$rvar_example = $_GET['example'];
		$example = new Example(basename($rvar_example));

		$tpl = $this->templum->template('view');
		return($tpl->render(compact('example')));
	}
}
?>
