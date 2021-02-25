<?php

class Plan extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('plans', 'plan_id', $connection);
    }

    public function count()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM plans WHERE state = 1");
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetch();
            if($data == false){
                return 0;
            }

            return $data['total'];
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert(array $plan, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO plans (description, speed, price, created_at, created_user_id)
                                                    VALUES (:description, :speed, :price, :created_at, :created_user_id)');

            $stmt->bindValue(':description', $plan['description']);
            $stmt->bindValue(':speed', $plan['speed']);
            $stmt->bindValue(':price', $plan['price']);

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
}
