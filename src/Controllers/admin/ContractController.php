<?php

require_once MODEL_PATH . '/Contract.php';
require_once MODEL_PATH . '/Plan.php';
require_once MODEL_PATH . '/Server.php';
require_once MODEL_PATH . '/IdentityDocumentType.php';

class ContractController extends Controller
{
    protected $connection;
    protected $contractModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->contractModel = new Contract($connection);
    }

    public function home()
    {
        try {
            $planModel = new Plan($this->connection);
            $plan = $planModel->getAll();

            $serverModel = new Server($this->connection);
            $server = $serverModel->getAll();

            $identityDocumentTypeModel = new IdentityDocumentType($this->connection);
            $identityDocumentType = $identityDocumentTypeModel->getAll();

            $this->render('admin/contract.view.php', [
                'server' => $server,
                'plan' => $plan,
                'identityDocumentType' => $identityDocumentType,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function table()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'contract', 'listar');
            $page = htmlspecialchars(isset($_GET['page']) ? $_GET['page'] : 1);
            $limit = htmlspecialchars(isset($_GET['limit']) ? $_GET['limit'] : 20);
            $search = htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '');

            $contract = $this->contractModel->paginate($page, $limit, $search);

            $res->view = $this->render('admin/partials/contractTable.php', [
                'contract' => $contract,
            ], '', true);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    // public function id()
    // {
    //     $res = new Result();
    //     try {
    //         // authorization($this->connection, 'contract', 'modificar');
    //         $postData = file_get_contents('php://input');
    //         $body = json_decode($postData, true);

    //         $res->result = $this->contractModel->getById($body['contractId']);
    //         $res->success = true;
    //     } catch (Exception $e) {
    //         $res->message = $e->getMessage();
    //     }
    //     echo json_encode($res);
    // }

    public function searchByCustomer()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $contract = $this->contractModel->searchByCustomer($body['search']);

            $res->view = $this->render('admin/partials/searchByCustomer.php', [
                'contract' => $contract,
            ], '', true);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function create()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'contract', 'crear');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $res->result = $this->contractModel->insert([
                'datetimeOfIssue'=> htmlspecialchars($body['datetimeOfIssue']),
                'datetimeOfDue'=> htmlspecialchars($body['datetimeOfDue']),
                'datetimeOfDueEnable'=> htmlspecialchars($body['datetimeOfDueEnable']),
                'observation'=> htmlspecialchars($body['observation']),
                'customerId'=> htmlspecialchars($body['customerId']),
                'planId'=> htmlspecialchars($body['planId']),
                'serverId'=> htmlspecialchars($body['serverId']),
            ], $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function canceled()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'contract', 'eliminar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $currentDate = date('Y-m-d H:i:s');
            $this->contractModel->updateById($body['contractId'], [
                'canceled'=> 1,

                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],
            ]);
            
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
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
            if (($body['customerId'] ?? '') == '') {
                $res->message .= 'Falta especificar el cliente | ';
                $res->success = false;
            }

            if (($body['planId'] ?? '') == '') {
                $res->message .= 'Falta especificar el plan | ';
                $res->success = false;
            }

            if (($body['serverId'] ?? '') == '') {
                $res->message .= 'Falta especificar el servidor | ';
                $res->success = false;
            }

            if (($body['datetimeOfIssue'] ?? '') == '') {
                $res->message .= 'Falta espeficiar la fecha de contrato | ';
                $res->success = false;
            }
        }

        if ($type == 'update') {
            if (($body['contractId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el id del contract | ';
                $res->success = false;
            }
        }

        $res->message = trim(trim($res->message),'|');

        return $res;
    }
}
