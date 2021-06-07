<?php

class db
{

    private $conn;

    function __construct()
    {

    }
    
    public function setupConn()
    {
        
        if (!isset($conn)) {
//             $data = parse_ini_file('config.ini');
            
            $mysql_host = 'localhost';
            $mysql_username = 'rss';
            $mysql_password = 'newscast';
            $mysql_db = 'rss';
            
            try {
                $conn = new PDO("mysql:host=" . $mysql_host . ";dbname=" . $mysql_db . "", $mysql_username, $mysql_password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                echo "Verbinding met de Xibo database is gefaald!: " . $e->getMessage();
            }
            
            return $conn;
        }
        
    }

}

?>
