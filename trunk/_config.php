<?

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

Director::addRules(2, array(
	'testplan/$Action/$ID/$OtherID' => "TestPlan_Controller",
	'' => '->'
));
Director::addRules(100, array(
	'' => '->admin/'
));

Debug::send_errors_to("sam@silverstripe.com");

BasicAuth::disable();

Requirements::css("regress/css/TestPlan.css")

?>