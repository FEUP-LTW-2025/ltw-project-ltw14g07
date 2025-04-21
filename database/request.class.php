<?php
    class Request {
        public ?int $requestID;
        public int $serviceID;
        public int $userID;
        public string $title;
        public string $notes;
        public string $creationDate;
        public ?string $completionDate;
        public ?string $status = 'pending';

    public function __construct(?int $requestID, int $serviceID, int $userID, string $title, string $notes, string $creationDate, ?string $completionDate, ?string $status) {
        $this->requestID = $requestID;
        $this->serviceID = $serviceID;
        $this->userID = $userID;
        $this->title = $title;
        $this->notes = $notes;
        $this->creationDate = $creationDate;
        $this->completionDate = $completionDate;
        $this->status = $status;
    }

    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Request (requestID, serviceID, userID, title, notes, creationDate, completionDate, status) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?)');

        $stmt->execute(array(null, $this->serviceID, $this->userID, $this->title, $this->notes, $this->creationDate, $this->completionDate, $this->status));

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