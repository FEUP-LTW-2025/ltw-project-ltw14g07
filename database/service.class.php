<?php
    class Service {
        public int $serviceID;
        public int $userID;
        public string $userName;
        public string $title;
        public string $description;
        public int $hourlyRate;
        public int $deliveryTime;
        public string $creationDate;
        public $languages;
        public $fields;

    public function __construct(int $serviceID, int $userID, string $userName, string $title, string $description, int $hourlyRate, int $deliveryTime, string $creationDate, $languages, $fields) {
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

    public static function getAllServices($db, $limit) {
        $stmt = $db->prepare('SELECT serviceID FROM Service LIMIT ?');
        $stmt->execute(array($limit));

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

}
?>


