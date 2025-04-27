<?php
    class User {
        public int $userID;
        public string $name;
        public string $email;
        public ?string $description;
        public string $role;
        public ?string $profilePicture;

    public function __construct(int $userID, string $name, string $email, ?string $description, string $role, ?string $profilePicture) {
      $this->userID = $userID;
      $this->name = $name;
      $this->email = $email;
      $this->description = $description;
      $this->role = $role;
      $this->profilePicture = $profilePicture;
    }

    public static function getUserByID($db, int $userID) {
      $stmt = $db->prepare('SELECT * FROM Users WHERE userID = ? ');
      $stmt->execute(array($userID));

      $user = $stmt->fetch();
      return new User(
        $user['UserID'],
        $user['name'],
        $user['email'],
        $user['description'],
        $user['role'],
        $user['profilePicture']
      );
    }

    

    }


//    CREATE TABLE Users (
//      UserID INTEGER NOT NULL PRIMARY KEY,
//      name TEXT NOT NULL,
//      profilePicture TEXT,    --filepath, not sure how to actually store it
//      email TEXT NOT NULL UNIQUE,
//      password TEXT NOT NULL,   --should be hashed or something like that
//      description TEXT,
//      role TEXT CHECK (role IN ('client', 'freelancer', 'admin'))
//    );
//    

?>


