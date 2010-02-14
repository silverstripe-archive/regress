<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 */ 
class StepResultNote extends DataObject {
	
	static $db = array(
		"Status" => "Enum('Resolved,Unresolved,','')",
		"Date"   => "Datetime",
		"Note"   => "Text",
	);
	
	static $has_one = array(
		"StepResult" => "StepResult"
	);	

	function NoteMarkdown() {
		return MarkdownText::render($this->Note);
	}	
}