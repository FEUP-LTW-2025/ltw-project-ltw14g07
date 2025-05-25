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

    public static function getUserByID(PDO $db, ?int $userID): ?User {
        if ($userID === null) {
            return null;
        }
        
        $stmt = $db->prepare('SELECT * FROM Users WHERE userID = ?');
        $stmt->execute([$userID]);
        
        $user = $stmt->fetch();
        return $user ? new User(
            (int)$user['UserID'],
            $user['name'],
            $user['email'],
            $user['description'] ?? null,
            $user['role'] ?? 'client',
            $user['profilePicture'] ?? null
        ) : null;
    }

    public static function login(PDO $db, string $email, string $password): ?User {
        $stmt = $db->prepare('SELECT * FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password'])) {
            return new User(
                (int)$user['UserID'],
                $user['name'],
                $user['email'],
                $user['description'] ?? null,
                $user['role'] ?? 'client',
                $user['profilePicture'] ?? null
            );
        }
        return null;
    }

    public static function register(PDO $db, string $name, string $email, string $password, string $role = 'client'): bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
    
        $stmt = $db->prepare('SELECT 1 FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false;
        }
    
        $stmt = $db->prepare('INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)');
        return $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            'client' // Force client role
        ]);
    }

    public static function promoteToAdmin(PDO $db, int $userID): bool {
        $stmt = $db->prepare('UPDATE Users SET role = :role WHERE UserID = :userID');
        return $stmt->execute([
            ':role' => 'admin',
            ':userID' => $userID
        ]);
    }
    
    

    public static function getAllAdmins(PDO $db): array {
        $stmt = $db->prepare("SELECT * FROM Users WHERE role = 'admin'");
        $stmt->execute();
        
        $admins = [];
        while ($user = $stmt->fetch()) {
            $admins[] = new User(
                (int)$user['UserID'],
                $user['name'],
                $user['email'],
                $user['description'] ?? null,
                $user['role'],
                $user['profilePicture'] ?? null
            );
        }
        return $admins;
    }
    public static function logSystemAction(PDO $db, ?int $userId, string $action, string $details = "") {
        $stmt = $db->prepare("
            INSERT INTO SystemLogs (UserID, Action, Details, Timestamp)
            VALUES (:userId, :action, :details, datetime('now'))
        ");
        $stmt->execute([
            ':userId' => $userId,
            ':action' => $action,
            ':details' => $details
        ]);
    }

    public static function getRecentLogs(PDO $db, int $limit = 10): array {
        $stmt = $db->prepare("SELECT l.*, u.name as user_name 
                            FROM SystemLogs l
                            JOIN Users u ON l.UserID = u.UserID
                            ORDER BY Timestamp DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public static function getTotalUsers(PDO $db): int {
        return $db->query("SELECT COUNT(*) FROM Users")->fetchColumn();
    }

    public static function demoteUser(PDO $db, int $userID): bool {
        $stmt = $db->prepare("UPDATE Users SET role = 'freelancer' WHERE UserID = ?");
        return $stmt->execute([$userID]);
    }

    public static function getUserByEmail(PDO $db, string $email): ?User {
        $stmt = $db->prepare('SELECT * FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            return new User(
                (int)$user['UserID'],
                $user['name'],
                $user['email'],
                $user['description'] ?? null,
                $user['role'],
                $user['profilePicture'] ?? null
            );
        }
        return null;
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public static function enforceAdminPasswordPolicy(string $password): bool {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', $password);
    }

    public static function changeAdminPassword(PDO $db, int $userID, string $currentPassword, string $newPassword): bool {
        $user = self::getUserByID($db, $userID);
        if (!$user || !password_verify($currentPassword, $user->password)) {
            return false;
        }
        
        if (!self::enforceAdminPasswordPolicy($newPassword)) {
            return false;
        }
        
        $stmt = $db->prepare("UPDATE Users SET password = ? WHERE UserID = ?");
        return $stmt->execute([
            password_hash($newPassword, PASSWORD_DEFAULT),
            $userID
        ]);
    }

    public static function getCurrentUser(PDO $db): ?User {
        if (!isset($_SESSION['userID'])) return null;
        return self::getUserByID($db, $_SESSION['userID']);
    }

    public static function requireLogin(PDO $db): User {
        session_start();
        $user = self::getCurrentUser($db);
        if (!$user) {
            header("Location: /pages/signup.php?q=l");
            exit();
        }
        return $user;
    }
    public static function verifyPassword(PDO $db, int $userID, string $password): bool {
        $stmt = $db->prepare("SELECT password FROM Users WHERE UserID = ?");
        $stmt->execute([$userID]);
        $user = $stmt->fetch();
        
        return $user && password_verify($password, $user['password']);
    }

    public static function loginNewUser(PDO $db, string $email, string $password): ?User {
        $stmt = $db->prepare('SELECT * FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return new User(
                (int)$user['UserID'],
                $user['name'],
                $user['email'],
                $user['description'] ?? null,
                $user['role'],
                $user['profilePicture'] ?? null
            );
        }
        return null;
    }
}

/* 
CREATE TABLE Users (
  UserID INTEGER NOT NULL PRIMARY KEY,
  name TEXT NOT NULL,
  profilePicture TEXT,
  email TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  description TEXT,
  role TEXT CHECK (role IN ('client', 'freelancer', 'admin'))
);
*/
?>


