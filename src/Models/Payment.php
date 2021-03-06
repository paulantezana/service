<?php

class Payment extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('payments', 'payment_id', $connection);
    }


    public function paginate(int $page, int $limit = 20, string $search = '', int $contractId = 0, $searchStartDate = 0, $searchEndDate = 0)
    {
        try {
            $offset = ($page - 1) * $limit;
            $aditionalQuery = '';
            if ($contractId > 0) {
                $aditionalQuery = ' AND pay.contract_id = :contract_id ';
            }

            $stmt = $this->db->prepare("SELECT COUNT(*) as total
                                        FROM payments AS pay
                                        INNER JOIN contracts AS con ON pay.contract_id = con.contract_id
                                        INNER JOIN customers AS cus ON con.customer_id = cus.customer_id
                                        INNER JOIN users as user ON pay.user_id = user.user_id
                                        WHERE (DATE(pay.datetime_of_issue) BETWEEN :start_date_of_issue AND :end_date_of_issue) 
                                        AND cus.social_reason LIKE '%{$search}%' 
                                        " . $aditionalQuery);
            $stmt->bindParam(":start_date_of_issue", $searchStartDate);
            $stmt->bindParam(":end_date_of_issue", $searchEndDate);
            if ($contractId > 0) {
                $stmt->bindParam(":contract_id", $contractId);
            }
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            $totalRows = $stmt->fetch();
            $totalPages = ceil($totalRows['total'] / $limit);


            $stmt = $this->db->prepare("SELECT pay.*,
                                            cus.document_number AS customer_document_number,
                                            cus.social_reason AS customer_social_reason,
                                            cus.fiscal_address AS customer_fiscal_address,
                                            cus.email AS customer_email,
                                            cus.telephone AS customer_telephone,
                                            user.user_name AS user_name
                                        FROM payments AS pay
                                        INNER JOIN contracts AS con ON pay.contract_id = con.contract_id
                                        INNER JOIN customers AS cus ON con.customer_id = cus.customer_id
                                        INNER JOIN users as user ON pay.user_id = user.user_id
                                        WHERE (DATE(pay.datetime_of_issue) BETWEEN :start_date_of_issue AND :end_date_of_issue) 
                                        AND cus.social_reason LIKE '%{$search}%' 
                                        ".$aditionalQuery." 
                                        ORDER BY pay.payment_id DESC
                                        LIMIT $offset, $limit");
            $stmt->bindParam(":start_date_of_issue", $searchStartDate);
            $stmt->bindParam(":end_date_of_issue", $searchEndDate);
            if ($contractId > 0) {
                $stmt->bindParam(":contract_id", $contractId);
            }
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetchAll();

            return [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getByIdPrint(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT pay.*,
                                            cus.document_number as customer_document_number,
                                            cus.social_reason as customer_social_reason,
                                            cus.fiscal_address as customer_fiscal_address,
                                            cus.email as customer_email,
                                            cus.telephone as customer_telephone,
                                            user.user_name as user_name
                                        FROM payments AS pay
                                        INNER JOIN contracts AS con ON pay.contract_id = con.contract_id
                                        INNER JOIN customers AS cus ON con.customer_id = cus.customer_id
                                        INNER JOIN users as user ON pay.user_id = user.user_id
                                        WHERE payment_id= :payment_id LIMIT 1");
            $stmt->bindParam(":payment_id", $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function count()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM payments WHERE state = 1");
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $data = $stmt->fetch();
            if ($data == false) {
                return 0;
            }

            return $data['total'];
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function lastPaymentByContractId($contractId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM payments WHERE contract_id = :contract_id AND canceled = 0 ORDER BY payment_id DESC LIMIT 1");
            $stmt->bindValue(':contract_id', $contractId);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert(array $payment, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO payments (datetime_of_issue, description, reference, payment_count, from_datetime, to_datetime, total, contract_id, user_id, created_at, created_user_id)
                                                    VALUES (:datetime_of_issue, :description, :reference, :payment_count, :from_datetime, :to_datetime, :total, :contract_id, :user_id, :created_at, :created_user_id)');

            $stmt->bindValue(':datetime_of_issue', $currentDate);
            $stmt->bindValue(':description', $payment['description']);
            $stmt->bindValue(':reference', $payment['reference']);
            $stmt->bindValue(':payment_count', $payment['paymentCount']);
            $stmt->bindValue(':from_datetime', $payment['fromDatetime']);
            $stmt->bindValue(':to_datetime', $payment['toDatetime']);
            $stmt->bindValue(':total', $payment['total']);
            $stmt->bindValue(':contract_id', $payment['contractId']);
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

    public function reportChart($filter)
    {
        $stmt = $this->db->prepare("SELECT DATE(created_at) as created_at_query, COUNT(payment_id) as count FROM payments
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
