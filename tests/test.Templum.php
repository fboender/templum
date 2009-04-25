#!/usr/bin/php
<?php

error_reporting(E_ALL);

require_once('class.UnitTest.php');
require_once('../src/templum.php');

class TestTemplate {
	 private $templatePath = './test_data/templates/';

	 public function Engine_Create($test) {
		// Test if we can create a new template engine.
		$this->templum = new Templum($this->templatePath);
	}

	 public function Engine_GetTemplate($test) {
		// Test if we can retrieve a template.
		$tpl = $this->templum->template('Engine_GetTemplate');
	}

	 public function Engine_Cache($test) {
		// Test whether the cache is working. It should return the same object
		// instance for the same templates.
		$tpl1 = $this->templum->template('Engine_GetTemplate');
		$tpl2 = $this->templum->template('Engine_GetTemplate');

		$test->assert($tpl1 === $tpl2);
	}

	 public function Engine_CacheI18N($test) {
		// Test whether the cache is working for translated pages. It should
		// return the same object instance for the same template.
		$language = 'en_US';
		$templum = new Templum($this->templatePath, array(), 'en_US');

		$tpl1 = $templum->template('I18N_Basic');
		$tpl2 = $templum->template('I18N_Basic');

		$test->assert($tpl1 === $tpl2);
	}

	 public function Render_Basic($test) {
		// Check if the basic rendering is working.
		$tpl = $this->templum->template('Render_Basic');
		$out = $tpl->render();
		$test->assert(strpos($out, 'test1') !== False);
	}

	 public function Render_Accolade($test) {
		// Check if the rendering of {{$var}} works.
		$tpl = $this->templum->template('Render_Accolade');
		$out = $tpl->render(array('name'=>'ferry'));
		$test->assert(strpos($out, 'ferry') !== False);
	}

	 public function Render_AtLine($test) {
		// Check if the rendering of the @line syntax works.
		$names = array(
			'ferry',
			'sjaak',
			'piet',
		);
		$tpl = $this->templum->template('Render_AtLine');
		$out = $tpl->render(array('names'=>$names));
		$test->assert(
			strpos($out, 'ferry') !== False && 
			strpos($out, 'sjaak') !== False &&
			strpos($out, 'Piet was here') !== False
		);
	}
	
	 public function Render_BlockParen($test) {
		// Test if we can render block parenthesis content.
		$tpl = $this->templum->template('Render_BlockParen');
		$out = $tpl->render();
		assert($out == "\nXXSomething!XX\n");
	}

	 public function Render_Full($test) {
		// Test a template that does everything the template engine can handle.
		$username = 'fboender';
		$users = array(
			'fboender' => array('realname'=>'Ferry', 'age'=>28),
			'ssjaaksen' => array('realname'=>'Sjaak', 'age'=>79),
			'ppietersen' => array('realname'=>'Piet', 'age'=>19),
		);
		$tpl = $this->templum->template('Render_Full');
		$out = $tpl->render(compact('username', 'users'));
		$test->assert(
			strpos($out, 'fboender') !== False && 
			strpos($out, 'ssjaaksen') !== False &&
			strpos($out, 'ppietersen') !== False
		);
	}

	 public function Render_Newline($test) {
		$one = 'one';
		$tpl = $this->templum->template('Render_Newline');
		$out = $tpl->render(compact($one));
		$test->assert($out == "one\ntwo\nthree\n");
	}

	 public function Render_NoAutoEscapeGlobal($test) {
		$contents = '<h1>Test</h1>';
		$this->templum->setAutoEscape(False);
		$tpl = $this->templum->template('Render_NoAutoEscape');
		$out = $tpl->render(compact('contents'));
		$this->templum->setAutoEscape(True);
		$test->assert($out == "<h1>Test</h1>\n");
	}

	 public function Render_NoAutoEscapeParam($test) {
		$contents = '<h1>Test</h1>';
		$tpl = $this->templum->template('Render_NoAutoEscape', False);
		$out = $tpl->render(compact('contents'));
		$test->assert($out == "<h1>Test</h1>\n");
	}

	 public function Template_Function($test) {
		// Test if a public function in a template doesn't cause errors when we define
		// it twice
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('Template_Function');
		$out = $tpl->render();
		$test->assert($out == "in someFunction\n");
		$tpl = $templum->template('Template_Function');
		$out = $tpl->render();
		$test->assert($out == "in someFunction\n");
	}

	 public function Namespace_Simple($test) {
		// Test if a simple namespace (single dir) works.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('namespace/main');
		$out = $tpl->render();
		$test->assert($out == "namespace.main\n");
	}

	 public function Namespace_Deep($test) {
		// Test if a deeper namespace works.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('namespace/deep/main');
		$out = $tpl->render();
		$test->assert($out == "namespace:deep:main\n");
	}

	 public function I18N_Basic($test) {
		// Test if we can retrieve the main version of a template that also has
		// a translated version.
		$language = 'standaard'; // Not really a locale, just a test-string.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('I18N_Basic');
		$out = $tpl->render(compact('language'));
		$test->assert($out == "Dit is een test template in de standaard taal.\n");
	}

	 public function I18N_Translated($test) {
		// Test if we can retrieve a translated version of template given a
		// certain locale.
		$language = 'en_US';
		$templum = new Templum($this->templatePath, array(), 'en_US');
		$tpl = $templum->template('I18N_Basic');
		$out = $tpl->render(compact('language'));
		$test->assert($out == "This is a test template in the en_US language.\n");
	}

	 public function Error_Notice($test) {
		// Test if we can intercept Notice errors in a template.
		$tpl = $this->templum->template('Error_Notice');
		try {
			$out = $tpl->render();
			$test->failed(new Exception('TemplateException not raised'));
		} catch (TemplumTemplateError $e) {
			$test->passed();
		}
	}
}

$tester = new UnitTest('Templum', new TestTemplate());
print($tester->getResultsText(True));
?>
