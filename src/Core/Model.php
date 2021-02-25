<?php


class Model
{
    protected $table;
    protected $tableID;
    protected $db;

    public function __construct(string $table, string $tableID, PDO $db)
    {
        $this->table = $table;
        $this->tableID = $tableID;
        $this->db = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM ' . $this->table);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function paginate(int $page, int $limit = 20)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query('SELECT COUNT(*) FROM ' . $this->table)->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT $offset, $limit");

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

    public function getById(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $this->tableID = :$this->tableID LIMIT 1");
            $stmt->bindParam(":$this->tableID", $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getBy(string $columnName, $value)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $columnName = :$columnName LIMIT 1");
            $stmt->bindParam(":$columnName", $value);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function deleteById(int $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->tableID} = :{$this->tableID}");
            $stmt->bindParam(":{$this->tableID}", $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $id;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function deleteBy(string $columnName, $value)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE $columnName = :$columnName");
            $stmt->bindParam(":{$columnName}", $value);
            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $value;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function updateById(int $id, array $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE {$this->tableID} = :{$this->tableID}";

            $stmt = $this->db->prepare($sql);

            foreach ($data as $key => $value) {
                if (gettype($value) === 'boolean') {
                    $stmt->bindValue(":{$key}", $value, PDO::PARAM_BOOL);
                } else {
                    $stmt->bindValue(":{$key}", $value);
                }
            }
            $stmt->bindParam(":{$this->tableID}", $id);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $id;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function updateBy(string $columnName, $value, array $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE $columnName = :$columnName";

            $stmt = $this->db->prepare($sql);

            foreach ($data as $key => $rowValue) {
                if (gettype($rowValue) === 'boolean') {
                    $stmt->bindValue(":{$key}", $rowValue, PDO::PARAM_BOOL);
                } else {
                    $stmt->bindValue(":{$key}", $rowValue);
                }
            }
            $stmt->bindParam(":{$columnName}", $value);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $value;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function searchBy(string $columnName, string $search)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE $columnName LIKE :$columnName  LIMIT 8");
            $stmt->bindValue(":{$columnName}", '%' . $search . '%');

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }
}
