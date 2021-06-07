<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require dirname(__DIR__) . '/inc/inc.php';

    if ($user->getAdmin() < 1) {
        header('Location: ../index.php');
    }

    $error = false;
    $success = false;

    $web_title = $core->getSetting('web_name')->getValue();
    $rss_title = $core->getSetting('rss_title')->getValue();
    $rss_description = $core->getSetting('rss_description')->getValue();
    $max_width = $core->getSetting('max_width')->getValue();
    $max_height = $core->getSetting('max_height')->getValue();

    if(isset($_POST["submit"])) {
        if(!empty($_POST["website_title"])
          && !empty($_POST["rss_title"])
          && !empty($_POST["rss_description"])
          && !empty($_POST["max_width"])
          && !empty($_POST["max_height"])) {
            if (is_numeric($_POST["max_width"]) && is_numeric($_POST["max_height"])) {
                $core->setSetting('web_name', $_POST["website_title"]);  
                $core->setSetting('rss_title', $_POST["rss_title"]);
                $core->setSetting('rss_description', $_POST["rss_description"]);
                $core->setSetting('max_width', $_POST["max_width"]);
                $core->setSetting('max_height', $_POST["max_height"]);
                header('Location: ../index.php');
            } else { $error = true; $errormsg = 'Maximale grootte en breedte moeten een getal zijn.'; }
        } else { $error = true; $errormsg = 'Alle velden moeten ingevuld zijn.'; }
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
            <form id="layout-form" class="form-signin text-center" method="post">
                <h4></h4><br>
                <label for="website_title">Website naam</label>
                <input id="website_title" class="form-control input-block-level" value="<?= $web_title ?>" name="website_title" type="text" placeholder="Website naam" autofocus>
                <label for="rss_title">RSS titel</label>
                <input id="rss_title" class="form-control input-block-level" value="<?= $rss_title ?>" name="rss_title" type="text" placeholder="Titel">
                <label for="rss_description">RSS beschrijving</label>
                <textarea id="rss_description" class="form-control input-block-level" name="rss_description" placeholder="Bericht" rows="5"><?= $rss_description ?></textarea><br>
                <label for="max_width">Maximale breedte afbeeldingen</label>
                <center><input id="max_width" style="width: 100px;" class="form-control input-block-level" value="<?= $max_width ?>" name="max_width" type="number"></center>
                <label for="max_height">Maximale hoogte afbeeldingen</label>
                <center><input id="max_height" style="width: 100px;" class="form-control input-block-level" value="<?= $max_height ?>" name="max_height" type="number"></center><br>
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