<?php
    class Comment {
        public ?int $commentID;
        public int $requestID;
        public int $userID;
        public string $text;
        public string $creationDate;
        public ?string $userName;  // complementary

    public function __construct(?int $commentID, int $requestID, int $userID, string $text, string $creationDate, ?string $userName) {
        $this->commentID = $commentID;
        $this->requestID = $requestID;
        $this->userID = $userID;
        $this->text = $text;
        $this->creationDate = $creationDate;
        $this->userName = $userName;
    }

    public static function getCommentsByRequestID($db, $requestID) {
        $stmt = $db->prepare('SELECT Comment.commentID, Comment.requestID, Comment.userID, Comment.text, Comment.creationDate, Users.name
                            FROM Comment JOIN Users ON Comment.userID = Users.UserID
                            WHERE Comment.requestID = ? ');

        $stmt->execute(array($requestID));

        $comments = array();
        while($row = $stmt->fetch()) {
            $comments[] = new Comment (
                commentID: $row['commentID'],
                requestID: $row['requestID'],
                userID: $row['userID'],
                text: $row['text'],
                creationDate: $row['creationDate'],
                userName: $row['name']
            );
        }
        return $comments;
    }



    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Comment (commentID, requestID, userID, text, creationDate) VALUES 
        (?, ?, ?, ?, ?)');

        $stmt->execute(array(null, $this->requestID, $this->userID, $this->text, $this->creationDate));

    }
}

//
//
//CREATE TABLE Comment (
//	commentID INTEGER PRIMARY KEY,
//	requestID INTEGER NOT NULL,   --comment on this request
//	userID INTEGER NOT NULL,      --user who commented
//	text TEXT, 
//	creationDate DATE NOT NULL DEFAULT CURRENT_DATE,
//
//	FOREIGN KEY (requestID) REFERENCES Request(requestID)
//		ON DELETE CASCADE
//		ON UPDATE CASCADE,
//	FOREIGN KEY (userID) REFERENCES Users(userID)
//		ON DELETE CASCADE
//		ON UPDATE CASCADE
//);
//

?>