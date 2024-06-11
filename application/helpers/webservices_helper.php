<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function api_post_data($url,$fields){
	$field_string = http_build_query($fields);

	if(!isset($field_string)) $field_string = array();

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

	$content = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	$ret = json_decode($content);
	$data = array('data'=>$ret,'message'=>$httpcode);

	return $data;
}

function api_post_data_add($url,$fields,$keyword){
	$field_string = http_build_query($fields);

	if(!isset($field_string)) $field_string = array();

	$ch = curl_init();

	$headr = array();
	$headr[] = 'Authorization: Bearer '.$keyword;
	$headr[] = 'Content-Type: application/x-www-form-urlencoded ';

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);

	$content = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	$ret = json_decode($content);
	$data = array('data'=>$ret,'message'=>$httpcode);

	return $data;
}

function api_jqgrid_data($url,$fields){
	$field_string = http_build_query($fields);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($field_string));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

	$content = curl_exec($ch);

	curl_close($ch);
	
	echo $content;
}

function getJSONData($url,$keyword){
	$ch = curl_init();
	$headr = array();
	$headr[] = 'Authorization: Bearer '.$keyword;

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }

  if (error_reporting() & $severity) {
    throw new ErrorException($message.'. '.$filename.' Line : '.$lineno, 0, $severity, $filename, $lineno);
  }
}