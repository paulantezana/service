<?php

require_once MODEL_PATH . '/AppAuthorization.php';
require_once MODEL_PATH . '/UserRole.php';

class AppAuthorizationController extends  Controller
{
    protected $connection;
    protected $appAuthorizationModel;
    protected $userRoleModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->appAuthorizationModel = new AppAuthorization($connection);
        $this->userRoleModel = new UserRole($connection);
    }

    public function home()
    {
        try {
            authorization($this->connection, 'rol', 'listar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            // $appAuthorization = $this->appAuthorizationModel->getAllByUserRoleId($body['userRoleId']);

            $appAuthorization = $this->appAuthorizationModel->getAll();
            $userRole = $this->userRoleModel->getAll();

            $this->render('admin/appAuthorization.view.php', [
                'appAuthorization' => $appAuthorization,
                'userRole' => $userRole
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function byUserRoleId()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'rol', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $res->result  = $this->appAuthorizationModel->getAllByUserRoleId($body['userRoleId']);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function save()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'rol', 'modificar');
            $postData = file_get_contents("php://input");
            $body = json_decode($postData, true);

            $authIds = $body['authIds'] ?? [];
            $userRoleId = $body['userRoleId'] ?? 0;

            $res->result  = $this->appAuthorizationModel->save($authIds, $userRoleId, $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'Los cambios se guardaron exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}
