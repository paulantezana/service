<?php

class Customer extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('customers', 'customer_id', $connection);
    }

    public function count()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM customers WHERE state = 1");
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

    public function searchBySocialReason(string $search)
    {
        try {
            $stmt = $this->db->prepare("SELECT customer_id, social_reason FROM customers WHERE social_reason LIKE :social_reason AND state = 1 LIMIT 10");
            $stmt->bindValue(":social_reason", '%' . $search . '%');
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function paginate(int $page, int $limit = 10, string $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM customers WHERE social_reason LIKE '%{$search}%' AND state = 1")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT cus.*, tdt.description as identity_document_description FROM customers as cus
                                        INNER JOIN identity_document_types tdt on cus.identity_document_code = tdt.code
                                        WHERE cus.social_reason LIKE :search AND cus.state = 1 LIMIT $offset, $limit");
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

    public function insert(array $customer, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO customers (document_number, identity_document_code, social_reason, commercial_reason, fiscal_address, email, telephone, created_at, created_user_id)
                                                    VALUES (:document_number, :identity_document_code, :social_reason, :commercial_reason, :fiscal_address, :email, :telephone, :created_at, :created_user_id)');

            $stmt->bindValue(':document_number', $customer['documentNumber']);
            $stmt->bindValue(':identity_document_code', $customer['identityDocumentCode']);
            $stmt->bindValue(':social_reason', $customer['socialReason']);
            $stmt->bindValue(':commercial_reason', $customer['commercialReason']);
            $stmt->bindParam(':fiscal_address', $customer['fiscalAddress']);
            $stmt->bindParam(':email', $customer['email']);
            $stmt->bindParam(':telephone', $customer['telephone']);

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
