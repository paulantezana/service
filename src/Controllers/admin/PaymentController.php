<?php

require_once MODEL_PATH . '/Payment.php';
require_once MODEL_PATH . '/Contract.php';

class PaymentController extends Controller
{
    protected $connection;
    protected $paymentModel;
    protected $contractModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->paymentModel = new Payment($connection);
        $this->contractModel = new Contract($connection);
    }

    public function home()
    {
        try {
            $this->render('admin/payment.view.php', [
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function report()
    {
        try {
            $contractId = $_GET['contractId'] ?? 0;
            $contract = [];
            if($contractId > 0){
                $contract = $this->contractModel->getByIdDetail($contractId);
            }

            $this->render('admin/paymentReport.view.php', [
                'contractId' => $contractId,
                'contract' => $contract,
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
            // authorization($this->connection, 'cliente', 'listar');
            $page = htmlspecialchars(isset($_GET['page']) ? $_GET['page'] : 1);
            $limit = htmlspecialchars(isset($_GET['limit']) ? $_GET['limit'] : 20);
            $search = htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '');
            $contractId = htmlspecialchars(isset($_GET['contractId']) ? $_GET['contractId'] : 0);
            
            $payment = $this->paymentModel->paginate($page, $limit, $search, $contractId);

            $res->view = $this->render('admin/partials/paymentTable.php', [
                'payment' => $payment,
            ], '', true);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function id()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $res->result = $this->paymentModel->getById($body['paymentId']);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function lastPaymentByContractId()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $lastPayment = $this->paymentModel->lastPaymentByContractId($body['contractId']);
            $contract = $this->contractModel->getByIdDetail($body['contractId']);

            $res->result = [
                'lastPayment'=>$lastPayment,
                'contract'=>$contract,
            ];
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
            // authorization($this->connection, 'cliente', 'crear');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $res->result = $this->paymentModel->insert([
                'description'=> htmlspecialchars($body['description']),
                'reference'=> htmlspecialchars($body['reference']),
                'paymentCount'=> htmlspecialchars($body['paymentCount']),
                'fromDatetime'=> htmlspecialchars($body['fromDatetime']),
                'toDatetime'=> htmlspecialchars($body['toDatetime']),
                'total'=> htmlspecialchars($body['total']),
                'contractId'=> htmlspecialchars($body['contractId']),
            ], $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function update()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'update');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->paymentModel->updateById($body['paymentId'], [
                'description'=> htmlspecialchars($body['speed'] . ' ' . $body['plan']),
                'reference'=> htmlspecialchars($body['reference']),
                'payment_count'=> htmlspecialchars($body['paymentCount']),
                'fromDatetime'=> htmlspecialchars($body['fromDatetime']),
                'toDatetime'=> htmlspecialchars($body['toDatetime']),
                'total'=> htmlspecialchars($body['total']),
                'contractId'=> htmlspecialchars($body['contractId']),

                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],
            ]);

            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function canceled()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'eliminar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $currentDate = date('Y-m-d H:i:s');
            $this->paymentModel->updateById($body['paymentId'], [
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
            if (($body['reference'] ?? '') == '') {
                $res->message .= 'Falta ingresar la referencia | ';
                $res->success = false;
            }

            if (($body['paymentCount'] ?? '') == '') {
                $res->message .= 'Falta ingresar el número de meses a pagar | ';
                $res->success = false;
            }

            if (($body['fromDatetime'] ?? '') == '') {
                $res->message .= 'Falta ingresar la fecha de inicio | ';
                $res->success = false;
            }
            if (($body['toDatetime'] ?? '') == '') {
                $res->message .= 'Falta ingresar la fecha de fin | ';
                $res->success = false;
            }
            if (($body['total'] ?? '') == '') {
                $res->message .= 'Falta ingresar el total | ';
                $res->success = false;
            }
            if (($body['contractId'] ?? '') == '') {
                $res->message .= 'Falta especificar el contrato | ';
                $res->success = false;
            }
            if (($body['description'] ?? '') == '') {
                $res->message .= 'Falta ingresar la descripción | ';
                $res->success = false;
            }
        }

        if ($type == 'update') {
            if (($body['paymentId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el id del payment | ';
                $res->success = false;
            }
        }

        $res->message = trim(trim($res->message),'|');

        return $res;
    }
}
