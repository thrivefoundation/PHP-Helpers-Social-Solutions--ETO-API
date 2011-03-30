<?php

function action_index() {
	global $eto, $tpl, $config, $arguments;
	
	$tpl['debug'] = '';
	// Redirect if already logged in.
	if (isset($_SESSION['SSOAuthToken'])) {
		header('Location: ' . $config['base_url'] . 'participant/search');
	}

	if (!isset($_POST['txt_user']) OR !isset($_POST['txt_pass'])) {
		$tpl['view'] = 'login';
		$tpl['h1'] = 'Login';
		// $tpl['error'] = 'No username or password submitted. Please try again.';
		render();
	} else {
		$user = $_POST['txt_user'];
		$pass = $_POST['txt_pass'];

		// Get the SSOAuthToken
		$request = array(
			'security' => array(
				'AccessKey' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX', // REPLACE THIS with your own key
				'AuthStatusCode' => 0,
				'Email' => $user,
				'Password' => $pass,
				'MarketplaceId' => 0,
				'SSOAuthToken' => '00000000-0000-0000-0000-000000000000',
				'TimeZoneOffset' => '0'
			)
		);

		$response = restPost($eto['SSOAuthenticate'] , $request);
		// dval($response);
		$tpl['debug'] .= dval($request, 'Request to '.$eto['SSOAuthenticate'], false);
		$tpl['debug'] .= dval($response, 'Response', false);
		$response = json_decode( $response );
		// dval($response->SSOAuthenticateResult->SSOAuthToken);
		if (isset($response->SSOAuthenticateResult->SSOAuthToken)) {
			$sso['SSOAuthToken'] = $response->SSOAuthenticateResult->SSOAuthToken;
		} else {
			$tpl['h1'] = 'Login';
			$tpl['view'] = 'login';
			// $tpl['error'] = dval($response->Detail, 'Response Details', false);
			$tpl['error'] = 'It looks like there was a problem with your username or password. Please doublecheck and try again.';
			render();
		}


		// Get the SSOEnterprise
		$request_url = $eto['GetSSOEnterprises'].$sso['SSOAuthToken'];
		$response = restGet( $request_url );
		// dval($response);
		$tpl['debug'] .= dval($request_url, 'Request to '.$request_url, false);
		$tpl['debug'] .= dval($response, 'Response', false);
		$response = json_decode($response);
		// dval($response);
		// dval($response[0]->Key);
		if (isset($response->Detail)) {
			$tpl['h1'] = 'Login';
			$tpl['view'] = 'login';
			$tpl['error'] = dval($response->Detail, 'Response Details', false);
			$tpl['error'] = 'It looks like there was a problem with your username or password. Please doublecheck and try again.';
			render();
		}
		$sso['enterpriseGuid'] = $response[0]->Key;

		// If there's an error, return back to the login page
		if (empty($sso['enterpriseGuid'])) {
			$tpl['h1'] = 'Login';
			$tpl['view'] = 'login';
			$tpl['error'] = $response;
			render();
		}


		// Now get the sites
		if (isset($_POST['site_id'])) {
			$sso['site_id'] = $_POST['site_id'];
			// dval($_POST);
		} else {
			$request_url = $eto['getSites'].$sso['SSOAuthToken'].'/'.$sso['enterpriseGuid'];
			$response = restGet( $request_url );
			$tpl['debug'] .= dval($request_url, 'Request to '.$request_url, false);
			$tpl['debug'] .= dval($response, 'Response', false);
			$response = json_decode($response);
			$tpl['debug'] .= dval($response, 'Response', false);
			if (count($response) > 1) {
				$tpl['sites'] = $response;
				$tpl['h1'] = 'Select a Site';
				$tpl['view'] = 'login';
				// $tpl['error'] = true;
				render();
			} else {
				$sso['site_id'] = $response[0]->Key;
			}
		}


		// Now we need the security token
		$request_url = $eto['GetSecurityToken'].$sso['site_id'].'/'.$sso['enterpriseGuid'].'/'.$sso['SSOAuthToken'].'/0';
		// dval($request_url);
		$response = restGet($request_url);
		$tpl['debug'] .= dval($request_url, 'SECURITY TOKEN Request to '.$request_url, false);
		$tpl['debug'] .= dval($response, 'Response', false);
		// dval($response);
		if (strpos($response, '<html ')) {
			$tpl['h1'] = 'Login';
			$tpl['view'] = 'login';
			$tpl['error'] = 'There was an error securing your security token. Please use the help tab to the right to let us know about this issue.';
			render();
		} else {
			$sso['securityToken'] = trim($response,'"');
		}
		// dval($sso['securityToken']);


		// Now get the Program ID
		if (isset($_POST['program_id'])) {
			$sso['program_id'] = $_POST['program_id'];
		} else {
			$_POST['site_id'] = $sso['site_id']; // so it can be accessed by the template and re-POSTED
			$response  = restGetCustom($eto['GetPrograms'].$sso['site_id'], $sso['enterpriseGuid'], $sso['securityToken']);
			$tpl['debug'] .= dval($response, 'PROGRAM ID Response from '.$eto['GetPrograms'].$sso['site_id'], false);
			$tpl['debug'] .= dval(count($response), 'PROGRAM ID Response LENGTH', false);
			$response = json_decode($response);
			// TODO allow for program selection
			if (isset($response->Detail)) {
				$tpl['h1'] = 'Login';
				$tpl['view'] = 'login';
				$tpl['error'] = $response->Detail;
				render();
			} elseif (empty($response)) {
				$tpl['h1'] = 'Login';
				$tpl['view'] = 'login';
				$tpl['error'] = '<p>No programs returned for your user.</p>';
				render();
			} else {
				$i = 0;
				foreach ($response as $program) {
					if ($program->Disabled == true) {
						unset($response[$i]);
					}
					$i++;
				}
				$tpl['debug'] .= dval($response, 'PROGRAM IDs after removing disabled', false);
				$response = array_values($response);
				$tpl['debug'] .= dval($response, 'PROGRAM IDs after array_values', false);
				$tpl['debug'] .= dval(count($response), 'PROGRAM ID Response LENGTH', false);
				if (count($response) > 1) {
					$tpl['programs'] = $response;
					$tpl['h1'] = 'Select a Program';
					$tpl['view'] = 'login';
					// $tpl['error'] = true;
					render();
				} else {
					$sso['program_id'] = $response[0]->ID; // Weird it's a 1 not a 0...
					$tpl['debug'] .= dval($response[0]->ID, 'PROGRAM ID, ONLY one', false);
					// $tpl['view'] = 'login';
					// render();
				}
			}
		}
		// Sanity check, just in case
		if (empty($sso['program_id'])) {
			$tpl['h1'] = 'Login';
			$tpl['view'] = 'login';
			$tpl['error'] = '<p>Your program_id appears to be empty.</p>';
		}


		// Now let's remember some of these values
		$_SESSION['user'] = $user;
		$_SESSION['SSOAuthToken'] = $sso['SSOAuthToken'];
		$_SESSION['enterpriseGuid'] = $sso['enterpriseGuid'];
		$_SESSION['securityToken'] = $sso['securityToken'];
		$_SESSION['program_id'] = $sso['program_id'];
		$_SESSION['site_id'] = $sso['site_id'];

		// Set the right Program ID
		$params = array(
			'ProgramID' => $sso['program_id']
		);
		$response = restPostCustom($eto['UpdateCurrentProgram'], $params);
		// $_SESSION['program_id_response'] = $response;

		// Now let's load up the next page
		header('Location: ' . $config['base_url'] . 'participant/search');
	}
}

function action_sites() {
	
}

function action_logout() {
	global $config;
	session_destroy();   // destroy session data in storage
	session_unset();     // unset $_SESSION variable for the runtime
	header('Location: ' . $config['base_url'] . 'login/');
}