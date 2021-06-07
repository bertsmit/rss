<?php
require 'inc/inc.php';
require 'classes/item.class.php';
require 'classes/rubric.class.php';

$id = $_GET["id"];

$item = $core->getItem($id);

if ($item) {
    if (file_exists('uploads/'.$item->getImage())) {
        unlink('uploads/'.$item->getImage());
    }
    
    $core->deleteItem($id);
    
    $core->writeXml($item->getRubric());
    
    header('Location: edit.php');
    
//    header('Location: edit.php');
} else { header('Location: edit.php'); }

    
?>