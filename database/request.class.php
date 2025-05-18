<?php
    class Request {
        public ?int $requestID;
        public int $serviceID;
        public int $userID;
        public string $title;
        public string $description;
        public ?string $creationDate;
        public ?string $completionDate;
        public ?string $status = 'pending';

    public function __construct(?int $requestID, int $serviceID, int $userID, string $title, string $description, ?string $creationDate, ?string $completionDate, ?string $status) {
        $this->requestID = $requestID;
        $this->serviceID = $serviceID;
        $this->userID = $userID;
        $this->title = $title;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->completionDate = $completionDate;
        $this->status = $status;
    }


    public static function getRequestByID($db, $requestID) {
        $stmt = $db->prepare('SELECT * FROM Request where requestID = ?');
        $stmt->execute(array($requestID));

        $request = $stmt->fetch();

        if ($request == NULL) return NULL;

        return new Request(
            $request['requestID'],
            $request['serviceID'],
            $request['userID'],
            $request['title'],
            $request['description'],
            $request['creationDate'],
            $request['completionDate'],
            $request['status']
        );
    }



     public static function getRequestByUserID($db, $userID, $status = null) {
        $stmt = $db->prepare('SELECT * FROM Request where userID = ? AND status = ?');
        $stmt->execute(array($userID, $status));

        $services = array();
        while($row = $stmt->fetch()) {
            $services[] = new Request(
                $row['requestID'],
                $row['serviceID'],
                $row['userID'],
                $row['title'],
                $row['description'],
                $row['creationDate'],
                $row['completionDate'],
                $row['status']
            );
        }
        return $services;
     }

     public static function getRequestByServiceID($db, $serviceID) {
        $stmt = $db->prepare('SELECT * FROM Request where serviceID = ?');
        $stmt->execute(array($serviceID));

        $services = array();
        while($row = $stmt->fetch()) {
            $services[] = new Request(
                $row['requestID'],
                $row['serviceID'],
                $row['userID'],
                $row['title'],
                $row['description'],
                $row['creationDate'],
                $row['completionDate'],
                $row['status']
            );
        }
        return $services;
     }

     public static function getCreatorByID($db, $requestID) {
        $stmt = $db->prepare('SELECT userID FROM Request WHERE requestID = ? ');
        $stmt->execute(array($requestID));
        $row = $stmt->fetch();
        return $row['userID'];
    }


    public function save($db) {
        if (!empty($this->requestID)) $this->updateDatabase($db);
        else $this->insertIntoDatabase($db);
        return $this->requestID;
    }

    
    public function updateDatabase($db) {
        $stmt = $db->prepare('UPDATE Request 
                            SET title = ?, description = ?, status = ?
                            WHERE requestID = ?');

        $stmt->execute(array($this->title, $this->description, $this->status, $this->requestID));
    }


    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Request (requestID, serviceID, userID, title, description, completionDate, status) VALUES 
        (?, ?, ?, ?, ?, ?, ?)');

        $stmt->execute(array(null, $this->serviceID, $this->userID, $this->title, $this->description, null, $this->status));
        $this->requestID = intval($db->lastInsertId());
    }

    public static function delete($db, $requestID) {
        $stmt = $db->prepare('DELETE FROM Request WHERE requestID = ?');
        $stmt->execute(array($requestID));
    }
}



//CREATE TABLE Request (
//	requestID INTEGER NOT NULL PRIMARY KEY,
//	serviceID INTEGER NOT NULL,   --service to request
//	userID INTEGER NOT NULL,      --user who requested
//	title TEXT NOT NULL,
//	notes TEXT,
//	creationDate DATE NOT NULL,
//	completionDate DATE,
//	status TEXT NOT NULL CHECK(status IN ('pending', 'accepted', 'denied', 'done')),      --dar update para carrinho  
//	review INTEGER CHECK (review BETWEEN 1 AND 5) DEFAULT NULL,
//
//	FOREIGN KEY (serviceID) REFERENCES Service(serviceID)
//		ON DELETE CASCADE
//		ON UPDATE CASCADE,
//	FOREIGN KEY (userID) REFERENCES Users(userID)
//		ON DELETE CASCADE
//		ON UPDATE CASCADE
//);

?>