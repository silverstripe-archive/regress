<?php
class RegressReport extends SS_Report {
	
	protected $title = "Regress";
	
	static $na_label = "-";
	
	function getCMSFields() {
		Requirements::javascript("regress/javascript/RegressReport.js");
		return parent::getCMSFields(); 
	}
	
	function sourceRecords($params, $sort, $limit) {
		$sessions = new DataObjectSet(); 
		
		$testGroupID = isset($params["TestGroup"]) ? $params["TestGroup"] : null;

		if($testGroupID) {
			$testPlans = DataObject::get("SiteTree", "\"ParentID\" = $testGroupID", $sort);
		}
		else {
			$testPlans = new DataObjectSet(); 
		}
		
		foreach($testPlans as $plan) {
			$this->prepareTestPlan($plan);
			$sessions->merge($plan); 
			
			foreach($plan->Sections as $section) {
				$sessions->merge($section);
			}
		}
		return $sessions->getRange($limit['start'], $sessions->Count());
	}
	
	/**
	 * Prepare all data from a test plan for the report
	 */ 
	protected function prepareTestPlan($plan) {
		$plan->PlanTitle = $plan->Title;
		$plan->Sections = new DataObjectSet(); 
		
		foreach($plan->Children() as $section) {
			$plan->Sections->merge($section); 
			$plan->Total += $this->prepareTestFeature($section);
		}
		
		$sessions = $plan->getComponents("Sessions", "", "Created DESC"); 
		$lastSession = $sessions->First(); 
		
		if($lastSession) {
			$passes = $lastSession->Passes(); 
			if($passes) {
				$plan->Passes = $passes->Count();
			}
			else {
				$plan->Passes = 0; 
			}
			
			$failures = $lastSession->Failures(); 
			if($failures) {
				$plan->Failures = $failures->Count();
			}
			else {
				$plan->Failures = 0; 
			}
			
			$skips = $lastSession->Skips(); 
			if($skips) {
				$plan->Skips = $skips->Count();
			}
			else {
				$plan->Skips = 0; 
			}
			
			$plan->Date = $lastSession->LastEdited;
		}
		else {
			$plan->Passes = self::$na_label;
			$plan->Failures = self::$na_label;
			$plan->Skips = self::$na_label;
			$plan->Date = self::$na_label;
		}
	}
	
	/**
	 * Prepare all data from a test section for the report
	 * NOTE: This function returns a value because calling TestSection::getAllTestSteps() twice 
	 * 		 (on calling method and on this method) on the same object causes the function 
	 *		 to return wrong number of steps in the case where parent feature doesn't 
	 * 		 any step and child feature does. 
	 * @return int | total number of steps in all children
	 */
	protected function prepareTestFeature($feature) {
		$feature->FeatureTitle = $feature->Title; 
		$feature->Total = $feature->getAllTestSteps()->Count();		
		
		$sessions = $feature->getComponents("Sessions", "", "Created DESC"); 
		$lastSession = $sessions->First();
		
		if($lastSession) {
			$passes = $lastSession->Passes(); 
			if($passes) {
				$feature->Passes = $passes->Count();
			}
			else {
				$feature->Passes = 0; 
			}
			
			$failures = $lastSession->Failures(); 
			if($failures) {
				$feature->Failures = $failures->Count();
			}
			else {
				$feature->Failures = 0; 
			}
			
			$skips = $lastSession->Skips(); 
			if($skips) {
				$feature->Skips = $skips->Count();
			}
			else {
				$feature->Skips = 0; 
			}
			
			$feature->Date = $lastSession->LastEdited;
		}
		else {
			$feature->Passes = self::$na_label;
			$feature->Failures = self::$na_label;
			$feature->Skips = self::$na_label;
			$reature->Date = self::$na_label;
		}
		
		return $feature->Total; 
	}
	
	function columns() {
		$fields = array(
			"PlanTitle" => array(
				"title" => "Project",
				"formatting" => '<a title=\"Go to test\" href=\"admin/show/$ID\">$PlanTitle</a>'
			),
			"FeatureTitle" => array(
				"title" => "Plan/Feature",
				"formatting" => '<a title=\"Go to test\" href=\"admin/show/$ID\">$FeatureTitle</a>'
			),
			"Total" => array(
				"title" => "Number of tests"
			),
			"Passes" => array(
				"title" => "Passed"
			),
			"Failures" => array(
				"title" => "Failed"
			),
			"Skips" => array(
				"title" => "Skipped"
			),
			"Date" => array(
				"title" => "Last session date"
			)
		);
		
		return $fields;
	}
	
	function parameterFields() {
		$pages = DataObject::get("SiteTree", "\"ParentID\" = 0 AND \"ClassName\" <> 'ErrorPage' AND \"URLSegment\" <> 'home'"); 
		
		return new FieldSet(
			new DropdownField("TestGroup", "Test Group", $pages->map())
		);
	}
}
