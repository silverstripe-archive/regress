<?

class Page extends SiteTree {
	
	static $db = array(
	);
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root", "Content");
		$fields->removeFieldFromTab("Root", "Behaviour");
		$fields->removeFieldFromTab("Root", "Reports");
		$fields->removeByName('To-do');
		$fields->removeByName('To-do **');
		$fields->removeByName('Access');
		
		$fields->addFieldToTab("Root.Edit", new TextField("Title", "Name"));
		$fields->addFieldToTab("Root.Edit", new TextareaField("Content", "Description"));
		return $fields;
	}
	
	function getAllCMSActions() {
		return new FieldSet(
			new FormAction("save", "Save changes")
		);
	}
	
	function canCreate() {
		return $this->class == "TestPlan" || $this->class == "TestSection";
	}
	
	function TreeTitle() {
		return $this->Title;
	}
}

class Page_Controller extends ContentController {
	function Menu1() {
		return $this->getMenu(1);
	}
	
	function Menu2() {
		return $this->getMenu(2);
	}
}

?>