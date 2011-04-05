<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 * A StepResult is the instance of a test-result for a scenario (test-step).
 *
 * Each step-result is related to one scenario (test-step) and stores the 
 * information about the test results for the particular scenario. A scenario
 * can pass it's test, fail or can be skipped by the test person.
 */ 
class StepResult extends DataObject {
	
	static $severity_map = array(
		'' => 'not available',
		'Severity1' => 'Critical',
		'Severity2' => 'High',
		'Severity3' => 'Medium',
		'Severity4' => 'Low',
	);
	
	static $db = array(
		"Outcome"         => "Enum(',pass,fail,skip','')",
		"Severity"        => "Enum(',Severity1,Severity2,Severity3,Severity4','')",
		"Note"            => "Text",
		"ResolutionDate"  => "Datetime"
	);
	
	static $has_one = array(
		"TestSession" => "TestSessionObj",
		"TestPlan"    => "TestPlan",
		"TestStep"    => "TestStep",
	);
	
	static $has_many = array(
		"StepResultNotes" => "StepResultNote",
		"Attachments"     => "File"
	);
	
	
	function canResolve() {
		return Permission::check("STEPRESULT_RESOLVE");		
	}

	function canUnresolve() {
		return Permission::check("STEPRESULT_UNRESOLVE");		
	}
	
	function ResolveActionLink() {
		return "StepResult_Controller/resolve/$this->ID";
	}
	
	function UnresolveActionLink() {
		return "StepResult_Controller/unresolve/$this->ID";
	}
	
	function CommentLink() {
		return "StepResult_Controller/comment/$this->ID";
	}
	
	function NoteMarkdown() {
		return MarkdownText::render($this->Note);
	}	
	
	function IsFail() {
		return ($this->Outcome == 'fail');
	}
	
	function IsTopSeverityRating() {
		return ($this->Severity == 'Severity1' || $this->Severity == 'Severity2');
	}
	
	function IsSeverity1() {
		return $this->Severity == 'Severity1';
	}

	function IsSeverity2() {
		return $this->Severity == 'Severity2';
	}

	function IsSeverity3() {
		return $this->Severity == 'Severity3';
	}

	function IsSeverity4() {
		return $this->Severity == 'Severity4';
	}	
	
	function IsSeverityUnselected() {
		return $this->Severity == '';
	}
		
	function getSeverityNice() {
		return StepResult::$severity_map[$this->Severity];
		
	}
}

/**
 * Controller Class for Step Result {@link StepResult}.
 *
 */
class StepResult_Controller extends Controller implements PermissionProvider {

	static $allowed_actions = array(
		'resolve',
		'unresolve',
		'comment',
		'results'
	);
	
	function providePermissions() {
	    return array(
	      "STEPRESULT_RESOLVE"   => "Can resolve marked-issues in session-report",
	      "STEPRESULT_UNRESOLVE" => "Can un-resolve marked-issues in session-report",
	    );
  	}
  
		
	/**
	 * Returns a requested Step-Result
	 *
	 * Returns the step-result for a given id. The ID needs to be passed 
	 * through as an URL-parameter.
	 *
	 * @return StepResult|null
	 */
	function StepResult() {
		$Plan = DataObject::get_by_id("StepResult", $this->urlParams['ID']);
	}
	
	function ListResults($status = null){
		$testID = (int)$this->urlParams['ID'];
		$level = $this->urlParams['OtherID'];
		if($level == 'plan') $class = 'TestPlan';
		else $class = 'TestSection';
		
		$TestPlan = DataObject::get_by_id($class,$testID);
		if(!$TestPlan->canView(Member::currentUser())) return false;
		if(!$status) $resp = DataObject::get("TestSessionObj","\"{$class}ID\" = $testID","\"Created\" Desc");
		else $resp = DataObject::get("TestSessionObj","\"{$class}ID\" = $testID AND \"Status\" = '$status'","\"Created\" Desc");
		
		return $resp;
	}
	
	function TestPlan(){
		return (DataObject::get_by_id("TestPlan",(int)$this->urlParams['ID'])) ? DataObject::get_by_id("TestPlan",(int)$this->urlParams['ID']) : DataObject::get_by_id("TestSection",(int)$this->urlParams['ID']);
	}
	
	function ErrorMessage(){
		
	}

	
	function ShowLeftOptions(){
		return false;
	}

	/**
	 * Change the severity status of the failed step-result.
	 */
	function setSeverityStatus($sr, $Severity) {

		$currentStatus = $sr->Outcome;
		$statusMessage = "%s: change status from '%s' to '%s'.";
	
		$currentSeverity = $sr->Severity;
		if (!$currentSeverity) {
			$currentSeverity = 'not available';
		}

		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Commented";
		$StepResultNote->Note = sprintf($statusMessage,Member::currentUser()->getName(),$currentSeverity, $Severity);
		$StepResultNote->Date = date('Y-m-d h:i:s');
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();

		$sr->Outcome = 'fail';
		$sr->Severity = $Severity;

		$sr->write();
	}

	function getSessionReportURL() {
		if($this->request->requestVar('_REDIRECT_BACK_URL')) {
			$url = $this->request->requestVar('_REDIRECT_BACK_URL');
		} else if($this->request->getHeader('Referer')) {
			$url = $this->request->getHeader('Referer');
		} else {
			$url = Director::baseURL();
		}

		// absolute redirection URLs not located on this site may cause phishing
		if(!Director::is_site_url($url)) {
			return '';
		}
		return $url;
	}

	/**
	 * Marks the step-result as resolved.
	 *
	 * Set the flag of the step-result to 'resolved'.
	 */
	function resolve($fields) {
		
		if (!Member::currentUser()) {
			Security::permissionFailure();
			Director::redirectBack();
			return;
		}
		
		// check if current user has the permission to 'resolve' the result.
		if(!Permission::check("STEPRESULT_RESOLVE")) {
			Security::permissionFailure();	
			Director::redirectBack();
			return;
		}
		
		$params = $fields->getVars();

		$ResolutionNote = 'No comments';
		if (isset($params['resolutionnote']) && $params['resolutionnote'] != '') {
			$ResolutionNote = $params['resolutionnote'];
		}
		
		$sr = $this->StepResult();
		
		$Severity = '';
		if (isset($params['severity'])) {
			$Severity = trim($params['severity']);
		}		

		// update severity if required
		if ($Severity && $Severity != $sr->Severity) {
			$this->setSeverityStatus($sr, $Severity);
		}
				
		$sr->ResolutionDate = date('Y-m-d h:i:s');
		$sr->write();

		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Resolved";
		$StepResultNote->Note = sprintf('%s : %s',Member::currentUser()->getName(),$ResolutionNote);
		$StepResultNote->Date = $sr->ResolutionDate;
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();
		
		// redirect to the anchor of the test-step
		$url = $this->getSessionReportURL();
		Director::redirect($url."#step_".$sr->ID);
	}
	
	/**
	 * Unmarks the step-result as resolved.
	 *
	 * Set the flag of the step-result to 'unresolved'.
	 */
	function unresolve($fields) {

		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}
		
		// check if current user has the permission to 'unresolve' the result.
		if(!Permission::check("STEPRESULT_UNRESOLVE")) {
			Security::permissionFailure();
			Director::redirectBack();
			return;
		}

		$params = $fields->getVars();

		$ResolutionNote = 'No comments';
		if (isset($params['resolutionnote']) && $params['resolutionnote'] != '') {
			$ResolutionNote = $params['resolutionnote'];
		}

		$sr = $this->StepResult();

		$Severity = '';
		if (isset($params['severity'])) {
			$Severity = trim($params['severity']);
		}		

		// update severity if required
		if ($Severity && $Severity != $sr->Severity) {
			$this->setSeverityStatus($sr, $Severity);
		}
		
		$sr->ResolutionDate = null;

		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Unresolved";
		$StepResultNote->Note = sprintf('%s : %s',Member::currentUser()->getName(),$ResolutionNote);
		$StepResultNote->Date  = date('Y-m-d h:i:s');
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();

		$sr->write();

		// redirect to the anchor of the test-step
		$url = $this->getSessionReportURL();
		Director::redirect($url."#step_".$sr->ID);
	}
	
	function comment($fields) {
		
		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}
		
		$params = $fields->getVars();
		
		$ResolutionNote = '';
		if (isset($params['resolutionnote'])) {
			$ResolutionNote = trim($params['resolutionnote']);
		}
		
		// if the comment/note is empty, redirect back
		if(!$ResolutionNote) {
			Director::redirectBack();
			return true;
		}
		
		$sr = $this->StepResult();

		$Severity = '';
		if (isset($params['severity'])) {
			$Severity = trim($params['severity']);
		}		

		// update severity if required
		if ($Severity && $Severity != $sr->Severity) {
			$this->setSeverityStatus($sr, $Severity);
		}		
		
		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Commented";
		$StepResultNote->Note = sprintf('%s : %s',Member::currentUser()->getName(),$ResolutionNote);
		$StepResultNote->Date  = date('Y-m-d h:i:s');
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();
		
		// redirect to the anchor of the test-step
		$url = $this->getSessionReportURL();
		Director::redirect($url."#step_".$sr->ID);
	} 
}

?>