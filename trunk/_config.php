<?php

if($_SERVER['HTTP_HOST'] == "test") {
	header("Location: http://test.totallydigital.co.nz" . $_SERVER['REQUEST_URI']);
	die();
}
if($_SERVER['HTTP_HOST'] == "dev") {
	header("Location: http://dev.totallydigital.co.nz" . $_SERVER['REQUEST_URI']);
	die();
}

global $project;
$project = "regress";

global $database;
$database = "SS_regress";

require_once('conf/ConfigureFromEnv.php');

Director::set_environment_type('dev');

Director::addRules(2, array(
	'testplan/$Action/$ID/$OtherID' => "TestPlan_Controller",
	'feature/$Action/$ID/$OtherID' => "TestSection_Controller",
	'session/$Action/$ID/$OtherID' => "Session_Controller",
	'scenario/$Action/$ID/$OtherID' => "TestStep_Controller",
	'' => '->'
));

Director::addRules(100, array(
	'' => '->admin/'
));

// R.Spittel - BasicAuth::disable is depreciated.
BasicAuth::protect_entire_site(false);
// BasicAuth::disable();

Requirements::css("regress/css/TestPlan.css");

Object::add_extension('TestPlan', 'TestPageDecorator');
Object::add_extension('TestSection', 'TestPageDecorator');

Object::add_extension('LeftAndMain', 'PageLeftAndMainDecorator');

// run following sql update after updating:
// update SiteTree set CanViewType='Inherit', CanEditType='Inherit'

// Set root sitetree note can-optiosn to administrators only

// ensure that the groups tables SiteTree_EditorGroups and SiteTree_ViewerGroups are empty.
?>
