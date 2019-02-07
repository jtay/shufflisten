<?php

	session_start();
	header('Content-type: application/json');

	include_once($_SERVER['DOCUMENT_ROOT'] . '/../class/shufflisten.api.class.php');

	if(isset($require_login)){
		$api = new Shufflisten_API($require_login);	
	}else{
		$api = new Shufflisten_API();
	}
	