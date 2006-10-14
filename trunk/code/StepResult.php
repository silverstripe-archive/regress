<?php

class StepResult extends DataObject {
	static $db = array(
		"Outcome" => "Enum('pass,fail,','')",
		"FailReason" => "Text",
	);
	static $has_one = array(
		"TestSession" => "TestSession",
		"TestPlan" => "TestPlan",
		"TestStep" => "TestStep",
	);
	
}

?>