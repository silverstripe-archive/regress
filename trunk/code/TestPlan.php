<?php
/**
 * @package regress
 * @subpackage code
 */ 

/**
 * A Test plan is a page type which groups features (or story cards). 
 *
 * Reference: {@see TestSection}
 */
class TestPlan extends Page {
	
	static $allowed_children = array("TestSection");
	
	static $default_child = "TestSection";
	
	static $has_many = array(
		"Sessions" => "TestSessionObj",
	);

	/**
	 * Returns a FieldSet with which to create the CMS editing form.
	 *
	 * @return FieldSet The fields to be displayed in the CMS.
	 */	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		return $fields;
	}

	/**
	 * Get the actions available in the CMS for this page:  save and perform test.
	 *
	 * @return FieldSet The available actions for this page.
	 */
	function getAllCMSActions() {
		$url = $this->baseHref().$this->getcontrollerurl()."/perform/".$this->ID;
		return new FieldSet(
			
			new LiteralField("Link", "<a href='".$url."' target='performtest_$this->title'>Perform Test</a>"),
//			new FormAction("callPageMethod", "Perform test", null, "cms_performTest"),
			new FormAction("save", "Save changes")
		);
	}

	/**
	 * Returns the url to the test-plan controller 
	 *
	 * @return string
	 */
	function getcontrollerurl() {
		return 'testplan';
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
 * Controller class for the test-plan. The controller handles the 
 * 'perform test' action, triggered by the CMS user in the back-end.
 * Perform-tests will use the TestPlan_perform template to render the HTML
 * page.
 */ 
class TestPlan_Controller extends Controller {
	
	/**
	 * Init method.
	 */
	function init() {		
		HTTP::set_cache_age(0);
		parent::init();

		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}
		
		// add required javascript
		Requirements::javascript(THIRDPARTY_DIR."/behaviour/behaviour.js");
		Requirements::javascript(THIRDPARTY_DIR."/prototype/prototype.js");
		
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-form/jquery.form.js");
		
		Requirements::javascript("regress/javascript/jquery.jeditable.js");
		Requirements::javascript("regress/javascript/TestPlan.js");
	}

	/**
	 * Returns the test plan of a given ID. The ID is passed in as a HTTP
	 * parameter.
	 * 
	 * @return TestPlan Instance of the testplan.
	 */
	function TestPlan() {		
		return DataObject::get_by_id("TestPlan", $this->urlParams['ID']);
	}
}
?>
