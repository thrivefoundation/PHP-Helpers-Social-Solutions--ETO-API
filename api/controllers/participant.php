<?php

function action_search() {
	// dval($args);
	global $eto, $tpl, $config, $arguments;
	$tpl['h1'] = 'Search for a Participant';
	$tpl['view'] = 'participant.search';
	$tpl['data'] = array();
	$tpl['debug'] = '';

	if (!empty($_GET['search_participant'])) {
		$q = $_GET['search_participant'];
	} elseif (!empty($arguments[0])) {
		$q = $arguments[0];
	}

	// if (!empty($q) && is_numeric($q)) {
	// 	echo 'foo!';
	// 	header('Location: '.$config['base_url'].'assessment/list/'.$q);
	// } elseif (!empty($q)) {
	if (!empty($q) && is_numeric($q)) {
		$params = array(
			"searchparams" => array(
				'EnterpriseGuid' => $_SESSION['enterpriseGuid'],
				'SiteID' => $_SESSION['site_id'],
				'ProgramID' => $_SESSION['program_id'],
				'Scope' => 1,
				'IncludeDismissedFamilyMembers' => false,
				'LoadHouseHoldMembers' => false,
				// 'SearchText' => '130100',
				// 'SearchText' => '140100',
				'SearchText' => (int) $q,
				'Types' => 4 // 4 is to search for the case ID
			)
		);
		$response = restPostCustom($eto['AdvancedSearch'], $params);
		$tpl['debug'] .= dval($response, 'RESPONSE from '.$eto['AdvancedSearch'], false);
		// dval($params, 'PARAMS for '.$eto['AdvancedSearch']);
		// dval($response, 'RESPONSE from '.$eto['AdvancedSearch']);
		$response  = json_decode($response);

		$counter = 0;
		foreach ( $response->AdvancedSearchResult as $participants => $participant ) {
			$_SESSION['participant'][$participant->CLID] = $participant->FName . ' ' . $participant->LName;
			$tpl['data'][$counter]['id'] = $participant->CLID;
			$tpl['data'][$counter]['case_id'] = $q;
			$tpl['data'][$counter]['name_first'] = $participant->FName;
			$tpl['data'][$counter]['name_last'] = $participant->LName;
			$counter++;
		}
	} elseif (!empty($q)) {
		$request_url = $eto['SearchParticipant'].$_SESSION['program_id'].'/'.$q;
		$response  = restGetCustom( $request_url );
		$tpl['debug'] .= dval($response, 'RESPONSE from '.$request_url, false);
		$response  = json_decode($response);

		$counter = 0;
		foreach ( $response as $participants => $participant ) {
			$_SESSION['participant'][$participant->CLID] = $participant->FName . ' ' . $participant->LName;
			$tpl['data'][$counter]['id'] = $participant->CLID;
			$tpl['data'][$counter]['case_id'] = $participant->CLID;
			$tpl['data'][$counter]['name_first'] = $participant->FName;
			$tpl['data'][$counter]['name_last'] = $participant->LName;
			$counter++;
		}
	}

	render();
}

// $tpl['h1'] = 'fallback';
// $tpl['view'] = 'participant.search';
// 
// render();
