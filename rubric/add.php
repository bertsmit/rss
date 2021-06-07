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
        if (!empty($_POST['rubricname'])) {

            if (!$core->getRubricByName(trim($_POST["rubricname"]))) {
                        $rubric = new Rubric();
                        $rubric->setName(trim($_POST['rubricname']));
                        $core->addRubric($rubric);
                        header("Location: index.php");
                    }
                    else { $error = true; $errormsg = 'Rubriek bestaat al.'; }

            
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
                <label for="rubricname">Rubrieknaam</label>
                <input id="rubricname" class="form-control input-block-level" name="rubricname" type="text" placeholder="Rubrieknaam" autofocus>
                 <button class="btn btn-large btn-primary" type="submit" name="submit">Verzenden</button>
            </form>
            <div class="text-center"><a type="button" class="btn btn-primary" href="index.php">Rubrieken beheren</a></div>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>