<?php

require_once __DIR__ . "/../db.php";

class UserModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function createUser($name, $email, $phone, $password, $role)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $is_verified = ($role === "seeker") ? 1 : 0;

        $this->conn->begin_transaction();

        $sql = "INSERT INTO users 
                (name, email, password_hash, phone, role, is_active, is_verified)
                VALUES (?, ?, ?, ?, ?, 1, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            $this->conn->rollback();
            return false;
        }

        $stmt->bind_param("sssssi", $name, $email, $password_hash, $phone, $role, $is_verified);

        if (!$stmt->execute()) {
            $this->conn->rollback();
            return false;
        }

        $userId = $stmt->insert_id;
        $profileSql = null;

        if ($role === "seeker") {
            $profileSql = "INSERT INTO seeker_profiles (user_id) VALUES (?)";
        } elseif ($role === "employer") {
            $profileSql = "INSERT INTO employer_profiles (user_id) VALUES (?)";
        } elseif ($role === "recruiter") {
            $profileSql = "INSERT INTO recruiter_profiles (user_id) VALUES (?)";
        }

        if ($profileSql !== null) {
            $profileStmt = $this->conn->prepare($profileSql);
            if (!$profileStmt) {
                $this->conn->rollback();
                return false;
            }

            $profileStmt->bind_param("i", $userId);
            if (!$profileStmt->execute()) {
                $this->conn->rollback();
                return false;
            }
        }

        $this->conn->commit();
        return true;
    }

    public function findUserByEmail($email)
    {
        $sql = "SELECT id, name, email, password_hash, phone, role, is_active, is_verified
                FROM users
                WHERE email = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createAdmin($name, $email, $phone, $password)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role = "admin";

        $sql = "INSERT INTO users
                (name, email, password_hash, phone, role, is_active, is_verified)
                VALUES (?, ?, ?, ?, ?, 1, 1)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password_hash, $phone, $role);
        return $stmt->execute();
    }
}
