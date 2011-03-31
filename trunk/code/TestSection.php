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
		"FeatureID" => "VarChar(256)",
		"Preparation" => "MarkdownText",
		"TestData" => "MarkdownText",
		"Notes" => "MarkdownText"
	);
	
	static $has_many = array(
		"Sessions" => "TestSessionObj",
		"Steps"    => "TestStep",
	);

	public static $singular_name = 'Feature';

	public static $plural_name   = 'Features';

	// This page type can not have any sub-page
	static $allowed_children = array("TestSection");

	// This page type can not be a root page.
	static $can_be_root      = false;


	/** 
	 * Returns the Feature-Setup / preparation text as HTML text.
	 *
	 * @return string HTML text
	 */
	function GetPreparationMarkdown() {
		return MarkdownText::render($this->Preparation);		
	}

	/**
	 * Returns the url to the test-plan controller 
	 *
	 * @return string
	 */
	function getcontrollerurl() {
		return 'feature';
	}	

	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab("Root.Edit",new TextField("FeatureID","Feature ID"),"Content" );
		$fields->addFieldToTab("Root.Edit",new TextareaField("Preparation","Test Preparation (supports Markdown)") );
		$fields->addFieldToTab("Root.Edit",new TextareaField("TestData","Test Data (supports Markdown)") );
		$fields->addFieldToTab("Root.Edit",new TextareaField("Notes","Notes (supports Markdown)") );
		
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
		$urlReport = $this->baseHref().$this->getcontrollerurl()."/report/".$this->ID;
		$url = $this->baseHref().$this->getcontrollerurl()."/perform/".$this->ID;

		return new FieldSet(
			new LiteralField("Link", "<a href='".$urlReport."' target='createreport_$this->title'>Create Test Report</a>"),
			new LiteralField("Link", "<a href='".$url."' target='performtest_$this->title'>Perform Test</a>"),
			new FormAction("save", "Save changes")
		);
	}
		
	/**
	 * Create a duplicate of this node. Doesn't affect joined data - create a
	 * custom overloading of this if you need such behaviour. 
	 * Copies the scenario children as well.
	 *
	 * @return SiteTree The duplicated object.
	 */
	 public function duplicateWithChildren() {
		$clone = parent::duplicateWithChildren();
		
		$children = $this->Steps();
		if($children) {
			foreach($children as $child) {
				$childClone = $child->duplicate();
				$childClone->ParentID = $clone->ID;
				$childClone->write();
			}
		}
		return $clone;
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
	 * Returns the children of this feature (which are other TestSections/features).
	 * This getter has no dedicated purpose except for readability in the templates.
	 * 
	 * @return DataObjectSet
	 */
	function getTestSections() {
		return $this->Children();
	}
	
	/**
	 * @return DataObjectSet
	 */
	function getAllTestSteps() {
		$steps = $this->Steps();
		$sections = $this->getTestSections();
		if($sections) foreach($sections as $section) {
			$steps->merge($section->getAllTestSteps());
		}
		
		return $steps;
	}

	/**
	 * @return TestPlan
	 */ 
	function getTestPlan() {
		$stopMarker = 10;
		$testPlan   = null;
		
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
	 * It is to be used in the TestSection.ss to avoid infinite loop 
	 * when include <% include TestSection %> in TestSection.ss
	 * 
	 * @return string
	 */
	function Render() {
		return $this->renderWith(array("TestSection"));
	}
	
	
	function RenderReport() {
		return $this->renderWith(array("TestSectionReport"));
	}	
}

/**
 * Test-Section controller
 *
 * Controller class for test-section (feature). It handles the perform-test
 * for a single feature.
 *
 * @todo use page_controller instead of Controller. Not working, don't load data() correctly.
 */
class TestSection_Controller extends Controller {

	static $allowed_actions = array(
		'perform',
		'report'
	);
	
	/**
	 *  Initialise the page, checks permissions and load the required JS files.
	 */
	function init() {		
		HTTP::set_cache_age(0);		
		parent::init();

		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}

		$testSection = $this->TestSection();		
		if ($testSection) {
			if (!$testSection->canView(Member::currentUser())) {
				Director::redirect('testplan/error');
				return;
			}
		}

		Requirements::javascript(THIRDPARTY_DIR."/behaviour/behaviour.js");
		Requirements::javascript(THIRDPARTY_DIR."/prototype/prototype.js");

		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-form/jquery.form.js");
		
		Requirements::javascript("regress/javascript/jquery.jeditable.js");
		Requirements::javascript("regress/javascript/jquery.changeawareform.js"); 
		Requirements::javascript("regress/javascript/TestPlan.js");
		Requirements::javascript("regress/javascript/StepResultAttachements.js");
		
	}

	/**
	 * Returns the requested Test-Section data object. The ID of the 
	 * data object needs to be passed into this method via the URL parameters.
	 *
	 * @return TestSection 
	 */
	public function TestSection() {
		if (isset($this->urlParams['ID'])) {
			return DataObject::get_by_id("TestSection", $this->urlParams['ID']);
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
		return $this->TestSection();
	}

	/**
	 * Returns the test session object of a given ID. The ID is passed in as a
	 * HTTP parameter.
	 * 
	 * @return TestSessionObj Instance of the session object.
	 */
	function TestSessionObj() {
		if($this->urlParams['OtherID']) {
			return true;
			return DataObject::get_by_id("TestSessionObj", $this->urlParams['OtherID']);
		}
	}	
	
	
}
?>