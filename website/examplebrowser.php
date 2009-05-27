<?php

class Example {
	public $allowedExtensions = array('php', 'tpl', 'css');
	public function Example($name) {
		$this->name = basename($name);
		$this->readme = explode("\n", file_get_contents('examples/'.$this->name.'/README.txt'));
		$this->title = $this->readme[0];
		$this->description = implode("\n", array_slice($this->readme, 1));
	}

	public function getFiles() {
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
				$contents = file_get_contents($path);
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
