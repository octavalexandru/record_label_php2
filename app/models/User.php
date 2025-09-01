<?php
require_once __DIR__ . '/../core/Model.php';

class User extends Model {
    public function __construct() {
        parent::__construct("users", "user_id");
    }

    public function create($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([$username, $email, $hash]);
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function verifyUser($userId) {
    $stmt = $this->pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE user_id = ?");
    return $stmt->execute([$userId]);
}



    public function findByToken($token) {
    return $this->queryOne("SELECT * FROM {$this->table} WHERE verification_token = ?", [$token]);
}

}
