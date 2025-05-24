<?php
class Service {
    public ?int $serviceID;
    public int $userID;
    public ?string $userName;
    public string $title;
    public string $description;
    public int $hourlyRate;
    public int $deliveryTime;
    public string $creationDate;
    public $languages;
    public $fields;
    public ?string $status;

    public function __construct(?int $serviceID, int $userID, ?string $userName, string $title, 
                              string $description, int $hourlyRate, int $deliveryTime, 
                              string $creationDate, $languages, $fields, ?string $status = null) {
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
        $this->status = $status;
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

    public static function getAllServicesByUserID($db, $userID) {
        $stmt = $db->prepare('SELECT serviceID FROM Service WHERE userID = ?');
        $stmt->execute(array($userID));

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
            array_column($fields, 'field'),
            $service['status'] ?? null
        );
    }
    public static function searchServices(PDO $db, array $filters = []): array {
        $query = "SELECT DISTINCT s.* FROM Service s
                  LEFT JOIN ServiceLanguage sl ON s.serviceID = sl.serviceID
                  LEFT JOIN ServiceField sf ON s.serviceID = sf.serviceID
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $query .= " AND (s.title LIKE :search OR s.description LIKE :search 
                        OR sl.language LIKE :searchLang OR sf.field LIKE :searchField)";
            $params[':search'] = '%' . $filters['search'] . '%';
            $params[':searchLang'] = '%' . $filters['search'] . '%';
            $params[':searchField'] = '%' . $filters['search'] . '%';
        }
        
        error_log("Final query: " . $query); // Debug
        error_log("Parameters: " . print_r($params, true)); // Debug
        
        $stmt = $db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        // Debug: Check what's actually being returned
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Service');
        error_log("Found " . count($results) . " services");
        if (count($results) > 0) {
            error_log("First result: " . print_r($results[0], true));
        }
        
        return $results;
    }
    
    public function getLanguages(PDO $db): array {
        $stmt = $db->prepare("
            SELECT language FROM ServiceLanguage 
            WHERE serviceID = ?
        ");
        $stmt->execute([$this->serviceID]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getFields(PDO $db): array {
        $stmt = $db->prepare("
            SELECT field FROM ServiceField 
            WHERE serviceID = ?
        ");
        $stmt->execute([$this->serviceID]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function save($db) {
        if (!empty($this->serviceID)) $this->updateDatabase($db);
        else $this->insertIntoDatabase($db);
        return $this->serviceID;
    }

    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Service (userID, title, description, hourlyRate, deliveryTime, creationDate, status) VALUES 
        (?, ?, ?, ?, ?, ?, ?)');

        $stmt->execute(array(
            $this->userID, 
            $this->title, 
            $this->description, 
            $this->hourlyRate, 
            $this->deliveryTime, 
            $this->creationDate,
            $this->status ?? 'active'
        ));

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
                            SET title = ?, description = ?, hourlyRate = ?, deliveryTime = ?, status = ?
                            WHERE serviceID = ?');

        $stmt->execute(array(
            $this->title, 
            $this->description, 
            $this->hourlyRate, 
            $this->deliveryTime,
            $this->status,
            $this->serviceID
        ));

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

    /* =================== */
    /* ADMIN-ONLY METHODS  */
    /* =================== */
    
    public static function getActiveCount(PDO $db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM Service WHERE status = 'active'");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public static function getStatusStats(PDO $db): array {
        $stmt = $db->prepare("
            SELECT status, COUNT(*) as count 
            FROM Service 
            GROUP BY status
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRecentServices(PDO $db, int $limit = 5): array {
        $stmt = $db->prepare("
            SELECT s.*, u.name as userName
            FROM Service s
            JOIN Users u ON s.userID = u.UserID
            ORDER BY s.creationDate DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $services = [];
        while ($row = $stmt->fetch()) {
            $services[] = new Service(
                $row['serviceID'],
                $row['userID'],
                $row['userName'],
                $row['title'],
                $row['description'],
                $row['hourlyRate'],
                $row['deliveryTime'],
                $row['creationDate'],
                [],
                [],
                $row['status'] ?? null
            );
        }
        return $services;
    }

    public static function getFilteredServices(PDO $db, array $filters = []): array {
        $query = "SELECT s.*, u.name as userName FROM Service s JOIN Users u ON s.userID = u.UserID";
        $conditions = [];
        $params = [];

        if (!empty($filters['status'])) {
            $conditions[] = "s.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['userID'])) {
            $conditions[] = "s.userID = :userID";
            $params[':userID'] = $filters['userID'];
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY s.creationDate DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);

        $services = [];
        while ($row = $stmt->fetch()) {
            $services[] = new Service(
                $row['serviceID'],
                $row['userID'],
                $row['userName'],
                $row['title'],
                $row['description'],
                $row['hourlyRate'],
                $row['deliveryTime'],
                $row['creationDate'],
                [],
                [],
                $row['status']
            );
        }
        return $services;
    }

    public static function updateStatus(PDO $db, int $serviceID, string $status): bool {
        $stmt = $db->prepare("
            UPDATE Service 
            SET status = :status 
            WHERE serviceID = :serviceID
        ");
        return $stmt->execute([
            ':status' => $status,
            ':serviceID' => $serviceID
        ]);
    }
}
?>