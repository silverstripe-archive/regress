<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 * TestSection => Feature/Story Card
 */
class TestSection extends Page {

	static $db = array(
		"Preparation" => "MarkdownText"
	);
	
	static $has_many = array(
		"Sessions" => "TestSessionObj",
		"Steps"    => "TestStep",
	);

	public static $singular_name = 'Feature';

	public static $plural_name   = 'Features';

	// This page type can not have any sub-page
	static $allowed_children = "none";

	// This page type can not be a root page.
	static $can_be_root      = false;


	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab("Root.Edit",new TextareaField("Preparation","Test Preparation (supports Marldown)") );
		
		// add report table field to the result tab
		if(is_numeric($this->ID)){
			// TODO Limit Textareafield to two lines (passing arguments currently doesn't work in TableField)
			$stepsTF = new TableField(
				"Steps", 
				"TestStep",
				array(
					"Step" => "Scenario (support Markdown)",
					'Sort' => 'Sort Order',
				), 
				array(
					"Step" => 'TextareaField',
					'Sort' => 'TextField', 
				), 
				null, // filterfield
				"ParentID = {$this->ID}", // filter
				true, // edit existing
				'Sort ASC' // sort
			);
			$stepsTF->setExtraData(array(
				'ParentID' => $this->ID
			));
			
				
			$fields->addFieldToTab("Root.Edit",$stepsTF);
		}else{
			$fields->addFieldToTab("Root.Edit", new Headerfield("Please save this before continuing",1));
		}		
		
		return $fields;
	}
	
	/**
	 * Extend CMS action handling: the only action allowed is to save the page.
	 *
	 * @return FieldSet
	 */
	function getAllCMSActions() {
		return new FieldSet(
			new FormAction("callPageMethod", "Perform test", null, "cms_performTest"),
			new FormAction("save", "Save changes")
		);
	}

	/** 
	 * Returns the Feature-Setup / preparation text as HTML text.
	 *
	 * @return string HTML text
	 */
	function PreparationMarkdown() {
		return Markdown($this->Preparation);		
	}
	
	/**
	 * Open the 'perform test' page.
	 */
	function PerformTestSection() {
		return $this->renderWith("PerformTestSection");
	}
	
	/**
	 * Returns all steps for this feature. 
	 */
	function Steps() {
		$steps = $this->getComponents(
			'Steps',
			null, // filter
			'Sort ASC'
		);
		return $steps;
	}

	/**
	 * Returns the url to the test-plan controller 
	 *
	 * @return string
	 */
	function getcontrollerurl() {
		return 'feature';
	}
	
	function GetTestPlan() {
		$stopMarker = 10;
		
		$testPlan = null;
		
		$parent = $this->getParent();
		
		while ($parent != null AND $stopMarker > 0) {
			$stopMarker--;
			if (is_a($parent, "TestPlan")) {
				$testPlan = $parent;
				$stopMarker = 0;
			}
			$parent = $parent->getParent();
		}
		return $testPlan;		
	}
		
	/**
	 * Returns javascript to performa test. This is a Ajax callback method which
	 * initiates the test execution on this plan.
	 *
	 * @return string JavaScript code to open a new window and render the test.
	 */
	function cms_performTest() {
		return $this->renderWith('js_performTest');
	}

	/**
	 * Returns the test session object of a given ID. The ID is passed in as a
	 * HTTP parameter.
	 * 
	 * @return TestSessionObj|Null Instance of the session object.
	 */
	function TestSessionObj() {
		$obj = null;

		$OtherID = Controller::curr()->urlParams['OtherID'];
		if($OtherID) {
			$obj =  DataObject::get_by_id("TestSessionObj", $OtherID);
		}
		return $obj;
	}
}

/**
 * Test-Section controller
 *
 * Controller class for test-section (feature). It handles the perform-test
 * for a single feature.
 */
class TestSection_Controller extends Controller {
	
	function init() {		
		HTTP::set_cache_age(0);
		
		parent::init();

		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}

		Requirements::javascript("jsparty/behaviour.js");
		Requirements::javascript("jsparty/prototype.js");

		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-form/jquery.form.js");
		
		Requirements::javascript("regress/javascript/TestPlan.js");
	}

	/**
	 * Returns the requested Test-Section data object. The ID of the 
	 * data object needs to be passed into this method via the URL parameters.
	 *
	 * @return TestSection 
	 */
	public function TestSection() {
		$obj = DataObject::get_by_id("TestSection", $this->urlParams['ID']);
		return $obj;
	}

	/**
	 * Returns the test session object of a given ID. The ID is passed in as a
	 * HTTP parameter.
	 * 
	 * @return TestSessionObj Instance of the session object.
	 */
	function TestSessionObj() {
		if($this->urlParams['OtherID']) {
			return DataObject::get_by_id("TestSessionObj", $this->urlParams['OtherID']);
		}
	}	
}
?>