<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'classes/item.class.php';
    require 'classes/rubric.class.php';
    require 'classes/upload.class.php';
    require 'classes/user.class.php';
    require 'classes/core.class.php';

    date_default_timezone_set('Europe/Amsterdam');

    $core = new Core();
    $upload = new Upload();


    $rubrics = [];
    $activeItems = $core->getActiveItems();
    echo date("Y-m-d H:i:s").'<br>';
    if ($activeItems) {
        foreach ($activeItems as $item) {
            if (!in_array($item->getRubric(), $rubrics))
            {
                $rubrics[] = $item->getRubric(); 
            }
        }
    }
    
    $inactiveItems = $core->getInactiveItems();
    if ($inactiveItems) {
        foreach($inactiveItems as $item) {
            if (!in_array($item->getRubric(), $rubrics))
            {
                $rubrics[] = $item->getRubric(); 
            }
	    $core->deleteItem($item->getId());
        if (file_exists('uploads/'.$item->getImage())) {
            unlink('uploads/'.$item->getImage());
        }
        }
    }

    if (count($rubrics) >= 1) {
        foreach($rubrics as $rubric) {
            $core->writeXml($rubric);
        }
    }
?>