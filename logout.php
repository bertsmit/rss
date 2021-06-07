<?php
	session_start();

	if (!isset($_SESSION['userId'])) {
		header('Location: index.php');
		die();
	}

	require_once 'classes/user.class.php';
	require_once 'classes/core.class.php';
	require_once 'classes/authentication.class.php';

	$userid = $_SESSION['userId'];

	$authentication = new Authentication;

	$authentication->logout($userid);

	header('Location: index.php');

	die();
?>