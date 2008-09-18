<?php

class TestStep extends DataObject {
	static $db = array(
		"Step" => "Text",
		'Sort' => 'Int', 
	);
	static $has_one = array(
		"Parent" => "TestSection",
	);
	
	function KnownIssues() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'fail' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function PassNotes() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'pass' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function SkipNotes() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'skip' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function StepNote() {
		return ( $this->KnownIssues() || $this->PassNote() || $this->SkipNotes() );
	}
}

?>