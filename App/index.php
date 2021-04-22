<?php
	header('Content-Type: APPlication/json; charset=UTF-8');

	require_once 'vendor/autoload.php';
	

	use App\Rest;

	if (isset($_REQUEST) && !empty($_REQUEST)) {
		$rest = new Rest($_REQUEST);
		echo $rest->run();
	}