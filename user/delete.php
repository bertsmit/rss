<?php
require dirname(__DIR__) . '/inc/inc.php';

$account = $core->getUserById($_POST["userId"]);


if (isset($_POST["delete"]) && $account) {
    if ($account->getAdmin() >= 1) {
        header('Location: edit.php?id='.$_POST["userId"]);
        $error = true;
        $errormsg = 'Kan deze gebruiker niet verwijderen.';
        die();
    } else { 
        $core->deleteUser($account);
        header('Location: index.php');
    }
    
} else { header('Location: index.php'); }