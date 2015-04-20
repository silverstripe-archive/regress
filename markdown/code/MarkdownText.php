<?php

require_once('../markdown/thirdparty/Markdown_Extra/markdown.php');

/**
 *
 */
class MarkdownText extends Text {
	
	public function Value() {
		return self::render($this->value);
	}
	
	/**
	 * Returns an [X|HT]ML safe rendering of this field for insertion in
	 * templates.
	 */
	public function XML_val() {
		return self::render($this->value);
	}
	
	/**
	 * This method actually uses the markdown 3rd party library to render
	 * the text in HTML.
	 *
	 * @param string $value String to render (markdown text)
	 *
	 * @return string HTML text (applies markdown and generate HTML)
	 */
	public static function render($value) {
		return Markdown($value);
	}
}