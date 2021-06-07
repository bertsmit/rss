<?php
    session_start();

    if (!isset($_SESSION["userId"])) {
        header('Location: login');
    }
    
    require 'classes/upload.class.php';
    require 'classes/message.class.php';
    require 'classes/user.class.php';
    require 'classes/core.class.php';

    $core = new Core();
    $mClass = new Message();
    $upload = new Upload();    

    $user = $core->getUserById($_SESSION["userId"]);
    