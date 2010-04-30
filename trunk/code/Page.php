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
		// $fields->removeFieldFromTab("Root", "Behaviour");
		$fields->removeFieldFromTab("Root", "Reports");
		$fields->removeByName('To-do', false);
		$fields->removeByName('To-do **', false);

		$fields->addFieldToTab("Root.Edit", new LiteralField("PageType", sprintf("<h2>You have opened a %s </h2>",$this->singular_name())));
		$fields->addFieldToTab("Root.Edit", new TextField("Title", "Name"));
		$fields->addFieldToTab("Root.Edit", new TextareaField("Content", "Description (supports Markdown)"));

		// change order of the tabs so that edit is at the beginning again.
		$resultsTab = $fields->fieldByName('Root.Results');
		
		if ($resultsTab != null) {
			$editTab    = $fields->fieldByName('Root.Edit');
			$fields->removeFieldFromTab("Root", "Edit");
			$fields->addFieldToTab('Root',$editTab,"Results");
		}

		// change order of the tabs so that edit is at the beginning again.
		$resultsTab = $fields->fieldByName('Root.Access');
		
		if ($resultsTab != null) {
			$editTab    = $fields->fieldByName('Root.Edit');
			$fields->removeFieldFromTab("Root", "Edit");
			$fields->addFieldToTab('Root',$editTab,"Access");
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
