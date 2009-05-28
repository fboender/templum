<?php

error_reporting(E_ALL);

require_once('templum.php');
require_once('examplebrowser.php');

$templum = new Templum('view');
$templum->setVar('tags', array(
	'start_echo' => '{{',
	'end_echo' => '}}',
	'start_block' => '[[',
	'end_block'=> ']]',
));


class Route {
	public function Route($requestParts = NULL) {
		if (!array_key_exists('_ROUTE', $GLOBALS)) {
			$requestURI = strtolower(trim($_SERVER['REQUEST_URI'], '/'));
			if (!empty($requestURI)) {
				$GLOBALS['_ROUTE'] = explode('/', $requestURI);
			}
		}

		if (empty($GLOBALS['_ROUTE'])) {
			$this->index();
		} else {
			$curController = array_shift($GLOBALS['_ROUTE']);
			if (is_callable(array($this, $curController))) {
				$this->{$curController}();
			} else {
				$this->other($curController);
			}
		}
	}
}

class Examples extends Route {
	public function Examples($templum) {
		$this->templum = $templum;
		$this->examples = new ExampleManager();
		parent::Route();
	}

	public function index() {
		$examples = $this->examples->getList();
		$tpl = $this->templum->template('examples/list');
		print($tpl->render(compact('examples')));
	}

	public function other($example) {
		$example = new Example(basename($example));

		$tpl = $this->templum->template('examples/view');
		print($tpl->render(compact('example')));
	}
}

class Site extends Route {
	public function Site($templum) {
		$this->templum = $templum;
		parent::Route();
	}
	public function index() {
		$this->about();
	}
	public function about() {
		$examplemanager = new ExampleManager();
		$syntaxExample = $examplemanager->getExample('examples/all_syntax');
		$tpl = $this->templum->template('about');
		print($tpl->render(compact('syntaxExample')));
	}
	public function examples() {
		$examples = new Examples($this->templum);
	}
	public function documentation() {
		$tpl = $this->templum->template('documentation');
		print($tpl->render());
	}
	public function download() {
		$tpl = $this->templum->template('download');
		print($tpl->render());
	}
	public function development() {
		$tpl = $this->templum->template('development');
		print($tpl->render());
	}
	public function license() {
		$tpl = $this->templum->template('license');
		print($tpl->render());
	}
}

$site = new Site($templum);

?>
