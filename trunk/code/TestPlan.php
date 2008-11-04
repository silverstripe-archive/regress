<?php

class TestPlan extends Page {
	static $allowed_children = array("TestSection");
	static $default_child = "TestSection";
	
	static $has_many = array(
		"Sessions" => "TestSessionObj",
	);
	
	function getCMSFields() {
		$sessionTableFields = array(
		   "ID" => "Session #", 
		   "Created" => "Date", 
		   "NumPasses" => "# Passes", 
		   "NumFailures" => "# Failures",
		   "NumSkips" => "# Skips",
		   'Author.Title' => 'Author', 
		);
		
		$fields = parent::getCMSFields();
		if(is_numeric($this->ID)){
			$sessionReport = new TableListField(
			   "Sessions", 
			   "TestSessionObj",
				$sessionTableFields, 
				"TestPlanID = '$this->ID'", 
				"Created DESC"
			);
			$sessionReport->setPermissions(array('edit','show','delete'));
			
			//$sessionReport->setClick_PopupLoad("testplan/reportdetail/$this->ID/"); 
			$url = '<a target=\"_blank\" href=\"' . Director::baseURL() . 'testplan/reportdetail/'.$this->ID.'/$ID\">$value</a>';
			$sessionReport->setFieldFormatting(array_combine(array_keys($sessionTableFields), array_fill(0,count($sessionTableFields), $url)));
			
			$fields->addFieldToTab("Root.Results", $sessionReport);
		}else{
			$fields->addFieldToTab("Root.Results", new Headerfield("Please save this before continuing",1));
		}
		
		return $fields;
	}

	function getAllCMSActions() {
		return new FieldSet(
			new FormAction("callPageMethod", "Perform test", null, "cms_performTest"),
			new FormAction("save", "Save changes")
		);
	}

	function cms_performTest() {
		return <<<JS
			
			var w = window.open(baseHref() + "testplan/perform/$this->ID/" , "performtest");
			if (!w) {
				alert('Please allow popup for this site.');
			}
			else {
				w.focus();
			}
JS;
	}	
}

class TestPlan_Controller extends Controller {
	function init() {
		HTTP::set_cache_age(0);
		parent::init();
		Requirements::javascript("jsparty/behaviour.js");
		Requirements::javascript("jsparty/prototype.js");
		Requirements::javascript("regress/javascript/TestPlan.js");
	}
	
	function TestPlan() {
		return DataObject::get_by_id("TestPlan", $this->urlParams['ID']);
	}
	function TestSessionObj() {
		if($this->urlParams['OtherID']) {
			return DataObject::get_by_id("TestSessionObj", $this->urlParams['OtherID']);
		}
	}


	function Notes() {
		$planID = (int)$this->urlParams['ID'];

		// If we're viewing one session, then show that session's notes
		if($obj = $this->TestSessionObj()) return $obj->Notes();
		// Otherwise, view all unresolved notes
		else return DataObject::get("StepResult", "TestPlanID = $planID AND (Outcome = 'fail' OR (Outcome IN ('pass','skip') AND Note != '' AND Note IS NOT NULL)) 
			AND ResolutionDate IS NULL");
	}
		
	function saveperformance() {
		// if there's no outcomes was set the redirect to the same page
		if (!isset($_REQUEST['Outcome'])) {
			Director::redirect("testplan/perform/" . $this->urlParams['ID']);
			return;
		}
		
		// get test session object data
		$testSessionData = array();
		if (isset($_REQUEST['Tester'])) { 
			$testSessionData["Tester"] = $_REQUEST['Tester'];
		}
		if (isset($_REQUEST['OverallNote'])) { 
			$testSessionData["OverallNote"] = $_REQUEST['OverallNote'];
		}
		$testSessionData["TestPlanID"] = $this->urlParams['ID'];
		$session = new TestSessionObj($testSessionData);
		$session->write();
		
		foreach($_REQUEST['Outcome'] as $stepID => $outcome) {
			$result = new StepResult();
			$result->TestStepID = $stepID;
			$result->TestPlanID = $this->urlParams['ID'];
			$result->TestSessionID = $session->ID;
			$result->Outcome = $outcome;
			//if ($outcome=='pass') $result->ResolutionDate = date('Y-m-d h:i:s');
			$result->Note = $_REQUEST['Note'][$stepID];
			$result->write();
		}

		Director::redirect("testplan/reportdetail/" . (int)$_REQUEST['TestPlanID'] . "/$session->ID");
	}
	
}

?>
