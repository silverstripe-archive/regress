<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 * General page class for all regress site-tree classes. 
 */
class Page extends SiteTree {
	
	static $default_child = "TestPlan";
	
	static $db = array(
	);
	
	static $casting = array(
	);	
	
	
	function Content() {
		return MarkdownText::render($this->Content);
	}
	
	/**
	 * Overwrite SiteTree::getCMSFields method and remove most standard page
	 * type fields.
	 *
	 * @return FieldSet
	 */
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->removeFieldFromTab("Root", "Content");
		$fields->removeFieldFromTab("Root", "Reports");
		
		$fields->removeByName('To-do', false);
		$fields->removeByName('To-do **', false);
			
		$editTab = new Tab("Edit"); 
		$fields->insertBefore($editTab, "Behaviour"); 
		
		// Need to add URLSegment to prevent from parsing javascript error and hide this feel using css instead
		$editTab->push(new UniqueRestrictedTextField("URLSegment",
			"URLSegment",
			"SiteTree",
			_t('SiteTree.VALIDATIONURLSEGMENT1', "Another page is using that URL. URL must be unique for each page"),
			"[^A-Za-z0-9-]+",
			"-",
			_t('SiteTree.VALIDATIONURLSEGMENT2', "URLs can only be made up of letters, digits and hyphens."),
			"",
			"",
			"",
			50
		));
		
		$editTab->push(new LiteralField("PageType", sprintf("<h2>You have opened a %s </h2>",$this->singular_name())));
		$editTab->push(new TextField("Title", "Name"));
		$editTab->push(new TextareaField("Content", "Description (supports Markdown)"));		
		
		return $fields;
	}
	
	/**
	 * Extend CMS action handling: the only action allowed is to save the page.
	 *
	 * @return FieldSet
	 */
	function getAllCMSActions() {
		return new FieldSet(
			new FormAction("save", "Save changes")
		);
	}
	
	/**
	 * Overwrite canCreate method. Just instances of TestPlan, TestSection and 
	 * Page can be created.
	 * 
	 * @return boolean
	 */
	function canCreate() {
		return in_array($this->class, array('TestPlan', 'TestPlanHolder', 'TestSection', 'Page'));
	}
	
	/**
	 * Overwrite TreeTitle method.
	 *
	 * @return boolean
	 */
	function TreeTitle() {
		return $this->Title;
	}

	/**
	 * Create an empty form to generate the AJAX buttons via the CMS.
	 * This form has an empty fieldset and the action buttons, which are 
	 * shown on the page when the user performs a test.
	 */
	public function SessionForm() {
		
		$fields  = new FieldSet();
		$actions = new FieldSet();
		
		$actions->push(new FormAction("doSaveSession", "Save as draft"));
		$actions->push(new FormAction("mockSubmitTest", "Submit test"));
		
		$form = new Form($this, "SessionActions", $fields, $actions);
		$form->unsetValidator();
		
		return $form;
	}
	
	public function IsEditable() {
		return Member::currentUser() && $this->canEdit();
	}
}

/**
 * Controller class for the page-class.
 */ 
class Page_Controller extends ContentController {
	function Menu1() {
		return $this->getMenu(1);
	}
	
	function Menu2() {
		return $this->getMenu(2);
	}
}
