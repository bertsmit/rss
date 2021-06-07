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

	if ($core->getRubric($_GET["id"])) {
	    $rubric = $core->getRubric($_GET["id"]);
	    $rubricId = $rubric->getId();
    } else { header('Location: index.php'); }
    
    if (isset($_POST["submit"])) {
        if (!empty($_POST["name"])) {
                if ($core->getRubricByName(trim($_POST["name"]))) {
                    if($core->getRubricByName(trim($_POST["name"]))->getName() != $_POST["name"]) {
                        $error = true; $errormsg = 'Rubrieknaam is al in gebruik.';
                    }
                } else { $error = false; }
                if (!$error) {
                    $rubric = new Rubric();
                    $rubric->setId($rubricId)
                            ->setName(trim($_POST["name"]));

                    if (!$error) {
                        $core->editRubric($rubric);
                        header('Location: index.php');
                    }
                }
       

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
                <label for="rubricname">Rubriek</label>
                <input id="name" class="form-control input-block-level" value="<?= $rubric->getName() ?>" name="name" type="text" placeholder="Rubriek" autofocus <?= $disabled ?>>

                <button class="btn btn-large btn-primary" type="submit" name="submit" <?= $disabled ?>>Verzenden</button>
            </form>
            <form method="post" action="delete.php">
                <input type="hidden" name="rubricId" value="<?= $rubric->getId() ?>">
                <center><button class="btn btn-large btn-danger" type="submit" name="delete" onclick="return confirm('Weet je zeker dat je deze rubriek wilt verwijderen?')" <?= $disabled ?>>Verwijderen</button></center>
            </form>
            <div class="text-center"><a type="button" class="btn btn-primary" href="index.php">Rubrieken beheren</a></div>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>