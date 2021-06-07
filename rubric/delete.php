<?php
require dirname(__DIR__) . '/inc/inc.php';
require dirname(__DIR__) . '/classes/rubric.class.php';

$rubric = $core->getRubric($_POST["rubricId"]);


if (isset($_POST["delete"]) && $rubric) {
 
    $core->deleteRubric($rubric);
     header('Location: index.php');
    
} else { header('Location: index.php'); }