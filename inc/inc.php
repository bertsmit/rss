<?php
    session_start();
    
    if (!isset($_SESSION["userId"])) {
        header('Location: login');
    }

    $data = parse_ini_file(dirname(__DIR__) . '/config.ini');
    $url = $data['url'];

    date_default_timezone_set('Europe/Amsterdam');

    require dirname(__DIR__) . '/classes/upload.class.php';
    require dirname(__DIR__) . '/classes/user.class.php';
    require dirname(__DIR__) . '/classes/core.class.php';

    $core = new Core();
    $upload = new Upload();    

    $user = $core->getUserById($_SESSION["userId"]);
    