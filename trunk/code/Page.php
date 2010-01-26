<?php
/**
 * @package regress
 * @subpackage code
 */
require_once('../markdown/thirdparty/Markdown/markdown.php');

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
		return Markdown($this->Content);
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
		$fields->removeFieldFromTab("Root", "Behaviour");
		$fields->removeFieldFromTab("Root", "Reports");
		$fields->removeByName('To-do', false);
		$fields->removeByName('To-do **', false);
		$fields->removeByName('Access', false);
		
		$fields->addFieldToTab("Root.Edit", new TextField("Title", "Name"));
		$fields->addFieldToTab("Root.Edit", new TextareaField("Content", "Description"));

		// change order of the tabs so that edit is at the beginning again.
		$resultsTab = $fields->fieldByName('Root.Results');
		
		if ($resultsTab != null) {
			$editTab    = $fields->fieldByName('Root.Edit');
			$fields->removeFieldFromTab("Root", "Edit");
			$fields->addFieldToTab('Root',$editTab,"Results");
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
		return $this->class == "TestPlan" || $this->class == "TestSection" || $this->class == "Page";
	}
	
	/**
	 * Overwrite TreeTitle method.
	 *
	 * @return boolean
	 */
	function TreeTitle() {
		return $this->Title;
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

?>
