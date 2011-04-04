<?php

class SiteConfigDecorator extends DataObjectDecorator {
	
	/**
	 * Append additional NIWA related order fields.
	 */
	function extraStatics() {
		return array(
			'db' => array(
				'DashboardIndroduction' => 'Varchar(255)'
			),
			'has_one' => array(
			),
			'defaults' => array(
			),
			'field_labels' => array(
			),
		);
	}
	
	public function updateCMSFields(FieldSet &$fields) {

		$fields->addFieldToTab("Root.Main", new TextField('DashboardIndroduction','Dashboard Introduction'));


	}
}