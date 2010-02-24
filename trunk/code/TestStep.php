<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 * Scenario
 */
class TestStep extends DataObject {
	static $db = array(
		"Step" => "MarkdownText",
		'Sort' => 'Int', 
		"UpdatedViaFrontend" => "Boolean" ,
		"UpdatedTimestamp"   => "Varchar(50)" ,
		"UpdatedByMember" 	 => "Varchar(100)"
	);
	
	static $has_one = array(
		"Parent"    => "TestSection",
		"UpdatedBy" => "Member"
	);
	
	/**
	 * Human-readable singular name
	 * @var string
	 */
	public static $singular_name = 'Scenario';

	/**
	 * Human-readable plural name
	 * @var string
	 */
	public static $plural_name = 'Scenarios';
	
	/**
	 * Returns the test session object of a given ID. The ID is passed in as a
	 * HTTP parameter.
	 * 
	 * @return TestSessionObj|Null Instance of the session object.
	 */
	function SessionStepResult() {
		$obj = null;

		$OtherID = Controller::curr()->urlParams['OtherID'];
		if($OtherID) {
			if(is_numeric($OtherID)) {
				$dataobjectset = DataObject::get("StepResult", "TestStepID = $this->ID AND TestSessionID = $OtherID");
				if ($dataobjectset != null) {
					$obj = $dataobjectset->First();
				}
			}
		}
		return $obj;
	}
	
	function IsOutcomePass() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'pass';
	}

	function IsOutcomeSkip() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'skip';
	}

	function IsOutcomeFail() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'fail';
	}
	
	
	/**
	 * Return the text stored in Step formatted as HTML, using Markdown for the
	 * formatting.
	 *
	 * @return string
	 */
	function StepMarkdown() {
		return MarkdownText::render($this->Step);
	}
	
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
		return ( $this->KnownIssues() || $this->PassNotes() || $this->SkipNotes() );
	}
	
}

class TestStep_Controller extends Controller {
	
	static $allowed_actions = array(
		'load',
		'save'
	);
	
	/**
	 * This method returns the raw-scenario description which will
	 * be used to populate the textarea field for front-end editing.
	 *
	 * NOTE: this method is used for front-end editing. When the user
	 * does not have edit-permissions, this text should not come up and returns
	 * a 401 error.
	 *
	 * @param HTTPRequest $request
	 * @return string
	 */
	function load($request) {
		
		if (Member::currentUser() == null) {
			$this->getResponse()->setStatusCode(401);
			return TestPlan::$permission_denied_text;
		}
		
		$vars = $request->getVars();
		$stepid_raw = $vars['id'];
		
		$tmp = explode ("_",$stepid_raw);
		if ($tmp[0] == 'scenarioContent') {
			$id       = (int)$tmp[1];
			$testStep = DataObject::get_by_id("TestStep", $id);
			
			if (!$testStep->Parent()->canEdit()) {
				$this->getResponse()->setStatusCode(401);
				return TestPlan::$permission_denied_text;
			}		
			return $testStep->getField('Step');
		}
	}

	/**
	 * This method saves the scenario description which has been entered
	 * bin the textarea field during the test-run.
	 *
	 * NOTE: this method is used for front-end editing. When the user
	 * does not have edit-permissions, this text should not come up and returns
	 * a 401 error.
	 *
	 * @param HTTPRequest $request
	 * @return string
	 */
	function save($request) {
		
		if (Member::currentUser() == null) {
			$this->getResponse()->setStatusCode(401);
			return TestPlan::$permission_denied_text;
		}		
		
		$vars       = $request->postVars();
		$stepid_raw = $vars['id'];
		$value      = $vars['value'];
		
		$tmp = explode ("_",$stepid_raw);
		
		if ($tmp[0] == 'scenarioContent') {
			$id = (int)$tmp[1];
			$testStep = DataObject::get_by_id("TestStep", $id);
			
			if (!$testStep->Parent()->canEdit(Member::currentUser())) {
				$this->getResponse()->setStatusCode(401);
				return TestPlan::$permission_denied_text;
			}		
			
			$testStep->setField('Step',$value );			
			$testStep->setField('UpdatedViaFrontend',true);
			$testStep->setField('UpdatedByMember',Member::currentUser()->getName());
			$testStep->setField('UpdatedTimestamp',SS_Datetime::now()->Nice());

			$testStep->write();
			return $testStep->StepMarkdown();
		}
		
		$this->getResponse()->setStatusCode(500);
		return "Invalid parameters.";
	}
	
}

?>