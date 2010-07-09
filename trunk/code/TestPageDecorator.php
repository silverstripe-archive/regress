<?php
/**
 *
 */
class TestPageDecorator extends DataObjectDecorator {
	
	/**
	 * Append additional NIWA related order fields.
	 */
	function extraStatics() {
		return array(
			'db' => array(
			),
			'has_one' => array (
			),
			'defaults' => array(
			),
			'field_labels' => array(
			),
		);
	}

	/**
	 * Add new fields at an appropriate place in the CMS form.
	 */
	public function updateCMSFields(FieldSet &$fields) {
		$owner = $this->getOwner();

		if(is_numeric($owner->ID)) {
			$fields->addFieldToTab("Root.Results", $this->getReportTableListField() );
			$fields->addFieldToTab("Root.Drafts", $this->getDraftsTableListField() );
		} else {
			$fields->addFieldToTab("Root.Results", new Headerfield("Please save this before continuing",1));
			$fields->addFieldToTab("Root.Drafts", new Headerfield("Please save this before continuing",1));
		}

	}
		
	/**
	 * Initiate a TableListField to list all draft test which have been saved on 
	 * the particular test plan.
	 *
	 * @return TableListField
	 */
	function getDraftsTableListField() {
		
		Requirements::css('regress/css/RegressTableListField.css');
		Requirements::javascript('regress/javascript/autoresize.jquery.js');
		Requirements::javascript('regress/javascript/RegressTableListField.js');
		
		$owner = $this->getOwner();
		
		// all draft/new sessions
		$draftTableFields = array(
		   "ID" => "Session #", 
		   "Created" => "Date", 
		   "NumPasses" => "# Passes", 
		   "NumFailures" => "# Failures",
		   "NumSkips" => "# Skips",
		   'Author.Title' => 'Author', 
		);

		$draftReport = new TableListField(
		   "DraftSessions", 
		   "TestSessionObj",
			$draftTableFields, 
			$owner->ClassName."ID = '$owner->ID' and (Status = 'new' or Status = 'draft')", 
			"Created DESC"
		);
		$draftReport->setPermissions(array('edit','show','delete'));

		$url = '<a target=\"_blank\" href=\"' . Director::baseURL() . $owner->getcontrollerurl(). '/perform/' .$owner->ID.'/$ID\">$value</a>';
		$draftReport->setFieldFormatting(array_combine(array_keys($draftTableFields), array_fill(0,count($draftTableFields), $url)));
		
		return $draftReport;		
	}
	
	/**
	 * Initiate a TableListField to list all test which have been performed on 
	 * the particular test.
	 *
	 * @return TableListField
	 */
	function getReportTableListField() {
		$owner = $this->getOwner();
		
		$sessionTableFields = array(
		   "ID" => "Session #", 
		   "Created" => "Date", 
		   "NumPasses" => "# Passes", 
		   "NumFailures" => "# Failures",
		   "NumSkips" => "# Skips",
		   'Author.Title' => 'Author', 
		);

		$sessionReport = new TableListField(
		   "Sessions", 
		   "TestSessionObj",
			$sessionTableFields, 
			$owner->ClassName."ID = '$owner->ID' and (Status = 'submitted')", 
			"Created DESC"
		);
		$sessionReport->setPermissions(array('edit','show','delete'));
		 
		$url = '<a target=\"_blank\" href=\"' . Director::baseURL() . 'session/reportdetail/'.$owner->ID.'/$ID\">$value</a>';
		$sessionReport->setFieldFormatting(array_combine(array_keys($sessionTableFields), array_fill(0,count($sessionTableFields), $url)));

		return $sessionReport;
	}

}