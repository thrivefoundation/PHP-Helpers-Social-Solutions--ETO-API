<?php
/*
	TODO add session locking to IP address
*/


// Initialize some variables
$tpl = array();
$tpl['nav_sub'] = '/api/views/tpl.nav.partner';
$tpl['content'] = '';
$tpl['error'] = false;

$nav_section = 'partners';
$nav_active = 'active ';

$eto['SSOAuthenticate'] = 'https://services.etosoftware.com/API/Security.svc/SSOAuthenticate/';
$eto['GetSSOEnterprises'] = 'https://services.etosoftware.com/API/Security.svc/GetSSOEnterprises/';
$eto['getSites'] = 'https://services.etosoftware.com/API/Security.svc/GetSSOSites/';
$eto['GetPrograms'] = 'https://services.etosoftware.com/API/Form.svc/Forms/Program/GetPrograms/';
$eto['UpdateCurrentProgram'] = 'https://services.etosoftware.com/API/Security.svc/UpdateCurrentProgram/';
$eto['GetSecurityToken'] = 'https://services.etosoftware.com/API/Security.svc/SSOSiteLogin/';
$eto['SearchParticipant'] = 'https://services.etosoftware.com/API/Search.svc/Search/';
$eto['AdvancedSearch'] = 'https://services.etosoftware.com/API/Search.svc/Search/AdvancedSearch/';
$eto['GetActors'] = 'https://services.etosoftware.com/API/Search.svc/Search/GetActors/';
$eto['GetAssessment'] = 'https://services.etosoftware.com/API/Form.svc/Forms/Assessments/GetAllAssessmentsThrive/';
$eto['GetAssessmentRessponse'] = 'https://services.etosoftware.com/API/Form.svc/Forms/Assessments/GetAllAssessementResponses/';
$eto['GetAssessmentInfo'] = 'https://services.etosoftware.com/API/Form.svc/Forms/Assessments/';
$eto['AddAssessment'] = 'https://services.etosoftware.com/API/Form.svc/Forms/Assessments/AddAssessmentResponse/';


$controller = $_GET['controller'];
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$arguments = explode('/', $_GET['arguments']);
$config['base_url'] = 'http://www.EXAMPLE.org/api/';
$config['self_url'] = $config['base_url'].$controller.'/'.$action;


// Get to work
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
	session_destroy();   // destroy session data in storage
	session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

require_once('./helpers/RESTClient.php'); // This seems to want to be outside of the next if loop... not sure why.
if (empty($controller)) {
	$controller = 'login';
}
if (!isset($_SESSION['SSOAuthToken']) && !strpos($_SERVER['SCRIPT_FILENAME'], '/api/login') && $controller != 'login') {
	header('Location: ' . $config['base_url'] . 'login/');
	$controller = 'login';
}
require_once('./helpers/debug.php');
require_once('./controllers/'.$controller.'.php');
if (isset($action) && function_exists('action_'.$action)) {
	call_user_func('action_'.$action);
} elseif (function_exists('action_index')) {
	call_user_func('action_index');
}




// Render the template
function render() {
	global $tpl, $config, $controller, $action, $arguments, $nav_section, $nav_active;
	$tpl['tpl'] = (isset($tpl['tpl'])) ? $tpl['tpl'].'.' : '';
	ob_start();
	require('views/tpl.'.$tpl['tpl'].'php');
	ob_end_flush();
	exit;
}
