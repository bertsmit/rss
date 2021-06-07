<?php 
    require 'inc/inc.php';

    require 'classes/item.class.php';

    // Get all rubrics.
    require 'classes/rubric.class.php';
    $rubrics = $core->getRubrics();
    
    $upload = new Upload();
    
    $target_dir = '/uploads/';
    // Edit by defualt false.
    $edit = false;
    // Target direction for images.
    $uploadOk = false;
    $continue = true;
    // Error by default false.
    
    // Maximale grootte en breedte voor de afbeeldingen.
    $max_width = $core->getSetting('max_width')->getValue();
    $max_height = $core->getSetting('max_height')->getValue();

    $disabled = '';
    $error = false;

    $admin = $user->getAdmin();

    $permissions = explode(',', $user->getPermissions());
    if (is_null($user->getPermissions()) || empty($user->getPermissions())) {
        if ($user->getAdmin() < 1) {
            $disabled = 'disabled';
            $error = true;
            $errormsg = 'Geen toegang tot een rubriek.';
        }
    } else { $error = false; $disabled = ''; }
    
    if(isset($_GET["id"]) && !empty($_GET["id"])) {
        // If an id is given, set edit to true.
        $edit = true;
        
        $item = $core->getItem($_GET["id"]);

        // If message exists, return.
        if ($item) {
            // If the user is the creator or admin, allow editing.
            $title = $item->getTitle();
            $description = $item->getDescription();
            $rubric = $item->getRubric();
            $image = $item->getImage();
            $dStart = new DateTime($item->getStartDate());
            $dEnd = new DateTime($item->getEndDate());
            $startdate = $dStart->format('d/m/Y H:i');
            $enddate = $dEnd->format('d/m/Y H:i');
        } else header('Location: edit.php');
    }
    
    if ($edit && isset($_POST["submit"])) {
        if (!empty($_POST["title"]) && isset($_POST["title"]) && !empty($_POST["description"]) && isset($_POST["description"])) {
            
            if (strlen($_POST["title"]) >= 3) {
            if (strlen($_POST["description"]) >= 3) {
                // ID is given in the url.
                $id = $_GET["id"];
                
                // Check if an image is given.
                if (file_exists($_FILES['media']['tmp_name']) || is_uploaded_file($_FILES['media']['tmp_name'])) {
                    
                    $path_parts = pathinfo($_FILES["media"]["name"]);
                    $ext = $path_parts['extension'];
                    
                    if (!empty($image)) {
                        $file_name = pathinfo('uploads/'.$image);
                        $id = $file_name['filename'];
                    } else { $id = uniqid(); }
                    
                    // Toegestaande extensies/bestanden.
                    $allow_ext = ['jpeg', 'jpg', 'png', 'gif'];
                    
                    // If extension is allowed, continue
                    if (!in_array($ext, $allow_ext)) {
                        $error = true; $continue = false; $errormsg = "Alleen afbeeldingen zijn toegestaan.";
                    }
                    
                    // If image upload is success, upload_status is true.
                    if ($continue) {
                        if ($upload->uploadFile($_FILES["media"]["name"], $_FILES["media"]["tmp_name"], dirname(__FILE__).$target_dir, $id)) {
                            // Require simpleimage to resize the image.
                            require 'classes/claviska/SimpleImage.php';
                            $simpleImage = new \claviska\SimpleImage();
                            
                            $path_parts = pathinfo($_FILES["media"]["name"]);
                            $ext = $path_parts['extension'];
                            
                            if ($upload->maxImageSize($url.$target_dir.$id.'.'.$ext, $max_width, $max_height)) {
                                $uploadOk = true;
                            }
                            else {
                                try {
                                    $simpleImage->fromFile($url.$target_dir.$id.'.'.$ext)
                                    ->bestFit($max_width, $max_height);
                                    if(file_exists('uploads/'.$id.'.'.$ext)) { unlink('uploads/'.$id.'.'.$ext); }
                                    $simpleImage->toFile('uploads/'.$id.'.jpg', 'image/jpeg');
                                    $ext = 'jpg';
                                    $uploadOk = true;
//                                    die();
                                } catch(Exception $err) {
                                    // Handle errors
                                    echo $err->getMessage();
                                }
                            }
                        }
                    }
                    // Else $continue will not be set to true and it won't forward any changes.
                    else { $error = true; $continue = false; $errormsg = 'De afbeelding heeft niet kunnen uploaden.'; }
                }
                
                
                // If no errors occur, continue.
                if ($continue) {            
                    $item = new Item();
                    
                    if(!$uploadOk) {
                       $imageUrl = 'nothing.png'; 
                    }
                    else { $imageUrl = $id.'.'.$ext; }
                    
                    $item->setId($_GET["id"])
                        ->setTitle(trim($_POST["title"]))
                        ->setDescription(trim($_POST["description"]))
                        ->setRubric(trim($_POST["rubric"]))
                        ->setEndDate(trim($_POST["endDate"]))
                        ->setStartDate(trim($_POST["startDate"]))
                        ->setImage($imageUrl);
                    $core->editItem($item);
                    
                    $core->writeXml($_POST["rubric"]);
                    
                    header('Location: edit.php');
                }
            } else {
                // Return error into the field.
                $error = true;
                $errormsg = "Minimaal 3 tekens voor een bericht.";
            }
            } else {
                // Return error into the field.
                $error = true;
                $errormsg = "Minimaal 3 tekens voor de titel.";
            }
        } else {
            $error = true;
            $errormsg = "Alle velden zijn verplicht.";
        }
    }
    

?>
<html>
	<head>
		<!-- Minified Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="css/custom.css">
        <!-- Minified JS library -->
        <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
        <!-- Minified Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>
        <script src="datetimepicker/bootstrap-datetimepicker.nl.js"></script>

	</head>
	<body>
        <?php include 'inc/navbar.inc.php';
        // If $edit = true it loads the form. 
       if ($edit) { ?>
        <div class="container">
            <form id="layout-form" class="form-signin text-center" method="post" enctype="multipart/form-data">
    
                <p>Bericht aanpassen.</p>
    
                <input id="title" class="form-control input-block-level" name="title" type="text" placeholder="Titel" value="<?= $title ?>" autofocus>
                <textarea id="description" class="form-control input-block-level" name="description" placeholder="Bericht" rows="5"><?= $description ?></textarea><br>
                <label for="media">Afbeelding</label>
			    <div class="input-group">
                    <label class="input-group-btn">
                        <span class="btn btn-primary file-upload">
                            Browse&hellip; <input type="file" name="media" style="display: none;">
                        </span>
                    </label>
                    <input style="padding: 5px 9px;" type="text" class="form-control" readonly>
                </div>
			    <label for="display">Rubriek</label>
                <select class="form-control" name="rubric" id="display">
                <?php for ($i = 0; $i < count($rubrics); $i++) { 
                    $selected = ($rubrics[$i]->getId() === $item->getRubric() ? 'selected' : ''); ?>
					<option value="<?= $rubrics[$i]->getId(); ?>" <?= $selected ?>><?= $rubrics[$i]->getName(); ?></option>      
                <?php } ?>
            	</select><br>
            	<label for="startDate">Van (datum en tijd)</label>
            	<div class='input-group date' id='startDate'>
                    <input style="margin-bottom: 0px;" type='text' class="form-control" name="startDate" value="<?= $startdate; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            	<br>
                <label for="endDate">Tot (datum en tijd)</label>
                <div class='input-group date' id='endDate'>
                    <input style="margin-bottom: 0px;" type='text' class="form-control" name="endDate" value="<?= $enddate; ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div><br>
                
                <?php if ($error) { // If an error occurs, send a message. ?>
                <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
                <?php } ?>
                
                <button class="btn btn-large btn-primary" type="submit" name="submit">Verzenden</button>
            </form>
        	<div class="text-center">
                <a class="btn btn-large btn-primary" type="submit" href="index.php">Nieuw bericht maken</a>
                <a class="btn btn-large btn-primary" type="submit" href="edit.php">Ander bericht aanpassen</a>
            </div>
        </div>
        <?php // If edit is false it returns a list with messages
        } else {?>
        <div class="container">
        <a type="button" class="btn btn-primary" href="index.php">Nieuw bericht maken</a>
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Gebruiker</th>
                            <th>Start</th>
                            <th>Eind</th>
                            <th>Rubriek</th>
                            <th>Bewerken</th>
                            <th>Verwijderen</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $items = $core->getItems();
                            
                            if ($items) {
                            foreach ($items as $item) {
                                
                                $id = $item->getId();
                                $itemUser = $item->getUserId();
                                
                                if ($admin >= 1 || $itemUser == $_SESSION["userId"]) {
                                    
                                    $title = $item->getTitle();
                                    $description = $item->getDescription();
                                    $rubric = $core->getRubric($item->getRubric())->getName();
                                    $creator = $core->getUserById($item->getUserId())->getName();
                                
                                    $dStart = new DateTime($item->getStartDate());
                                    $dEnd = new DateTime($item->getEndDate());
                                    $startdate = $dStart->format('d/m/Y H:i');
                                    $enddate = $dEnd->format('d/m/Y H:i');

                                // Set content for each field. ?>
                        	<tr>
                                <td><?= $title ?></td>
                                <td><?= $creator ?></td>
                                <td><?= $startdate ?></td>
                                <td><?= $enddate ?></td>
                                <td><?= $rubric ?></td>
                                <td><a href="edit.php?id=<?= $id ?>">Bewerken</a></td>
                                <td><a href="delete.php?id=<?= $id ?>">Verwijderen</a></td>
                            </tr>
                        <?php } } } ?>
                        </tbody>
                </table>
            </div>
        </div>
        </div>
        <?php } ?>
        
        <script src="js/file.js" type="text/javascript"></script>
        <script type="text/javascript">
        $("#startDate").datetimepicker({
//         	defaultDate: moment(),
    	    format: 'DD/MM/YYYY HH:mm',
    	    minDate: moment(),
    	    useCurrent: true,
    	    language: 'nl'
    	});
    	$("#endDate").datetimepicker({
//     		defaultDate: moment().add(2, 'd').toDate(),
    	    format: 'DD/MM/YYYY HH:mm',
    	    minDate: moment(),
    	    useCurrent: true,
    	    language: 'nl'
    	});
        </script>
        
        <script src="../js/date-time-format.js"></script>
		<script src="../js/bootstrap-datetimepicker.min.js"></script>
		<script src="../js/bootstrap-datetimepicker.nl.js"></script>
    </body>
</html>