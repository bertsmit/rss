<?php
    // This gets a complete URL for the image. (https://www.example.com/file/img.jpg)
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $pathinfo_ = pathinfo($_SERVER["PHP_SELF"]);
    $url = $protocol."://".$_SERVER["SERVER_NAME"].$pathinfo_["dirname"];

    $file = fopen("config.ini", "w") or die("Unable to open config.ini! Create the file.");
    $txt = "[website]\n";
    fwrite($file, $txt);
    $txt = "url = " . $url;
    fwrite($file, $txt);
    fclose($file);

    echo 'Met de setup wordt alleen het config.ini automatisch ingevuld met de URL van de website. Als je een error krijgt of het lukt niet, kan je dit handmatig invullen.';
?>