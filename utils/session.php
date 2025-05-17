<?php

  class Session {
    private array $messages;

    public function __construct() {
      session_start();

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function getUserID() : ?int {
      return isset($_SESSION['userID']) ? $_SESSION['userID'] : null;    
    }

    public function getUsername() : ?string {
      return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    public function getCsrf() : ?string {
      return isset($_SESSION['csrf']) ? $_SESSION['csrf'] : null;
    }

    public function setUserID(int $id) {
      $_SESSION['userID'] = $id;
    }

    public function setUsername(string $username) {
      $_SESSION['username'] = $username;
    }

    public function setCsrf(int $csrf) {
      $_SESSION['csrf'] = $csrf;
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }

  }


?>