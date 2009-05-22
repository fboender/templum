<?php

class TestTemplum extends TestTemplum_all {

	function Engine_GetNonexistingTemplate($test) {
		// Retrieve a non-existing template.
		try {
			$tpl = $this->templum->template("GetNonexistingTemplate");
			$test->failed('TemplumException not raised');
		} catch (TemplumError $e) {
			$test->passed();
		}
	}

	function Error_Notice($test) {
		// Test if we can intercept Notice errors in a template.
		$tpl = $this->templum->template('Error_Notice');
		try {
			$out = $tpl->render();
			$test->failed('TemplumTemplateException not raised');
		} catch (TemplumTemplateError $e) {
			$test->passed();
		}
	}
}

?>
