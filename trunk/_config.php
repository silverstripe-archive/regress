<?

if($_SERVER[HTTP_HOST] == "test") {
	header("Location: http://test.totallydigital.co.nz$_SERVER[REQUEST_URI]");
	die();
}
if($_SERVER[HTTP_HOST] == "dev") {
	header("Location: http://dev.totallydigital.co.nz$_SERVER[REQUEST_URI]");
	die();
}

global $project;
$project = "regress";

$databaseConfig = array(
	"type" => "MySQLDatabase",
	"server" => "localhost", 
	"username" => "silverstripe", 
	"password" => "silverNli24yeg", 
	"database" => "SS_regress",
);


Director::addRules(2, array(
	'testplan/$Action/$ID/$OtherID' => "TestPlan_Controller",
));

Debug::sendLiveErrorsTo("support@silverstripe.com");

Security::setDefaultAdmin("td", "2Bornot2B");

BasicAuth::disable();

?>