<?php 
    require dirname(__DIR__) . '/inc/inc.php';
    require dirname(__DIR__) . '/classes/rubric.class.php';
    $rubrics = $core->getRubrics();
    
    if($user->getAdmin() < 1) {
        header('Location: index.php');
        die();
    }

    $error = false;
    $disabled = '';
    $isAdmin = '';

	if ($core->getUserById($_GET["id"])) {
        $account = $core->getUserById($_GET["id"]);
        $accountId = $account->getId();
    } else { header('Location: index.php'); }
    
    $permissions = explode(',', $account->getPermissions());

    if ($account->getAdmin() > 1) {
        $error = true; $errormsg = 'U hebt geen toestemming om deze gebruiker aan te passen.'; $disabled = 'disabled';
    }

    if ($account->getAdmin() >= 1) {
        $isAdmin = 'checked';
    }

    if (isset($_POST["submit"])) {
        if (!empty($_POST["username"]) && !empty($_POST["email"])) {
            if ($user->getAdmin() > $account->getadmin()) {
                if ($core->getUserByName(trim($_POST["username"]))) {
                    if($core->getUserByName(trim($_POST["username"]))->getName() != $_POST["username"]) {
                        $error = true; $errormsg = 'Gebruikersnaam is al in gebruik.';
                    }
                } else { $error = false; }
                if (!$error) {
                    $account = new User();
                    $account->setId($accountId)
                            ->setName(trim($_POST["username"]))
                            ->setPermissions(implode(',', $_POST["permissions"]))
                            ->setEmail(trim($_POST["email"]));
                    if (isset($_POST["type"]) && $_POST['type'] == 'admin') {
                        $account->setAdmin(1);
                    } else { $account->setAdmin(0); }
                    if (!empty($_POST["password1"]) && !empty($_POST["password2"])) {
                        if ($_POST["password1"] == $_POST["password2"]) {
                            $account->setPassword($_POST["password1"]);
                            $core->editPassword($account);
                        }
                        else { $error = true; $errormsg = 'Wachtwoorden komen niet overeen.'; }
                    }
                    if (!$error) {
                        $core->editUser($account);
                        header('Location: index.php');
                    }
                }
            }
            else { $error = true; $errormsg = 'U hebt geen toestemming om deze gebruiker aan te passen.'; $disabled = 'disabled'; }
        }
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
                <input id="username" class="form-control input-block-level" value="<?= $account->getName() ?>" name="username" type="text" placeholder="Gebruikersnaam" autofocus <?= $disabled ?>>
                <label for="username">Email</label>
                <input id="email" class="form-control input-block-level" value="<?= $account->getEmail() ?>" name="email" type="email" placeholder="Email" <?= $disabled ?>><br><br>
                <label for="email">Wachtwoord</label>
                <p style="top: 0; padding: 0;">Laat leeg om niet aan te passen.</p>
                <input id="password1" class="form-control input-block-level" name="password1" type="password" placeholder="Wachtwoord" <?= $disabled ?>>
                <label for="password2">Bevestig wachtwoord</label>
                <p style="top: 0; padding: 0;">Laat leeg om niet aan te passen.</p>
                <input id="password2" class="form-control input-block-level" name="password2" type="password" placeholder="Bevestig wachtwoord" <?= $disabled ?>><br>
                <label>Rubrieken</label>
                <?php foreach($rubrics as $rubric) { 
                        if(in_array($rubric->getId(), $permissions)) { $checked = 'checked'; } else { $checked = ''; } ?>
                <div style="text-align: left;"><input type="checkbox" name="permissions[]" value="<?= $rubric->getId(); ?>" <?= $checked ?>> <?= $rubric->getName(); ?></div>
                <?php $checked = ''; } ?><br><br>
                <input type="checkbox" name="type" value="admin" <?= $isAdmin ?> <?= $disabled ?>> <strong>Administrator</strong><br><br>
                <?php if ($error) { // Return error into div if error is true.?>
                <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
                <?php } ?>
                <button class="btn btn-large btn-primary" type="submit" name="submit" <?= $disabled ?>>Verzenden</button>
            </form>
            <form method="post" action="delete.php">
                <input type="hidden" name="userId" value="<?= $account->getId() ?>">
                <center><button class="btn btn-large btn-danger" type="submit" name="delete" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')" <?= $disabled ?>>Verwijderen</button></center>
            </form>
            <div class="text-center"><a type="button" class="btn btn-primary" href="index.php">Gebruikers beheren</a></div>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>