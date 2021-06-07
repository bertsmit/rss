<?php 
    require dirname(__DIR__) . '/inc/inc.php';
    require dirname(__DIR__) . '/classes/rubric.class.php';
    $rubrics = $core->getRubrics();

    if($user->getAdmin() < 1) {
        header('Location: index.php');
        die();
    }

    

    $error = false;

	if (isset($_POST['submit'])) {
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {
            if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
                if ($_POST["password1"] === $_POST["password2"]) {
                    if (!$core->getUserByName(trim($_POST["username"]))) {
                        $account = new User();
                        $account->setName(trim($_POST['username']))
                            ->setEmail(trim($_POST['email']))
                            ->setPermissions(implode(',', $_POST["permissions"]))
                            ->setPassword(trim($_POST['password1']));
                        if ($_POST['type'] === 'admin') {
                            $account->setAdmin(1);
                        } else { $account->setAdmin(0); }
                        $core->addUser($account);
                        header("Location: index.php");
                    }
                    else { $error = true; $errormsg = 'Gebruikersnaam is al in gebruik.'; }
                }
                else { $error = true; $errormsg = 'Wachtwoorden komen niet overeen.'; }
            }
            else { $error = true; $errormsg = 'Vul een geldig e-mailadres in.'; }
        }
        else { $error = true; $errormsg = 'Alle velden zijn verplicht.'; }
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
                <h4>Nieuwe gebruiker aanmaken</h4><br>
                <label for="username">Gebruikersnaam</label>
                <input id="username" class="form-control input-block-level" name="username" type="text" placeholder="Gebruikersnaam" autofocus>
                <label for="username">Email</label>
                <input id="email" class="form-control input-block-level" name="email" type="email" placeholder="Email"><br><br>
                <label for="email">Wachtwoord</label>
                <input id="password1" class="form-control input-block-level" name="password1" type="password" placeholder="Wachtwoord">
                <label for="password2">Bevestig wachtwoord</label>
                <input id="password2" class="form-control input-block-level" name="password2" type="password" placeholder="Bevestig wachtwoord"><br>
                <label>Rubrieken</label>
                <?php foreach($rubrics as $rubric) {  ?>
                <div style="text-align: left;"><input type="checkbox" name="permissions[]" value="<?= $rubric->getId(); ?>"> <?= $rubric->getName(); ?></div>
                <?php } ?><br><br>
                <input type="checkbox" name="type" value="admin"> <strong>Administrator</strong><br><br>
                <?php if ($error) { // Return error into div if error is true.?>
                <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
                <?php } ?>
                <button class="btn btn-large btn-primary" type="submit" name="submit">Verzenden</button>
            </form>
            <div class="text-center"><a type="button" class="btn btn-primary" href="index.php">Gebruikers beheren</a></div>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>