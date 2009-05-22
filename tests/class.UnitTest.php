<?php
/*
 * Copyright 2007, Ferry Boender 
 *
 * This file is part of PHLite, a lightweight PHP framework. 
 *
 * PHLite is libre software; you can redistribute and/or modify it under the
 * terms of the MIT/X11 License. See the COPYING file in the PHLite
 * distribution package for more information.
 */

/**
 * @brief Very simple unit tester.
 * 
 * This class can be used as a very simple Unit tester. It simply runs all
 * methods on a class (both static and non-static) and catches all thrown
 * exceptions and PHP errors. Alternatively, you can call
 * <tt>$test->assert(ASSERTION);</tt> from the methods in the class to test for
 * things.
 *
 * If the test class you pass to the constructor contains a property named
 * 'testNames', it will be used to display pretty names for the methods (tests)
 * in the class. testNames should be public, should be an associative array
 * where the keys match the methodnames  and should look like this: 
 * <tt>$testNames = array("GroupName_TestName" => "test the foobar", ...); </tt>
 *
 * For each called method in the class to be tested, the first parameter passed
 * to the method will be the Tester class. You can use this to assert things
 * from your test case like this: 
 * <tt>function MyTestCase($test) { $test->assert(a != b); }</tt>
 * 
 * You can subdivide test methods in groups by putting underscores in the
 * method names. Methods are called in the order as they appear in the test
 * class but may be sorted by group in the final results.
 *
 * An example test class could look like this:
 *
 * @code
 * class TestMe {
 *   $testNames = array(
 *     "User_Load" => "Load a user",
 *     "User_Save" => "Save a user",
 *     "Grant_User" => "Give rights to a user",
 *     "Grant_Group" => "Give rights to a group",
 *   );
 *  
 *   function User_Load($test) {
 *     $this->user = new MyProgramUser("john");
 *     $test->assert($this->user->getUserId() == "john");
 *   }
 *   function User_Save($test) {
 *     $this->user->save();
 *   }
 *   function Grant_User($test) {
 *     $this->user->grantPrivilge(READ);
 *   }
 *   function Grant_Group($test) {
 *     $this->user->group->grantPrivilge(READ);
 *   }
 *   function User_Load_NonExisting($test) {
 *     // Test PASSES if an error is thrown! (it's supposed to do so)
 *     try {
 *       new MyProgramUser("AmeliaEarhart");
 *       $test->failed(new Exception("Non existing user loaded."));
 *     catch (Exception $e) {
 *       $test->passed();
 *     }
 *   }
 * }
 *
 * $tester = new UnitTest('MyApplication', new TestMe());
 * print($tester->getResultsTextPretty());
 * @endcode
 *
 * Reports can be generated from the results using the ->getResultsFOO() methods.
 *
 * @warning Please be advised that this class does strange things to error
 * reporting and handling. Do not be surprised if errors suddenly don't turn up
 * anymore after you've created an instance of this class.
 *
 * comment: Any special methods (anything that starts with __) will not be run
 * by UnitTest.
 */
class UnitTest {

	var $appName = "";
	var $cnt = 1;
	var $testOutput = array();
	var $testResults = array();
	var $currentTest = array();
	var $otherErros = "";
	var $prevErrorReporting = NULL;

	/**
	 * @brief Create a new Unit test controller
	 * @param $appName (string) The name of the application you're testing.
	 * @param $testClass (string or object instance) An instance of or name (when only using static methods) of a class to test.
	 * @param $useErrHndl (boolean, true) Wether to use an internal error handler to detect errors and mark the test as failed.
	 * @warning Make sure to destroy this class when you're done unit testing, or errors will not show up anymore.
	 */
	function UnitTest($appName, $testClass, $useErrHndl = True) {
		if (!is_object($testClass)) {
			trigger_error("\$testClass is not an instantiated object", E_USER_ERROR); die();
		}
		$this->prevErrorReporting = error_reporting(E_ALL);
		if ($useErrHndl) {
			set_error_handler(array(&$this, "errorHandler"));
		}
		$this->appName = $appName;
		$this->testClass = $testClass;
		$this->run($this->testClass);
	}

	/**
	 * @brief Destructor.
	 */
	function __destruct() {
		restore_error_handler();
		// Do _not_ restore the error level if it wasn't set by us. 
		if ($this->prevErrorReporting !== NULL) {
			error_reporting($this->prevErrorReporting);
		}
	}

	/**
	 * @brief The custom error handler.
	 * @note DO NOT USE THIS, ITS FOR INTERNAL USE ONLY.
	 */
	function errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		if ($this->currentTest == array()) {
			$this->otherErrors .= "$filename($linenum): Error $errno: '$errmsg'\n";
		} else {
			$this->failed($errmsg, $errno);
		}
	}

	/**
	 * @brief The custom exception handler. Don't use this.
	 * @note DO NOT USE THIS, ITS FOR INTERNAL USE ONLY.
	 */
	function exceptionHandler($e) {
		$errmsg = $e->getMessage();
		$errno = $e->getCode();
		$this->failed($errmsg, $errno);
	}

	function run($testClass) {
		$props = get_object_vars($testClass);
		if (array_key_exists("testNames", $props)) {
			$hasNames = true;
		} else {
			$hasNames = false;
		}
		foreach(get_class_methods($this->testClass) as $method) {
			if (substr($method, 0, 2) != "__") {
				if (strpos($method, '_') !== false) {
					list($group, $name) = explode("_", $method, 2);
				} else {
					$group = "";
					$name = $method;
				}
				if ($hasNames && array_key_exists($method, $this->testClass->testNames)) {
					$name = $this->testClass->testNames[$method];
				}

				// Start the test
				$this->start($group, $name);
				$testClass->{$method}($this);
				$this->end();
			}
		}
	}

	function start($testGroup, $testName) {
		$this->currentTest = array(
			"group"  => $testGroup,
			"nr"     => $this->cnt++,
			"name"   => $testName,
			"result" => "",
			"dump"   => "",
		);
		$this->passed();
	}

	/**
	 * @brief Mark a single test as having passed. 
	 */
	function passed() {
		$this->currentTest["passed"] = true;
		$this->currentTest["result"] = "";
		$this->currentTest["dump"] = "";

		$this->currentTest = array_merge($this->currentTest, array(
			"passed" => true,
			)
		);
	}

	/**
	 * @brief Mark a single test as having failed. It will mark the test as such and generate a (useable) backtrace.
	 * @param $e (Exception) The exception that occured.
	 * @note This is also called by the custom error handler, which mimics a thrown exception
	 * @note You can safely call this method to set the default pass/fail status of a test method and later on in the test mark it as having passed.
	 */
	function failed($errmsg, $errcode) {
		$this->currentTest["passed"] = false;
		$this->currentTest["result"] .= $errmsg."\n";
		$this->currentTest["dump"] .= "\n";
	}

	/**
	 * @brief Assert a boolean expression. If it returns 'true', the test will be marked as passed. Otherwise it will be marked as failed.
	 * @param $bool (Boolean) Boolean result of an expression.
	 */
	function assert($bool) {
		if ($bool) {
			$this->passed();
		} else {
			trigger_error("Assertion failed", E_USER_ERROR);
		}
	}

	function end() {
		$this->testResults[] = $this->currentTest;
		$this->currentTest = array();
	}

	function sortResultsByGroup() {
		$cmp = create_function('$a,$b', '
			if ($a["group"] == $b["group"]) {
				return (0); 
			}; 
			return($a["group"] > $b["group"] ? -1 : 1);
		');
		usort($this->testResults, $cmp);
	}

	/**
	 * @brief Generate a beautiful dump of the test results in HTML format. This returns output that is a single HTML page.
	 * @param $hidePassed (Boolean) If true, passed tests will not be shown. This is useful when you've got alot of testcases and only want the failed ones to show up.
	 * @param $sortGroups (Boolean) If true, the results will be sorted by group. Otherwise the results will be listed in the same order they where executed.
	 */
	function getResultsHtml($hidePassed = false, $sortGroups = false) {
		if ($sortGroups) { 
			$this->sortResultsByGroup();
		}
		$out = "
			<html>
				<body>
					<style>
						body { font-family: sans-serif; }
						table { border: 1px solid #000000; }
						th { empty-cells: show; font-family: sans-serif; border-left: 1px solid #FFFFFF; border-top: 1px solid #FFFFFF; border-bottom: 1px solid #000000; border-right: 1px solid #000000; margin: 0px; font-size: x-small; color: #FFFFFF; background-color: #404040; padding: 2px 4px 2px 4px; }
						td { empty-cells: show; font-family: sans-serif; border-bottom: 1px solid #909090; margin: 0px; padding: 2px 4px 2px 4px; border-left: 1px solid #E0E0E0; font-size: x-small; }
						div.dump { background-color: #F0F0F0; }
						a.dump { text-decoration: underline; cursor: pointer; }
					</style>
				</body>
				<h1>Test results for ".$this->appName."</h1>
				<h2>Test results</h2>
				<table cellspacing='0' cellpadding='0'>
					";
					$prevResult = $this->testResults[0];
					$nrOfPassed = 0;
					$nrOfFailed = 0;
					foreach ($this->testResults as $result) {
						if ($result["passed"] == false) {
							$nrOfFailed++;
							$textResult = "FAILED";
							$rowColor = "#FF0000";
							$dump = "<a class='dump' onclick='document.getElementById(\"dump_".$result["nr"]."\").style.display=\"block\"'>Dump</a><div style='border: 1px solid #000000; background-color: #F0F0F0; display:none;' class='dump' id='dump_".$result["nr"]."'><pre>".$result["dump"]."</pre></div>";
						} else {
							$nrOfPassed++;
							if ($hidePassed) {
								continue;
							}
							$textResult = "passed";
							$rowColor = "#50FF00";
							$dump = "";
						}
						if ($result["group"] != $prevResult["group"]) {
							$out .= "
							<tr valign='top' align='left'>
								<td colspan='6'></td>
							</tr>\n";
						}
						$out .= "
							<tr valign='top' align='left'>
								<th>".$result["nr"]."</th>
								<th>".$result["group"]."</th>
								<th>".$result["name"]."</th>
								<td bgcolor='".$rowColor."'>".$textResult."</td>
								<td>".str_replace("\n", "<br />\n", $result["result"])."</td>
								<td>".$dump."</td>
							</tr>\n";

						$prevResult = $result;
					}
					$out .= "
				</table>
				<h2>Total results</h2>
				<table cellspacing='0' cellpadding='0'>
					<tr><th>Passed:</th><td>".$nrOfPassed."</td></tr>
					<tr><th>Failed:</th><td>".$nrOfFailed."</td></tr>
				</table>
				<h2>Non-testcase errors</h2>
				<pre>\n".$this->otherErrors."
				</pre>
			</html>
		";

		return($out);
	}

	/**
	 * @brief Generate a dump of the test results in Text format, seperated with tabs between each field.
	 * @param $hidePassed (Boolean) If true, passed tests will not be shown. This is useful when you've got alot of testcases and only want the failed ones to show up.
	 * @param $sortGroups (Boolean) If true, the results will be sorted by group. Otherwise the results will be listed in the same order they where executed.
	 */
	function getResultsText($hidePassed = false, $sortGroups = false) {
		if ($sortGroups) { 
			$this->sortResultsByGroup();
		}
		$fmt = "%s\t%s\t%s\t%s\n";
		$out = "";
		foreach ($this->testResults as $result) {
			if ($result["passed"] == false) {
				$textResult = "FAILED";
			} else {
				if ($hidePassed) {
					continue;
				}
				$textResult = "passed";
			}
			$out .= sprintf($fmt, $result["nr"], $result["group"].":".$result["name"], $textResult, str_replace("\n", "", $result["result"]));
		}
		return($out);
	}

	/**
	 * @brief Generate a beautiful dump of the test results in Text format. 
	 * @param $hidePassed (Boolean) If true, passed tests will not be shown. This is useful when you've got alot of testcases and only want the failed ones to show up.
	 * @param $sortGroups (Boolean) If true, the results will be sorted by group. Otherwise the results will be listed in the same order they where executed.
	 */
	function getResultsTextPretty($hidePassed = false, $sortGroups = false) {
		if ($sortGroups) { 
			$this->sortResultsByGroup();
		}
		$fmt = "%3s | %-60s | %-6s | %s\n";
		$sep = "%3s-+-%-60s-+-%-6s-+-%s\n";

		$out = "";
		$out .= sprintf($fmt, "Nr", "Test", "Passed", "Result");
		$out .= sprintf($sep, str_repeat('-', 3), str_repeat('-', 60), str_repeat('-', 6), str_repeat('-', 40));
		foreach ($this->testResults as $result) {
			if ($result["passed"] == false) {
				$textResult = "FAILED";
			} else {
				if ($hidePassed) {
					continue;
				}
				$textResult = "passed";
			}
			$out .= sprintf($fmt, $result["nr"], $result["group"].":".$result["name"], $textResult, str_replace("\n", "", $result["result"]));
		}
		return($out);
	}
}

?>
