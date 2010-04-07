<?php
class StepResultFileDecorator extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'has_one' => array(
				'StepResult' => 'StepResult'
			)
		); 
	}
}