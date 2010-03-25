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

	static $permission_denied_text = "We are sorry, but you don't have permissions to access this page.";

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
	
	
	/**
	 * Returns the children of this feature (which are other TestSections/features).
	 * This getter has no dedicated purpose except for readability in the templates.
	 * 
	 * @return DataObjectSet
	 */
	function getTestSections() {
		return $this->Children();
	}

}

/**
 * Controller class for the test-plan. The controller handles the 
 * 'perform test' action, triggered by the CMS user in the back-end.
 * Perform-tests will use the TestPlan_perform template to render the HTML
 * page.
 *
 * @todo use page_controller instead of Controller. Not working, don't load data() correctly.
 */ 
class TestPlan_Controller extends Controller {

	static $allowed_actions = array(
		'perform',
		'error'
	);	
	
	/**
	 * Init method.
	 */
	function init() {		
		HTTP::set_cache_age(0);
		parent::init();

		if (!Member::currentUser()) {
			return Security::permissionFailure($this, "Please log into the page before accessing this page.");
		}

		$testplan = $this->TestPlan();

		if ($testplan) {
			if (!$testplan->canView(Member::currentUser())) {
				Director::redirect('testplan/error');
				return;
			}
		}
		
		// add required javascript
		Requirements::javascript(THIRDPARTY_DIR."/behaviour/behaviour.js");
		Requirements::javascript(THIRDPARTY_DIR."/prototype/prototype.js");
		
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-form/jquery.form.js");		
		
		Requirements::javascript("regress/javascript/jquery.jeditable.js");
		Requirements::javascript("regress/javascript/jquery.changeawareform.js"); 
		Requirements::javascript("regress/javascript/TestPlan.js");
	}

	/**
	 * Returns the test plan of a given ID. The ID is passed in as a HTTP
	 * parameter.
	 * 
	 * @return TestPlan Instance of the testplan.
	 */
	function TestPlan() {		
		if (isset($this->urlParams['ID'])) {
			return DataObject::get_by_id("TestPlan", $this->urlParams['ID']);
		}
		return null;
	}

	/**
	 * Helper method which returns the test section data object. Used for 
	 * rendering the templates.
	 * It is used to access the base class for test-sections and test-plans,
	 * the page class and to retrieve the form object for the left panel.
	 *
	 * @return Page
	 */
	public function GetTestRootObject() {
		return $this->TestPlan();
	}	
	
	/**
	 * Returns a static text for the error page (TestPlan_error.ss). 
   	 * This can get extended when customised error messages are required.
 	 *
 	 * @return string Text which gets populated into the TestPlan_error template.
	 */
	public function getPermissionDeniedMessage() {
		return TestPlan::$permission_denied_text;
	}
}
?>
