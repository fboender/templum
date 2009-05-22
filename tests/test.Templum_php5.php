<?php

class TestTemplum extends TestTemplum_all {

	function Error_Notice($test) {
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

?>
