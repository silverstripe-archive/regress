<?php

class TestSection extends Page {
	static $allowed_children = array("TestSection");
	static $default_child = "TestSection";
	static $can_be_root = false;
		
	static $has_many = array(
		"Steps" => "TestStep",
	);
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		if(is_numeric($this->ID)){
			// TODO Limit Textareafield to two lines (passing arguments currently doesn't work in TableField)
			$fields->addFieldToTab("Root.Edit", 
				$stepsTF = new TableField(
					"Steps", 
					"TestStep", 
					array(
						"Step" => "Test Steps"
					), 
					array(
						"Step" => 'TextareaField'
					), 
					null, 
					"ParentID = {$this->ID}"
				)
			);
			$stepsTF->setExtraData(array(
				'ParentID' => $this->ID
			));
		}else{
			$fields->addFieldToTab("Root.Edit", new Headerfield("Please save this before continuing",1));
		}
		return $fields;
	}
	
	function PerformTestSection() {
		return $this->renderWith("PerformTestSection");
	}
}

class TestSection_Controller extends Page_Controller {
	
}
?>