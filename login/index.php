<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION["userId"])) {
    header('Location: ../');
}

require_once '../classes/authentication.class.php';
require_once '../classes/core.class.php';

$auth = new Authentication();
$core = new Core();

$error = false;

if (isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['password'])) {
    // if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $user = $core->getUserByName(trim($_POST['name']));
        if ($user != null) {
            if ($auth->validatePassword(trim($_POST["name"]), trim($_POST["password"]))) {
                $_SESSION['userId'] = $user->getId();
                echo $user->getId();
                header("Location: ../");
                die();
            }
            else {
            	$error = true; $errormsg = 'Onjuist wachtwoord.';
            }
        }
        else {
        	$error = true; $errormsg = 'Gebruiker niet gevonden.';
        }
    // }
    // else {
    // 	echo "Email bestaat niet.";
    // }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
    <!-- Minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <!-- Minified JS library -->
    <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <!-- Minified Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>
        .center-div {
            position: absolute;
            margin: auto;
            top: 0;
            right: 0;
            bottom: 25%;
            left: 0;
            max-height: 320px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="layout-form" class="form-signin text-center center-div" method="post">
            <h3>Inloggen</h3>
            <hr>
            <input id="name" class="form-control input-block-level" name="name" type="text" placeholder="Gebruikersnaam" autofocus>
            <input id="password" class="form-control input-block-level" name="password" type="password" placeholder="Wachtwoord" autofocus>
            <?php if ($error) { // Return error into div if error is true.?>
            <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
            <?php } ?>
            <button style="bottom: 0;" class="btn btn-large btn-primary" type="submit" name="submit">Login</button>
        </form>
    </div> <!-- /container -->
</body>
</html>
