<?php

function action_list() {
	global $eto, $tpl, $config, $arguments;
	$tpl['h1'] = 'Select an Assessment';
	$tpl['view'] = 'assessment.list';
	$tpl['data'] = array();
	$tpl['debug'] = '';

	if (!isset($_SESSION['SSOAuthToken'])) {
		header('Location: ' . $config['base_url'] . 'login/');
	}

	if (!empty($_GET['search_participant'])) {
		$tpl['participant'] = (int) $_GET['search_participant'];
	} elseif ($arguments[0] != '') {
		$tpl['participant'] = (int) $arguments[0];
	}
	$tpl['participant_name'] = $_SESSION['participant'][$tpl['participant']];
	if (!empty($tpl['participant'])) {
		$request_url = $eto['GetAssessment'];
		$params = array(
			'CLID' => $tpl['participant'],
			'surveyResponderType' => '0'
		);
		$response  = restPostCustom($request_url, $params);
		$tpl['debug'] .= dval($response, 'JSON Response', false);
		$response  = json_decode($response);
		$tpl['debug'] .= dval($response->GetAllAssessmentsThriveResult, 'Get response->GetAllAssessmentsThriveResult', false);
		$counter = 0;
		if (!isset($response->GetAllAssessmentsThriveResult)) {
			header('Location: ' . $config['base_url'] . 'login/');
		}
		$allowed_assessments = array(
			// Add assessment names, or the beginning of the assessment names, that you'd like to allow
		);
		foreach ($response->GetAllAssessmentsThriveResult as $assessment) {
			if (stripos_arr($assessment->Value, $allowed_assessments) === false) continue;
			$tpl['data'][$counter]['id'] = $assessment->Key;
			$tpl['data'][$counter]['name'] = str_replace('New_', '', $assessment->Value);
			$tpl['data'][$counter]['name'] = str_replace('_', ' ', $tpl['data'][$counter]['name']);
			$tpl['data'][$counter]['name'] = str_replace('07th', '7th', $tpl['data'][$counter]['name']);
			$tpl['data'][$counter]['name'] = str_replace('08th', '8th', $tpl['data'][$counter]['name']);
			$tpl['data'][$counter]['name'] = str_replace('09th', '9th', $tpl['data'][$counter]['name']);
			$counter++;
		}

		foreach ($tpl['data'] as $key => $row) {
		    $names[$key]  = $row['name']; 
		    // of course, replace 0 with whatever is the date field's index
		}
		// $arr = $tpl['data'];
		// print_r($arr);
		// array_multisort($names, SORT_DESC, $arr);
		// print_r($arr);
		// $tpl['data'] = $arr;

	}

	render();
}

function action_info() {
	global $eto, $tpl, $config, $arguments;

	if (!empty($_GET['assessment'])) {
		$assessment = (int) $_GET['assessment'];
	} elseif (!empty($arguments[0])) {
		$assessment = (int) $arguments[0];
	}
	if (!empty($_GET['participant'])) {
		$participant = (int) $_GET['participant'];
	} elseif (!empty($arguments[1])) {
		$participant = (int) $arguments[1];
	}

	$tpl['participant'] = $participant;
	$tpl['participant_name'] = $_SESSION['participant'][$participant];
	$tpl['h1'] = 'Record an Assessment for '.$tpl['participant_name'];
	$tpl['view'] = 'assessment.info';
	$tpl['data'] = array();
	$tpl['assessment'] = $assessment;

	if(empty($assessment)) {
		render();
	}
	
	$request_url = $eto['GetAssessmentInfo'].$assessment;
	// dval($eto['SearchParticipant']);
	// dval($prog_id);
	// dval($request_url);
	$response  = restGetCustom( $request_url );
	// dval($response);
	$response  = json_decode($response);
	// dval($response);

	$tpl['assessment_id'] = $response->SurveyID;
	$tpl['assessment_name'] = str_replace('07th', '7th', $tpl['assessment_name']);
	$tpl['assessment_name'] = str_replace('08th', '8th', $tpl['assessment_name']);
	$tpl['assessment_name'] = str_replace('09th', '9th', $tpl['assessment_name']);
	$tpl['assessment_name'] = str_replace('New_', '', $response->SurveyName);
	$tpl['assessment_name'] = str_replace('_', ' ', $tpl['assessment_name']);
	$counter = 0;
	if (!isset($response->SurveyElements)) {
		header('Location: ' . $config['base_url'] . 'login/');
		render();
	}
	foreach ($response->SurveyElements as $e) {
		$tpl['data'][$counter] = $e;
		$counter++;
	}

	render();
}

function action_submit() {
	global $eto, $tpl, $config, $arguments;

	// dval($_POST);
	if (!empty($_POST['assessment'])) {
		$assessment = (int) $_POST['assessment'];
	} else {
		render();
	}
	if (!empty($_POST['participant'])) {
		$participant = (int) $_POST['participant'];
	} else {
		render();
	}

	$tpl['participant_name'] = $_SESSION['participant'][$participant];
	$tpl['h1'] = 'Assessment Submitted for '.$tpl['participant_name'];
	$tpl['view'] = 'assessment.submit';
	$tpl['data'] = array();
	$tpl['assessment'] = $assessment;

	$params = array();
	$radio_choice = array();
	$counter = 0;

	foreach ($_POST as $name => $value) {
		if (empty($value) || $name == 'participant' || $name == 'assessment') continue;

		// echo $name.' :: '.$value;
		$name = explode('_', $name);
		$type_id = $name[0];
		$name = $name[1];
		$radio_choice[0] = (int) $value;

		if ($type_id == 'q2') {
			$radio_choice[0]=(int)$value;
			$element['SurveyElementID'] = (int) $name;
			$element['SurveyElementResponseType'] = true; // true if radio/multiple choice
			$element['SurveyElementResponseValue'] = $radio_choice; // accepts value in array 
			$element['CharacteristicType'] = 3;
			$element['CommentText'] = null;
		} else {
			$element['SurveyElementID'] = (int) $name;
			$element['SurveyElementResponseType'] = false;
			$element['SurveyElementResponseValue'] = $value;
			$element['CharacteristicType'] = 3;
			$element['CommentText'] = null;
		}
		$params[$counter] = $element;
		$counter++;
	}


	$timestamp = time()*1000;
	$request = array(
		"surveyResponse" => array(
			"SurveyResponseID" => 0, // Why are we setting this to 0 in this manner? IT WAS ON DEBUGING, no need , so replaced
			"SurveyID" => (int) $assessment,
			"SurveyDate" => '/Date('.$timestamp.')/',
			"SurveyResponderType" => 0,// 0 for participant
			"CL_EN_GEN_ID" => (int) $participant,
			"ResponseCreationDate" => '/Date('.$timestamp.')/',
			"SurveyElementResponses" => $params
		)
	);
	$tpl['data']['request'] = $request;

	$response = restPostCustom($eto['AddAssessment'], $request);
	if ($response == "NULL" || empty($response)) {
		$tpl['error'] = true;
		render();
	} else {
		$response = json_decode($response); 
		$tpl['data']['response'] = $response;
		render();
	}
}



// strpos that takes an array of values to match against a string
// note the stupid argument order (to match strpos)
function stripos_arr($haystack, $needle) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
        if(($pos = stripos($haystack, $what))!==false) return $pos;
    }
    return false;
}

// from http://firsttube.com/read/sorting-a-multi-dimensional-array-with-php/
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}
function compare($a, $b)
{
	print_r($b);
	if ($a['name'] > $b['name']) {
		echo '1';
		return 1;
	} else {
		echo '-1';
		return -1;
	}
    return ($a['name'] > $b['name']);
}
// usort($tpl['data'], "compare");
