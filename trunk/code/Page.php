<?

class Page extends SiteTree {
	
	static $db = array(
	);
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root.Content.Main", "MenuTitle");
		$fields->removeFieldFromTab("Root.Content.Main", "Content");
		
		$fields->addFieldToTab("Root.Content.Main", new TextareaField("Content", "Description"));
		return $fields;
	}
	
	
	function canCreate() {
		return $this->class == "TestPlan" || $this->class == "TestSection";
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