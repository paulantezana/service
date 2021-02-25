<?php

class AppAuthorization extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("app_authorizations", "app_authorization_id", $connection);
    }

    public function getMenu(int $userRoleId)
    {
        try {
            $stmt = $this->db->prepare('SELECT app.module FROM user_role_authorizations as ur
                                        INNER JOIN app_authorizations app ON ur.app_authorization_id = app.app_authorization_id
                                        WHERE ur.user_role_id = :user_role_id
                                        GROUP BY app.module');
            $stmt->bindParam(':user_role_id', $userRoleId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function getAllByuserRoleId(int $userRoleId)
    {
        try {
            $stmt = $this->db->prepare('SELECT aa.*, IFNULL(ura.app_authorization_id, 0) as is_auth
                                        FROM app_authorizations AS aa
                                        LEFT JOIN user_role_authorizations AS ura ON aa.app_authorization_id = ura.app_authorization_id
                                        WHERE ura.user_role_id = :user_role_id');
            $stmt->bindParam(':user_role_id', $userRoleId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function save(array $authIds, int $userRoleId, int $userId)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('DELETE FROM user_role_authorizations WHERE user_role_id = :user_role_id');
            $stmt->bindParam(':user_role_id', $userRoleId);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            foreach ($authIds as $row) {
                $stmt = $this->db->prepare('INSERT INTO user_role_authorizations (user_role_id, app_authorization_id)
                                            VALUES (:user_role_id, :app_authorization_id)');
                $stmt->bindParam(':user_role_id', $userRoleId);
                $stmt->bindParam(':app_authorization_id', $row);

                if (!$stmt->execute()) {
                    throw new Exception($stmt->errorInfo()[2]);
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }

    public function isAuthorized(string $module, string $action, int $userRoleId)
    {
        try {
            $stmt = $this->db->prepare('SELECT count(*) as count FROM user_role_authorizations as ur
                                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                                        WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                                        GROUP BY app.module');
            $stmt->bindParam(':user_role_id', $userRoleId);
            $stmt->bindParam(':module', $module);
            $stmt->bindParam(':action', $action);

            if (!$stmt->execute()) {
                throw new Exception($stmt->errorInfo()[2]);
            }

            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error en metodo : ' . __FUNCTION__ . ' | ' . $e->getMessage());
        }
    }
}
