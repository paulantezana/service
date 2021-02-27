<?php

class User extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('users', 'user_id', $connection);
    }

    public function getByIdShort(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT user_id, user_role_id, user_name, full_name, email, avatar, full_name  FROM users WHERE user_id = :user_id LIMIT 1");
            $stmt->bindParam(":user_id", $id);
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
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE user_id > 1 AND state = 1");
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

    public function paginate(int $page, int $limit = 10, string $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM users WHERE user_id > 1 AND user_name LIKE '%{$search}%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $stmt = $this->db->prepare("SELECT users.*, ur.description as user_roles,
                                        ur.state as user_role_state
                                        FROM users
                                        INNER JOIN user_roles ur on users.user_role_id = ur.user_role_id
                                        WHERE users.user_id > 1 AND users.user_name LIKE :search LIMIT $offset, $limit");
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

    public function login(string $user, string $password)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users 
                                        INNER JOIN user_roles AS rol ON users.user_role_id = rol.user_role_id AND rol.state = 1
                                        WHERE users.email = :email AND users.password = :password AND users.state = 1 LIMIT 1');
            $stmt->bindParam(':email', $user);
            $stmt->bindValue(':password', sha1(trim($password)));

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }
            $dataUser = $stmt->fetch();

            if ($dataUser == false) {
                $stmt = $this->db->prepare('SELECT * FROM users 
                                            INNER JOIN user_roles AS rol ON users.user_role_id = rol.user_role_id AND rol.state = 1
                                            WHERE users.user_name = :user_name AND users.password = :password AND users.state = 1 LIMIT 1');
                $stmt->bindParam(':user_name', $user);
                $stmt->bindValue(':password', sha1(trim($password)));

                if (!$stmt->execute()) {
                    throw new Exception($stmt->errorInfo()[2]);
                }
                $dataUser = $stmt->fetch();

                if ($dataUser == false) {
                    throw new Exception('El usuario o contraseñas es icorrecta');
                }
            }

            if ($dataUser['state'] == '0') {
                throw new Exception('Usted no esta autorizado para ingresar al sistema.');
            }

            return $dataUser;
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function insert(array $user, int $userId)
    {
        try {
            $currentDate = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO users (user_name, email, password, full_name, user_role_id, created_at, created_user_id)
                                                    VALUES (:user_name, :email, :password, :full_name, :user_role_id, :created_at, :created_user_id)');

            $stmt->bindValue(':user_name', $user['userName']);
            $stmt->bindValue(':email', $user['email']);
            $stmt->bindValue(':password', sha1($user['password']));
            $stmt->bindValue(':full_name', $user['fullName']);
            $stmt->bindParam(':user_role_id', $user['userRoleId']);

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
