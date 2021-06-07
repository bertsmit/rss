<?php 
    require 'inc/inc.php';
    require 'classes/item.class.php';

    // Get all rubrics.
    require 'classes/rubric.class.php';
    $rubrics = $core->getRubrics();

    // Get max width and max height for images from database.
    $max_width = $core->getSetting('max_width')->getValue();
    $max_height = $core->getSetting('max_height')->getValue();

    $disabled = '';
    $error = false;

    $permissions = explode(',', $user->getPermissions());
    if (is_null($user->getPermissions()) || empty($user->getPermissions())) {
        if ($user->getAdmin() < 1) {
            $disabled = 'disabled';
            $error = true;
            $errormsg = 'Geen toegang tot een rubriek.';
        }
    } else { $error = false; $disabled = ''; }
    
    // Run code on submit.
    if (isset($_POST["submit"])) {
        if (!empty($_POST["title"]) && isset($_POST["title"]) && !empty($_POST["description"]) && isset($_POST["description"])) {
            if (strlen($_POST["title"]) >= 3) {
            if (strlen($_POST["description"]) >= 3) {
                $upload = new Upload();
                // xml file location and name.
                $id = uniqid();
                // Target_dir media.
                $target_dir = '/uploads/';
                $uploadOk = false;
                $continue = true;
                
                // Check if an image is given.
                if (file_exists($_FILES['media']['tmp_name']) || is_uploaded_file($_FILES['media']['tmp_name'])) {
                    
                    $path_parts = pathinfo($_FILES["media"]["name"]);
                    $ext = $path_parts['extension'];
                    
                    // Toegestaande extensies/bestanden.
                    $allow_ext = ['jpeg', 'jpg', 'png', 'gif'];
                    
                    if (!in_array($ext, $allow_ext)) {
                        $error = true; $continue = false; $errormsg = "Alleen afbeeldingen zijn toegestaan.";
                    }
                    
                    // If image upload is success, upload_status is true.
                    if ($continue) {
                        if ($upload->uploadFile($_FILES["media"]["name"], $_FILES["media"]["tmp_name"], dirname(__FILE__).$target_dir, $id)) {
                            
                            // Require image editor.
                            require 'classes/claviska/SimpleImage.php';
                            $simpleImage = new \claviska\SimpleImage();

                            if ($upload->maxImageSize($url.$target_dir.$id.'.'.$ext, $max_width, $max_height)) {
                                $uploadOk = true;  
                            }
                            else {
                                try {
                                    // Resize the image.
                                    $simpleImage->fromFile($url.$target_dir.$id.'.'.$ext)
                                    ->bestFit($max_width, $max_height);
                                    if(file_exists('uploads/'.$id.'.'.$ext)) { unlink('uploads/'.$id.'.'.$ext); }
                                    $simpleImage->toFile('uploads/'.$id.'.jpg', 'image/jpeg');
                                    $ext = 'jpg';
                                    $uploadOk = true;
                                    
                                } catch(Exception $err) {
                                    // Handle errors
//                                    echo $err->getMessage();
                                    $error = true; $continue = false; $errormsg = 'Probeer een andere afbeelding.';
                                }
                            }
                            }
                        }
                        // Else $continue will not be set to true and it won't forward any changes. 
                        else { $error = true; $continue = false; $errormsg = 'De afbeelding heeft niet kunnen uploaden.'; }           
                }
                
                // If there are no errors, continue the process.
                if ($continue) {
                    $item = new Item();
                    
                    if(!$uploadOk) {
                       $image = 'nothing.png'; 
                    } else { $image = $id.'.'.$ext; }
                    
                    $item->setUserId($_SESSION["userId"])
                        ->setTitle(trim($_POST["title"]))
                        ->setDescription(trim($_POST["description"]))
                        ->setRubric(trim($_POST["rubric"]))
                        ->setEndDate(trim($_POST["endDate"]))
                        ->setStartDate(trim($_POST["startDate"]))
                        ->setImage($image);
                    $core->addItem($item);
                    
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
            // Return error into the field.
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
        <?php require 'inc/navbar.inc.php'; ?>
        <div class="container">
            <form id="layout-form" class="form-signin text-center" method="post" enctype="multipart/form-data">
    
                <p>Nieuw bericht plaatsen</p>
    
                <input id="title" class="form-control input-block-level" name="title" type="text" placeholder="Titel" autofocus <?= $disabled ?>>
                <textarea id="description" class="form-control input-block-level" name="description" placeholder="Bericht" rows="5" <?= $disabled ?>></textarea><br>
				<label for="media">Afbeelding</label>
			    <div class="input-group">
                    <label class="input-group-btn">
                        <span class="btn btn-primary">
                            Browse&hellip; <input type="file" name="media" style="display: none;" <?= $disabled ?>>
                        </span>
                    </label>
                    <input style="padding: 5px 9px;" type="text" class="form-control" readonly <?= $disabled ?>>
                </div>
			    <label for="display">Rubriek</label>
                <select class="form-control" name="rubric" id="display" <?= $disabled ?>>
                <option disabled>Rubrieken</option>
                <?php for ($i = 0; $i < count($rubrics); $i++) { 
                    if(in_array($rubrics[$i]->getId(), $permissions) || $user->getAdmin() >= 1) { ?>
					<option value="<?= $rubrics[$i]->getId(); ?>"><?= $rubrics[$i]->getName(); ?></option>                        
                <?php }} ?>
            	</select><br>
            	<label for="startDate">Van (datum en tijd)</label>
            	<div class='input-group date' id='startDate'>
                    <input style="margin-bottom: 0px;" type='text' class="form-control" name="startDate" <?= $disabled ?> />
                    <span class="input-group-addon">
                        <span style="" class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            	<br>
                <label for="endDate">Tot (datum en tijd)</label>
                <div class='input-group date' id='endDate'>
                    <input style="margin-bottom: 0px;" type='text' class="form-control" name="endDate" <?= $disabled ?> />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <br>
                <?php if ($error) { // Return error into div if error is true.?>
                <div class="alert alert-danger" style="padding: 5px;"><strong>Fout!</strong> <?= $errormsg ?></div>
                <?php } ?>
                <button class="btn btn-large btn-primary" type="submit" name="submit" <?= $disabled ?>>Verzenden</button>
            </form>
            <div class="text-center"><a type="button" class="btn btn-primary" href="edit.php">Een bericht aanpassen</a></div>
        </div> <!-- /container -->
    <script src="../js/file.js" type="text/javascript"></script>
	<script type="text/javascript">
    	$("#startDate").datetimepicker({
        	defaultDate: moment(),
    	    format: 'DD/MM/YYYY HH:mm',
    	    minDate: moment(),
    	    useCurrent: true,
    	    language: 'nl'
    	});
    	$("#endDate").datetimepicker({
    		defaultDate: moment().add(2, 'd').toDate(),
    	    format: 'DD/MM/YYYY HH:mm',
    	    minDate: moment(),
    	    useCurrent: true,
    	    language: 'nl'
    	});
    </script>
    <script src="../js/date-time-format.js"></script>
	<script src="../js/bootstrap-datetimepicker.min.js"></script>
		
    </body>
</html>