<?php


class UserForgot extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("user_forgots", "user_forgot_id", $db);
    }

    public function insert(array $user, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare('INSERT INTO user_forgots (user_id, secret_key, used, created_at, created_user_id)
                                                    VALUES (:user_id, :secret_key, :used, :created_at, :created_user_id)');

            $stmt->bindParam(':user_id', $user['userId']);
            $stmt->bindParam(':secret_key', $user['secretKey']);
            $stmt->bindValue(':used', 0);

            $stmt->bindParam(':created_at', $currentDate);
            $stmt->bindParam(':created_user_id', $userId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getBySecretKey(string $secretKey)
    {
        try {
            $stmt = $this->db->prepare('SELECT user_forgot_id, user_id, secret_key, created_at FROM user_forgots WHERE secret_key = :secret_key AND used = 0 LIMIT 1');
            $stmt->bindParam(':secret_key', $secretKey);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }
}
