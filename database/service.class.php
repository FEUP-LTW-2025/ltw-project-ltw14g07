<?php
    class Service {
        public ?int $serviceID;
        public int $userID;
        public ?string $userName;
        public string $title;
        public string $description;
        public int $hourlyRate;
        public int $deliveryTime;
        public ?string $creationDate;
        public $languages;
        public $fields;

    public function __construct(?int $serviceID, int $userID, ?string $userName, string $title, string $description, int $hourlyRate, int $deliveryTime, ?string $creationDate, $languages, $fields) {
      $this->serviceID = $serviceID;
      $this->userID = $userID;
      $this->userName = $userName;
      $this->title = $title;
      $this->description = $description;
      $this->hourlyRate = $hourlyRate;
      $this->deliveryTime = $deliveryTime;
      $this->creationDate = $creationDate;
      $this->languages = $languages;
      $this->fields = $fields;
    }

    public static function getAllServices($db, $limit = 6, $offset = 0) {
        $stmt = $db->prepare('SELECT serviceID FROM Service LIMIT ? OFFSET ?');
        $stmt->execute(array($limit, $offset));

        $services = array();
        while($row = $stmt->fetch()) {
            $services[] = Service::getService($db, $row['serviceID']);
        }
        return $services;
    }


    public static function getAllServicesByUserID($db, $userID, $offset = 0) {
        $stmt = $db->prepare('SELECT serviceID FROM Service WHERE userID = ? LIMIT ? OFFSET ?');
        $stmt->execute(array($userID, 6, $offset));

        $services = array();
        while($row = $stmt->fetch()) {
            $services[] = Service::getService($db, $row['serviceID']);
        }
        return $services;
    }


    public static function getService($db, $serviceID) {
        $stmt1 = $db->prepare('SELECT * FROM Service WHERE serviceID = ? ');
        $stmt2 = $db->prepare('SELECT language FROM ServiceLanguage WHERE serviceID = ? ');
        $stmt3 = $db->prepare('SELECT field FROM ServiceField WHERE serviceID = ? ');
        $stmt4 = $db->prepare('SELECT name FROM Users JOIN Service on Users.UserID = Service.userID WHERE serviceID = ?');
        
        $stmt1->execute(array($serviceID));
        $stmt2->execute(array($serviceID));
        $stmt3->execute(array($serviceID));
        $stmt4->execute(array($serviceID));
        
        $service = $stmt1->fetch();
        $languages = $stmt2->fetchAll();
        $fields = $stmt3->fetchAll();
        $userName = $stmt4->fetch();

        if ($service == NULL) return NULL;

        return new Service(
            $service['serviceID'],
            $service['userID'],
            $userName['name'],
            $service['title'],
            $service['description'],
            $service['hourlyRate'],
            $service['deliveryTime'],
            $service['creationDate'],
            array_column($languages, 'language'),
            array_column($fields, 'field')
        );
    }


    public static function getCreatorByID($db, $serviceID) {
        $stmt = $db->prepare('SELECT userID FROM Service WHERE serviceID = ? ');
        $stmt->execute(array($serviceID));
        $row = $stmt->fetch();
        return $row['userID'];
    }

    public function save($db) {
        if (!empty($this->serviceID)) $this->updateDatabase($db);
        else $this->insertIntoDatabase($db);
        return $this->serviceID;
    }

    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Service (userID, title, description, hourlyRate, deliveryTime) VALUES 
        (?, ?, ?, ?, ?)');

        $stmt->execute(array($this->userID, $this->title, $this->description, $this->hourlyRate, $this->deliveryTime));

        $this->serviceID = intval($db->lastInsertId());

        $stmtLang = $db->prepare('INSERT INTO ServiceLanguage (serviceID, language) VALUES (?, ?)');
        foreach ($this->languages as $language) {
            $stmtLang->execute(array($this->serviceID, $language));
        }

        $stmtField = $db->prepare('INSERT INTO ServiceField (serviceID, field) VALUES (?, ?)');
        foreach ($this->fields as $field) {
            $stmtField->execute(array($this->serviceID, $field));
        }
    } 

    public function updateDatabase($db) {
        $stmt = $db->prepare('UPDATE Service 
                            SET title = ?, description = ?, hourlyRate = ?, deliveryTime = ?
                            WHERE serviceID = ?');

        $stmt->execute(array($this->title, $this->description, $this->hourlyRate, $this->deliveryTime, $this->serviceID));


        $stmt = $db->prepare('DELETE FROM ServiceLanguage WHERE serviceID = ?');
        $stmt->execute(array($this->serviceID));

        $stmt = $db->prepare('INSERT INTO ServiceLanguage (serviceID, language) VALUES (?, ?)');
        foreach ($this->languages as $language) {
            $stmt->execute(array($this->serviceID, $language));
        }


        $stmt = $db->prepare('DELETE FROM ServiceField WHERE serviceID = ?');
        $stmt->execute(array($this->serviceID));

        $stmt = $db->prepare('INSERT INTO ServiceField (serviceID, field) VALUES (?, ?)');
        foreach ($this->fields as $field) {
            $stmt->execute(array($this->serviceID, $field));
        }
    }

    public static function delete($db, $serviceID) {
        $stmt = $db->prepare('DELETE FROM Service WHERE serviceID = ?');
        $stmt->execute(array($serviceID));
    }



}




//
//INSERT INTO Service (serviceID, userID, title, description, hourlyRate, deliveryTime, creationDate) VALUES 
//(1,
//1,
//'I will do modern mobile app ui ux design or website ui ux design',
//'As a UI UX designer, I put much value on trustful, transparent, long-term relationships. Thats why Im very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the products vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.', 
//12, 
//3,
//'2024-01-15'
//),
//
//(2,
//1,
//'I will do modern mobile app ui ux design or website ui ux design',
//'As a UI UX designer, I put much value on trustful, transparent, long-term relationships. Thats why Im very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the products vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.', 
//15, 
//4,
//'2024-01-15'
//);
//

?>
