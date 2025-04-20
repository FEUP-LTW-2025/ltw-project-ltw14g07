<?php
    class User {
        public int $userID;
        public string $name;
        public string $email;
        public string $role;
        public string $profilePicture;

    public function __construct(int $userID, string $name, string $email, string $role, string $profilePicture) {
      $this->userID = $userID;
      $this->name = $name;
      $this->email = $email;
      $this->role = $role;
      $this->profilePicture = $profilePicture;
    }

    

    }

?>


