<?php 
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require dirname(__DIR__) . '/inc/inc.php';

if ($user->getAdmin() < 1) {
    header('Location: ../index.php');
    die();
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

	</head>
	<body>
        <?php require dirname(__DIR__) . '/inc/navbar.inc.php';
        
        ?>
        <div class="container">
        <a type="button" class="btn btn-primary" href="add.php">Nieuwe gebruiker aanmaken</a>
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Gebruikersnaam</th>
                            <th>Email</th>
                            <th>Bewerken</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $users = $core->getUsers();
                            
                            if ($users) {
                            foreach ($users as $user) {
                                
                                $id = $user->getId();
                                $username = $user->getName();
                                $email = $user->getEmail();

                                // Set content for each field. ?>
                        	<tr>
                                <td><?= $username ?></td>
                                <td><?= $email ?></td>
                                <td><a href="edit.php?id=<?= $id ?>">Bewerken</a></td>
                            </tr>
                        <?php } } ?>
                        </tbody>
                </table>
            </div>
        </div>
        </div>
        
        <script src="../js/file.js" type="text/javascript"></script>
    </body>
</html>