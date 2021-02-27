<?php

require_once(MODEL_PATH . '/User.php');
require_once(MODEL_PATH . '/AppContract.php');

class ConfigController extends Controller
{
    private $connection;
    private $userModel;
    private $appContractModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->appContractModel = new AppContract($connection);
    }

    public function app()
    {
        try {
            if(!($_SESSION[SESS_USER]['user_role_id'] == 1)){
                $this->render('403.view.php', [
                    'message' => 'Permiso denegado',
                ], 'layouts/admin.layout.php');
                return;
            }

            $appContract = $this->appContractModel->getById(1);

            $this->render('admin/configApp.view.php', [
                'appContract' => $appContract,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function updateApp()
    {
        $res = new Result();
        try {
            if(!($_SESSION[SESS_USER]['user_role_id'] == 1)){
                throw new Exception('Permiso denegado');
            } 

            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'update');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->appContractModel->updateById($body['appContractId'], [
                'date_of_due'=> htmlspecialchars($body['dateOfDue']),
                'app_key'=> htmlspecialchars($body['appKey']),
                'notice_days'=> htmlspecialchars($body['noticeDays']),
            ]);

            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function validateInput($body, $type = 'create')
    {
        $res = new Result();
        $res->success = true;

        if ($type == 'create' || $type == 'update') {
            if (($body['dateOfDue'] ?? '') == '') {
                $res->message .= 'Falta ingresar fecha de vencimiento | ';
                $res->success = false;
            }

            if (($body['appKey'] ?? '') == '') {
                $res->message .= 'Falta ingresar la llave del app | ';
                $res->success = false;
            }

            if (($body['noticeDays'] ?? '') == '') {
                $res->message .= 'Falta ingresar dias de notificación | ';
                $res->success = false;
            }
        }

        if ($type == 'update') {
            if (($body['appContractId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el id de la configuración | ';
                $res->success = false;
            }
        }

        $res->message = trim(trim($res->message),'|');

        return $res;
    }
}
