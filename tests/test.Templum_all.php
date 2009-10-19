<?php

class TestTemplum_all {
	var $templatePath = './test_data/templates/';
	var $templum = NULL;

	function TestTemplum_all() {
		$this->templum = new Templum($this->templatePath);
	}

	function Engine_Create($test) {
		// Test if we can create a new template engine.
		$templum = new Templum($this->templatePath);
	}

	function Engine_GetTemplate($test) {
		// Test if we can retrieve a template.
		$tpl = $this->templum->template('Engine_GetTemplate');
	}

	function Engine_Cache($test) {
		// Test whether the cache is working. It should return the same object
		// instance for the same templates.
		$tpl1 = $this->templum->template('Engine_GetTemplate');
		$tpl2 = $this->templum->template('Engine_GetTemplate');

		$test->assert($tpl1 === $tpl2);
	}

	function Engine_CacheI18N($test) {
		// Test whether the cache is working for translated pages. It should
		// return the same object instance for the same template.
		$language = 'en_US';
		$templum = new Templum($this->templatePath, array(), 'en_US');

		$tpl1 = $templum->template('I18N_Basic');
		$tpl2 = $templum->template('I18N_Basic');

		$test->assert($tpl1 === $tpl2);
	}

	function Render_Basic($test) {
		// Check if the basic rendering is working.
		$tpl = $this->templum->template('Render_Basic');
		$out = $tpl->render();
		$test->assert(strpos($out, 'test1') !== False);
	}

	function Render_Accolade($test) {
		// Check if the rendering of {{$var}} works.
		$tpl = $this->templum->template('Render_Accolade');
		$out = $tpl->render(array('name'=>'ferry'));
		$test->assert(strpos($out, 'ferry') !== False);
	}

	function Render_AtLine($test) {
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
	
	function Render_BlockParen($test) {
		// Test if we can render block parenthesis content.
		$tpl = $this->templum->template('Render_BlockParen');
		$out = $tpl->render();
		assert($out == "\nXXSomething!XX\n");
	}

	function Render_Full($test) {
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

	function Render_Newline($test) {
		$one = 'one';
		$tpl = $this->templum->template('Render_Newline');
		$out = $tpl->render(compact($one));
		$test->assert($out == "one\ntwo\nthree\n");
	}

	function Render_NoAutoEscapeGlobal($test) {
		$contents = '<h1>Test</h1>';
		$this->templum->setAutoEscape(False);
		$tpl = $this->templum->template('Render_NoAutoEscape');
		$out = $tpl->render(compact('contents'));
		$this->templum->setAutoEscape(True);
		$test->assert($out == "<h1>Test</h1>\n");
	}

	function Render_NoAutoEscapeParam($test) {
		$contents = '<h1>Test</h1>';
		$tpl = $this->templum->template('Render_NoAutoEscape', False);
		$out = $tpl->render(compact('contents'));
		$test->assert($out == "<h1>Test</h1>\n");
	}

	function Render_UTF8($test) {
		$tpl = $this->templum->template('Render_UTF8');
		$test->assert(mb_detect_encoding($tpl->render()) == "UTF-8");
	}

	function Template_Function($test) {
		// Test if a function in a template doesn't cause errors when we define
		// it twice
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('Template_Function');
		$out = $tpl->render();
		$test->assert($out == "in someFunction\n");
		$tpl = $templum->template('Template_Function');
		$out = $tpl->render();
		$test->assert($out == "in someFunction\n");
	}

	function Namespace_Simple($test) {
		// Test if a simple namespace (single dir) works.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('namespace/main');
		$out = $tpl->render();
		$test->assert($out == "namespace.main\n");
	}

	function Namespace_Deep($test) {
		// Test if a deeper namespace works.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('namespace/deep/main');
		$out = $tpl->render();
		$test->assert($out == "namespace:deep:main\n");
	}

	function I18N_Basic($test) {
		// Test if we can retrieve the main version of a template that also has
		// a translated version.
		$language = 'standaard'; // Not really a locale, just a test-string.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('I18N_Basic');
		$out = $tpl->render(compact('language'));
		$test->assert($out == "Dit is een test template in de standaard taal.\n");
	}

	function I18N_Translated($test) {
		// Test if we can retrieve a translated version of template given a
		// certain locale.
		$language = 'en_US';
		$templum = new Templum($this->templatePath, array(), 'en_US');
		$tpl = $templum->template('I18N_Basic');
		$out = $tpl->render(compact('language'));
		$test->assert($out == "This is a test template in the en_US language.\n");
	}

	function Inherit_Basic($test) {
		// Test if a basic inheritance works.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('Inherit_Basic_Child');
		$out = $tpl->render();
		$test->assert($out == "<h1>Child</h1>\n");
	}

	function Inherit_Noblock($test) {
		// Test if a parent block is rendered if child does not specify the block.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('Inherit_Basic_Noblock');
		$out = $tpl->render();
		$test->assert($out == "<h1>Main</h1>\n");
	}

	function Include_Basic($test) {
		// Test if includes work.
		$templum = new Templum($this->templatePath, array());
		$tpl = $templum->template('Include_Parent');
		$out = $tpl->render();
		$test->assert($out == "child\n");

	}
}

?>
