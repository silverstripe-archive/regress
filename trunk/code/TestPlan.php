<?php

class TestPlan extends Page {
	static $allowed_children = array("TestSection");
	static $default_child = "TestSection";
	
}

class TestPlan_Controller extends Page_Controller {
	
}

?>