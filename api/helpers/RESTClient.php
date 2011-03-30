<?php
/**
 * Functions for performing HTTP REST requests
 *
 * Tested in PHP 5.0.3
 *
 * Description :
 * File includes functions to call REST webservices using PHP cURL
 * Following REST Calls are included:
 *
 * o GET
 * o POST
 * o PUT
 * o DELETE
 *
 * @Company   The Man Can! <http://www.themancan.com>
 * @author    Sami Fiaz <sami@themancan.com>
 */

/**
 *@function: restGet
 *@Param: $url (URI for the REST webservice)
 *@Return: $response (JSON Object)
 */

function restGet( $url ){
	$handle = curl_init();
	$headers = array(
	'Accept: application/json',
	'Content-Type: application/json'
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	return $response;
}

function restGetCustom( $url, $guid = '', $sec = '' ) {
	$guid = (!empty($guid)) ? $guid : $_SESSION['enterpriseGuid'];
	$sec = (!empty($sec)) ? $sec : $_SESSION['securityToken'];
	$handle = curl_init();
	$headers = array(
		'Accept: application/json',
		'Content-Type: application/json',
		'enterpriseGuid: '.$guid,
		'securityToken: '.$sec
	);
	// dval($headers, 'CURL Headers');

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	// dval($response, 'CURL Response');
	// dval($handle, 'CURL Handle');
	// dval($code, 'CURL Response Code');
	return $response;
}


/**
 *@function: restPost
 *@Param: $url (URI for the REST webservice), $param (Array of values to be sent as parameters)
 *@return: $response ( JSON Object )
 */
function restPost( $url , $param ){
	$handle = curl_init();
	$data= json_encode($param);

	$headers = array(
	'Accept: application/json',
	'Content-Type: application/json'
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	return $response;

}

function restPostCustom( $url , $param , $guid = '', $sec = '') {
	$guid = (!empty($guid)) ? $guid : $_SESSION['enterpriseGuid'];
	$sec = (!empty($sec)) ? $sec : $_SESSION['securityToken'];
	$handle = curl_init();
	$data= json_encode($param);

	$headers = array(
		'Accept: application/json',
		'Content-Type: application/json',
		'enterpriseGuid: '.$guid,
		'securityToken: '.$sec
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	return $response;

}

/**
 *@function: restPut
 *@Param: $url (URI for the REST webservice), $param (Array of values to be sent as parameters)
 *@return: $response ( JSON Object )
 */

function restPut( $uri , $param ){
	$handle = curl_init();
	$data= json_encode($param);

	$headers = array(
	'Accept: application/json',
	'Content-Type: application/json'
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($handle, CURLOPT_POSTFIELDS, $param);

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	return $response;

}

/**
 *@function: restDelete
 *@Param: $url (URI for the REST webservice), $param (Array of values to be sent as parameters)
 *@return: $response ( JSON Object )
 */

function restDelete( $uri , $param ){
	$handle = curl_init();
	$data= json_encode($param);

	$headers = array(
	'Accept: application/json',
	'Content-Type: application/json'
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');

	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	$code2 = curl_getinfo($handle,CURLINFO_SSL_VERIFYRESULT);
	curl_close($handle);
	return $response;

}

function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}

/* ======= End Of file ======= */