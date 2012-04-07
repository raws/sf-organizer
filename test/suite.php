<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "/usr/lib/php/pear");
require_once "lib/simpletest/autorun.php";

class OrganizerTestSuite extends TestSuite {
	function __construct() {
		parent::__construct();
		$this->TestSuite("All tests");
		$this->add_test_files("unit");
	}
	
	private function add_test_files($type) {
		foreach ($this->find_test_files($type) as $test) {
			$this->addFile($test);
		}
	}
	
	private function find_test_files($path) {
		return glob($path . "/*_test.php");
	}
}
?>
