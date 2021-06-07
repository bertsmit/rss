<?php
    
require_once dirname(__DIR__) . '/classes/connect.class.php';
require_once dirname(__DIR__) . '/classes/setting.class.php';

class Core
{ 
    private $conn;
    
    
    function __construct()
    { 
        $connection = new db();
        $this->conn = $connection->setupConn();
    }
    // Deze functie haalt de instelling op bij naam en verwerkt het in de 'Setting' class.
    function getSetting($name)
    {
        $stm = $this->conn->prepare('SELECT * FROM settings WHERE setting=:setting_name');
        $stm->bindParam(":setting_name", $name);
        $stm->setFetchMode(PDO::FETCH_CLASS,"Setting");
        $stm->execute();
        
        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    
    function setSetting($setting, $value) {
        $stmt = $this->conn->prepare("UPDATE settings SET value=:value WHERE setting = :setting");
        $stmt->bindParam(":setting", $setting);
        $stmt->bindParam(":value", $value);
        $stmt->execute();
    }
    
//    function nameExists($name) {
//        $stm = $this->conn->prepare('SELECT name FROM users WHERE name=:name');
//        $stm->bindParam(":name", $name);
//        $stm->execute();
//        
//        if ($stm->rowCount() > 1) {
//            return true;
//        }
//    }
//    
//    function emailExists($email) {
//        
//    }
    
    // Deze functie kijkt of de gebruiker administrator is. Het haalt de informatie op met de gebruikers id. Als dit het geval is geeft hij 'true' terug, en anders geeft hij 'false' terug.
    function isAdmin($id) {
        $stm = $this->conn->prepare("SELECT admin FROM users WHERE id=:id LIMIT 1");
        $stm->bindParam(":id",$id);
        $stm->execute();
        $admin = $stm->fetch();

        if($admin == 1) {
            return true;
        }
    }
    // Deze funcite haalt alle gegevens van de gebruiker op met behulp van het id en zet deze om naar de 'User' class.
    function getUserById($id) {
        $stm = $this->conn->prepare("SELECT * FROM users WHERE id=:id LIMIT 1");
        $stm->bindParam(":id",$id);
        $stm->setFetchMode(PDO::FETCH_CLASS,"User");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    // Als er geen id beschikbaar is, kan je ook de gegevens van een gebruiker ophalen met de naam. Dit wordt ook omgezet naar de 'User' class.
    function getUserByName($name)
    {
        $stm = $this->conn->prepare("SELECT * FROM users WHERE name=:name LIMIT 1");
        $stm->bindParam(":name",$name);
        $stm->setFetchMode(PDO::FETCH_CLASS,"User");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    // Als er geen id en gebruikersnaam beschikbaar zijn is het nog mogelijk om de gegevens van een gebruiker op te halen met de e-mailadres. Dit wordt ook omgezet naar de 'User' class.
    function getUserByEmail($email) {
        $stm = $this->conn->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
        $stm->bindParam(":email",$email);
        $stm->setFetchMode(PDO::FETCH_CLASS,"User");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    // Deze functie haalt alle rubrieken op zodat ze later met behulp van een foreach of for loop weergegeven kunnen worden of gecheckt kunnen worden. De opgehaalde data wordt omgezet naar de 'Rubric' class.
    function getRubric($id)
     {
        $stm = $this->conn->prepare("SELECT * FROM rubrics WHERE id=:id LIMIT 1");
        $stm->bindParam(":id",$id);
        $stm->setFetchMode(PDO::FETCH_CLASS,"Rubric");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    function getRubrics()
     {
         $stm = $this->conn->prepare("SELECT * FROM rubrics");
         $stm->execute();

         if ($stm->rowCount() > 0) {
             return $stm->fetchAll(PDO::FETCH_CLASS,"Rubric");
         }
    }
    
    // Deze functie voegt een gebruiker toe. De gebruiker moet met de 'User' class verzonden worden naar de functie. Hiervoor is een gebruikersnaam, email en een wachtwoord nodig.
    function addRubric(Rubric $rubric) {
        // Random generated salt to encrypt password with.

        $name = $rubric->getName();
        
        $stmt = $this->conn->prepare("INSERT INTO rubrics(name) VALUES (:name)");
        $stmt->bindParam(":name",$name);
        $stmt->execute();
    }
    
    // Als er geen id beschikbaar is, kan je ook de gegevens van een rubric ophalen met de naam. Dit wordt ook omgezet naar de 'rubric' class.
    function getRubricByName($name)
    {
        $stm = $this->conn->prepare("SELECT * FROM rubrics WHERE name=:name LIMIT 1");
        $stm->bindParam(":name",$name);
        $stm->setFetchMode(PDO::FETCH_CLASS,"Rubric");
        $stm->execute();
        
        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    
// Deze functie past de rubriek aan. De informatie moet met de 'User' class in de functie gebruikt worden. Met de id van de gebruiker worden de gegevens aangepast.
function editRubric(Rubric $rubric) {
    $id = $rubric->getId();
    $name = $rubric->getName();
    
    $stmt = $this->conn->prepare("UPDATE rubrics SET name=:name WHERE id=:id");
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":name",$name);
    $stmt->execute();
    
}
    
// Voor deze functie moeten ook gegevens van de gebruiker verstuurd worden met de 'User' class. Alleen het id is hiervoor nodig. De gebruiker wordt gelijk verwijdert.
function deleteRubric(Rubric $rubric) {
    $RubricId = $rubric->getId();
       
    //and delete the permissions 
    $stm = $this->conn->prepare("SELECT id, permissions FROM users where permissions LIKE :id ");
    $stm->bindValue(":id",'%'.$RubricId.'%');
    $stm->execute();
    
    if ($stm->rowCount() > 0) {
        
        $userspermissions = $stm->fetchAll(PDO::FETCH_CLASS,"User");
        
        foreach ($userspermissions as $UserChangePermission) {
            $UserId = $UserChangePermission->getId(); 
            
            $oldPerm = $UserChangePermission->getPermissions();
            // the values are seperated by comma we need to get rid of them as well
            $newPerm = str_replace(','.$RubricId, '', $oldPerm); 
            $newPerm = str_replace($RubricId.',', '', $newPerm); 
            $newPerm = str_replace($RubricId, '', $newPerm); 
            
            $stmt = $this->conn->prepare("UPDATE users SET permissions=:permissions WHERE id=:id");
            $stmt->bindParam(":id",$UserId);
            $stmt->bindParam(":permissions",$newPerm);
            $stmt->execute();
        }
    }
    
    $stmt = $this->conn->prepare("DELETE FROM rubrics WHERE id=:id");
    $stmt->bindParam(":id",$RubricId);
    $stmt->execute();
    
}

    // Deze functie haalt alle gebruikers op en zet ze om naar de 'User' class.
     function getUsers()
     {
         $stm = $this->conn->prepare("SELECT * FROM users");
         $stm->execute();

         if ($stm->rowCount() > 0) {
             return $stm->fetchAll(PDO::FETCH_CLASS,"User");
         }
     }
    // Deze functie voegt een gebruiker toe. De gebruiker moet met de 'User' class verzonden worden naar de functie. Hiervoor is een gebruikersnaam, email en een wachtwoord nodig. 
    function addUser(User $user) {
        // Random generated salt to encrypt password with.
        $salt = rand(111111111, 999999999);
        $password = md5(md5($salt).md5($user->getPassword()));
        $name = $user->getName();
        $email = $user->getEmail();
        $admin = $user->getAdmin();
        $permissions = $user->getPermissions();

        $stmt = $this->conn->prepare("INSERT INTO users(name, email, salt, password, permissions, admin) VALUES (:name, :email, :salt, :password, :permissions, :admin)");
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":salt",$salt);
        $stmt->bindParam(":password",$password);
        $stmt->bindParam(":permissions",$permissions);
        $stmt->bindParam(":admin",$admin);
        $stmt->execute();
    }
    // Deze functie past de gebruiker aan. De informatie moet met de 'User' class in de functie gebruikt worden. Met de id van de gebruiker worden de gegevens aangepast.
    function editUser(User $user) {
        $id = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();
        $admin = $user->getAdmin();
        $permissions = $user->getPermissions();
        
        $stmt = $this->conn->prepare("UPDATE users SET name=:name, email=:email, permissions=:permissions, admin=:admin WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":permissions",$permissions);
        $stmt->bindParam(":admin",$admin);
        $stmt->execute();
    }
    // Voor deze functie moeten ook gegevens van de gebruiker verstuurd worden met de 'User' class. Alleen het id is hiervoor nodig. De gebruiker wordt gelijk verwijdert.
    function deleteUser(User $user) {
        $id = $user->getId();
        
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
    }
    // Om het wachtwoord te veranderen wordt deze functie apart gebruikt. Hier moeten ook de gegevens verzonden worden met de 'User' class.
    function editPassword(User $user) {
        $id = $user->getId();
        $salt = rand(111111111, 999999999);
        $password = md5(md5($salt).md5($user->getPassword()));
        
        $stmt = $this->conn->prepare("UPDATE users SET salt=:salt, password=:password WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->bindParam(":salt",$salt);
        $stmt->bindParam(":password",$password);
        $stmt->execute();
    }
    // Met deze functie worden berichten toegevoegd. In de functie worden de datums ook geformateerd om in de database te kunnen, omdat ze van de datetimepicker in Nederlands formaat komen. De berichten moeten verzonden worden met de 'Item' class.
    function addItem(Item $item) {
        $enddate = DateTime::createFromFormat('d/m/Y H:i', $item->getEndDate());
        $enddate = $enddate->format('Y-m-d H:i:s');
        
        $startdate = DateTime::createFromFormat('d/m/Y H:i', $item->getStartDate());
        $startdate = $startdate->format('Y-m-d H:i:s');
        
        $title = $item->getTitle();
        $description = $item->getDescription();
        $rubric = $item->getRubric();
        $image = $item->getImage();
        
        $stmt = $this->conn->prepare("INSERT INTO items(user_id, title, description, rubric, endDate, startDate, image) VALUES (:user_id, :title, :description, :rubric, :endDate, :startDate, :image)");
        $stmt->bindParam(":user_id",$_SESSION["userId"]);
        $stmt->bindParam(":title",$title);
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":rubric",$rubric);
        $stmt->bindParam(":endDate",$enddate);
        $stmt->bindParam(":startDate",$startdate);
        $stmt->bindParam(":image",$image);
        $stmt->execute();
    }
    // Hier worden de berichten aangepast. Ook hier worden de datums van formaat veranderd. Het is ook de bedoeling dat de gegevens hier naar verzonden worden met de 'Item' class.
    function editItem(Item $item) {
        $enddate = DateTime::createFromFormat('d/m/Y H:i', $item->getEndDate());
        $enddate = $enddate->format('Y-m-d H:i:s');
        
        $startdate = DateTime::createFromFormat('d/m/Y H:i', $item->getStartDate());
        $startdate = $startdate->format('Y-m-d H:i:s');
        
        $id = $item->getId();
        $title = $item->getTitle();
        $description = $item->getDescription();
        $rubric = $item->getRubric();
        $image = $item->getImage();
        
        $stmt = $this->conn->prepare("UPDATE items SET title=:title, description=:description, rubric=:rubric, endDate=:endDate, startDate=:startDate, image=:image WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->bindParam(":title",$title);
        $stmt->bindParam(":description",$description);
        $stmt->bindParam(":rubric",$rubric);
        $stmt->bindParam(":endDate",$enddate);
        $stmt->bindParam(":startDate",$startdate);
        $stmt->bindParam(":image",$image);
        $stmt->execute();
    }
    // Deze functie verwijdert de berichten. Hier hoeft geen class gebruikt voor te worden. Het enige dat hiervoor nodig is, is het id.
    function deleteItem($id) {      
        $stmt = $this->conn->prepare("DELETE FROM items WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
    }
    // Deze functie haalt alle gegevens van een bericht op met het id en zet het om naar de 'Item' class.
    function getItem($id)
    {
        $stm = $this->conn->prepare("SELECT * FROM items WHERE id=:id LIMIT 1");
        $stm->bindParam(":id",$id);
        $stm->setFetchMode(PDO::FETCH_CLASS,"Item");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetch();
        }
    }
    // Deze functie haalt alle berichten op en zet deze om naar de 'Item' class. Deze kunnen gebruikt worden in bijvoorbeeld een foreach of for loop.
    function getItems()
    {
        $stm = $this->conn->prepare("SELECT * FROM items");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetchAll(PDO::FETCH_CLASS,"Item");
        }
    }
    // Functie die de actieve berichten ophaalt. Berichten die tussen de einddatum en startdatum in zitten.
    function getActiveItems()
    {
        $stm = $this->conn->prepare("SELECT * FROM items WHERE startDate <= NOW() AND endDate >= NOW() AND active = 0");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetchAll(PDO::FETCH_CLASS,"Item");
        }
    }
    
    function setItemActive($id)
    {
        $stmt = $this->conn->prepare("UPDATE items SET active=1 WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
    
    function getInactiveItems()
    {
        $stm = $this->conn->prepare("SELECT * FROM items WHERE endDate <= NOW() AND active = 1");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            return $stm->fetchAll(PDO::FETCH_CLASS,"Item");
        }
    }
    // Deze functie zet alle gegevens uit de 'items' database naar xml. Er wordt een id van een rubriek meegegeven omdat er voor alle rubrieken een apart xml bestand is.
    function writeXml($rubricId) {
        date_default_timezone_set('Europe/Amsterdam');     
        
        $stm = $this->conn->prepare("SELECT * FROM items WHERE startDate <= NOW() AND endDate >= NOW() AND rubric=:rubric");
        $stm->bindParam(":rubric", $rubricId);
        $stm->execute();

        if ($stm->rowCount() > 0) {
            $items = $stm->fetchAll(PDO::FETCH_CLASS,"Item");
        }
        
        
        $rubric = $this->getRubric($rubricId);
        
        $data = parse_ini_file(dirname(__DIR__) . '/config.ini');
        $uploadUrl = $data['url'];
        
        $rubricName = str_replace(' ', '-', $rubric->getName());
        // If xml file exists it will be rewriten.
        if(file_exists(dirname(__DIR__) .'/data/'.$rubricName.'.xml')) unlink(dirname(__DIR__) .'/data/'.$rubricName.'.xml');

        $dom = new DomDocument('1.0');
        
        $rss = $dom->createElement('rss');
        $dom->appendChild($rss);
        $rss->setAttribute('version', '2.0');
        
        $channel = $rss->appendChild($dom->createElement('channel'));

        $cTitle = $channel->appendChild($dom->createElement('title')); 
        $cLink = $channel->appendChild($dom->createElement('link')); 
        $cDesc = $channel->appendChild($dom->createElement('description')); 
        
        $cTitle->appendChild(
            $dom->createTextNode($this->getSetting('rss_title')->getValue()));
        $cLink->appendChild(
            $dom->createTextNode($uploadUrl));
        $cDesc->appendChild(
            $dom->createTextNode($this->getSetting('rss_description')->getValue()));
        
        if ($items) {
            for ($i = 0; $i < count($items); $i++) {
                $item = $channel->appendChild($dom->createElement('item')); 
                $title = $item->appendChild($dom->createElement('title')); 
                $link = $item->appendChild($dom->createElement('link')); 
                $description = $item->appendChild($dom->createElement('description')); 

                $title->appendChild(
                    $dom->createTextNode($items[$i]->getTitle()));
                $link->appendChild(
                    $dom->createTextNode($uploadUrl.'/uploads/'.$items[$i]->getImage()));
                $description->appendChild(
                    $dom->createTextNode($items[$i]->getDescription()));

                if (!empty($items[$i]->getImage())) {
                    $enclosure = $item->appendChild($dom->createElement('enclosure'));
                    // Add attributes to media tag.
                    $enclosure->setAttribute('url', $uploadUrl.'/uploads/'.$items[$i]->getImage());
                    $enclosure->setAttribute('length', '0');
                    $enclosure->setAttribute('type', 'image/jpeg');
                }
                
                $this->setItemActive($items[$i]->getId());
            }
        }
        
        // Readable format.
        $dom->formatOutput = true;
        $dom->save(dirname(__DIR__) .'/data/'.$rubricName.'.xml');
    }
}

?>