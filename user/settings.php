<?php 
    require dirname(__DIR__) . '/inc/inc.php';

    $error = false;
    $success = false;

	if ($core->getUserById($_SESSION["userId"])) {
        $account = $core->getUserById($_SESSION["userId"]);
        $accountId = $account->getId();
    } else { header('Location: index.php'); }

    if ($user->getAdmin() < $account->getAdmin()) {
        $error = true; $errormsg = 'U hebt geen toestemming om deze gebruiker aan te passen.'; $edit = 'disabled';
    }

    if ($account->getAdmin() >= 1) {
        $checked = 'checked';
    }

    if (isset($_POST["submit"])) {
        if (!empty($_POST["email"])) {
            $account->setId($accountId)
                    ->setEmail(trim($_POST["email"]));
            if (!empty($_POST["password1"]) && !empty($_POST["password2"])) {
                if ($_POST["password1"] === $_POST["password2"]) {
                    $account->setPassword($_POST["password1"]);
                    $core->editPassword($account);
                }
                else { $error = true; $errormsg = 'Wachtwoorden komen niet overeen.'; }
            }
            if (!$error) {
                $core->editUser($account);
                $success = true; $successmsg = 'Gegevens zijn aangepast.';
            }
        }
        else { $error = true; $errormsg = 'Email kan niet leeg zijn.'; }
    }
?>

<html>
	<head>
		<!-- Minified Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="../css/custom.css">
        <!-- Minified JS library -->
        <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
        <!-- Minified Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>
    
	</head>
	<body>
        <?php require dirname(__DIR__) . '/inc/navbar.inc.php'; ?>
        <div class="container">
            <form id="layout-form" class="form-signin text-center" method="post" enctype="multipart/form-data">
                <h4></h4><br>
                <label for="username">Gebruikersnaam</label>
                <input id="username" class="form-control input-block-level" value="<?= $account->getName() ?>" name="username" type="text" placeholder="Gebruikersnaam" autofocus disabled>
                <label for="email">Email</label>
                <input id="email" class="form-control input-block-level" value="<?= $account->getEmail() ?>" name="email" type="email" placeholder="Email"><br><br>
                <label for="email">Wachtwoord</label>
                <p style="top: 0; padding: 0;">Laat leeg om niet aan te passen.</p>
                <input id="password1" class="form-control input-block-level" name="password1" type="password" placeholder="Wachtwoord">
                <label for="password2">Bevestig wachtwoord</label>
                <p style="top: 0; padding: 0;">Laat leeg om niet aan te passen.</p>
                <input id="password2" class="form-control input-block-level" name="password2" type="password" placeholder="Bevestig wachtwoord">
                <?php if ($error) { // Return error into div if error is true.?>
                <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
                <?php } ?>
                <?php if ($success) { // Return error into div if error is true.?>
                <div class="alert alert-success" style="padding: 5px;"><strong>Gelukt!</strong> <?= $successmsg ?></div>
                <?php } ?>
                <button class="btn btn-large btn-primary" type="submit" name="submit">Verzenden</button>
            </form>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>