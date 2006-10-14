<?php

class TestStep extends DataObject {
	static $db = array(
		"Step" => "Text",
	);
	static $has_one = array(
		"Parent" => "TestSection",
	);
}

?>