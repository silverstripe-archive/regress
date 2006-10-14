<?

class Page extends SiteTree {
	
	static $db = array(
	);
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