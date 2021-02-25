<?php

class Contract extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('contracts', 'contract_id', $connection);
    }

    public function count()
    {
        try {
            $stmt = $this->db->prepare("SELECT 
                    SUM(CASE WHEN canceled = '1' THEN 1 ELSE 0 END) AS total_canceled,
                    SUM(CASE WHEN canceled = '0' THEN 1 ELSE 0 END) AS total
                 FROM contracts");
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getByIdDetail(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT cont.*,
                                                plan.description AS plan_description, plan.speed AS plan_speed, plan.price AS plan_price
                                        FROM contracts AS cont
                                        INNER JOIN plans AS plan ON cont.plan_id = plan.plan_id 
                                        WHERE cont.contract_id = :contract_id LIMIT 1");
            $stmt->bindParam(":contract_id", $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function paginate(int $page, int $limit = 10, string $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM contracts")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT cont.*,
                                                cus.social_reason AS customer_social_reason,
                                                plan.description AS plan_description, plan.speed AS plan_speed, plan.price AS plan_price,
                                                SUM(IFNULL(pay.payment_count,0)) as payment_count
                                        FROM contracts AS cont
                                        INNER JOIN customers AS cus ON cont.customer_id = cus.customer_id 
                                        INNER JOIN plans AS plan ON cont.plan_id = plan.plan_id 
                                        LEFT JOIN payments AS pay ON cont.contract_id = pay.contract_id AND pay.canceled = 0
                                        WHERE cus.social_reason LIKE :search GROUP BY cont.contract_id LIMIT $offset, $limit");
            $stmt->bindValue(':search', '%' . $search . '%');

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetchAll();

            $paginate = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            return $paginate;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert(array $contract, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO contracts (datetime_of_issue, datetime_of_due, datetime_of_due_enable, observation, plan_id, customer_id, user_id, created_at, created_user_id)
                                                    VALUES (:datetime_of_issue, :datetime_of_due, :datetime_of_due_enable, :observation, :plan_id, :customer_id, :user_id, :created_at, :created_user_id)');

            $stmt->bindValue(':datetime_of_issue', $contract['datetimeOfIssue']);
            $stmt->bindValue(':datetime_of_due', $contract['datetimeOfDue']);
            $stmt->bindValue(':datetime_of_due_enable', $contract['datetimeOfDueEnable']);
            $stmt->bindValue(':observation', $contract['observation']);
            $stmt->bindParam(':plan_id', $contract['planId']);
            $stmt->bindParam(':customer_id', $contract['customerId']);
            $stmt->bindParam(':user_id', $userId);

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

    public function reportChart($filter){
        $stmt = $this->db->prepare("SELECT DATE(created_at) as created_at_query, COUNT(contract_id) as count FROM contracts
                                    WHERE created_at BETWEEN :start_date AND :end_date AND canceled = 0
                                    GROUP BY created_at_query");

        $stmt->bindParam(':start_date', $filter['startDate']);
        $stmt->bindParam(':end_date', $filter['endDate']);
    
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return $stmt->fetchAll();
    }
}
