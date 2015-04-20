<?php
class RegressReportController extends Controller {
	
	function TestGroup($request) {
		$id = $request->param('ID');
		$report = Object::create('RegressReport'); 
		$records = $report->sourceRecords(array("TestGroup" => $id), '', '');
		
		$recordArray = array(); 
		$fieldsToKeep = array('Title', 'SubTitle', 'Total'); 
		
		foreach($records as $record) {
			$recordArray[] = array(
				'Title' => $record->Title,
				'ParentID' => $record->ParentID,
				'ClassName' => $record->ClassName,
				'Total' => $record->Total,
				'Passes' => $record->Passes,
				'Failures' => $record->Failures,
				'Skips' => $record->Skips,
				'Date' => $record->Date
			); 
		}
		
		header('Content-type: application/json');
		echo json_encode($recordArray);
	}
}