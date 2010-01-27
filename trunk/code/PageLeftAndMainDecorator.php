<?php
/**
 * Plug-ins for additional functionality in your LeftAndMain classes.
 * 
 * @package regress
 * @subpackage core
 */
class PageLeftAndMainDecorator extends LeftAndMainDecorator {

	/**
	 * Change default title of all page types to their singular names instead
	 * of using the classname.
	 */
	function augmentNewSiteTreeItem(&$item) {
		$item->Title = "New ".$item->singular_name();
	}

}

?>