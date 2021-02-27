<?php

require_once MODEL_PATH . '/Contract.php';
require_once MODEL_PATH . '/Payment.php';
require_once MODEL_PATH . '/Company.php';

class ReportController extends Controller
{
    private $connection;
    private $contractModel;
    private $paymentModel;
    private $companyModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->contractModel = new Contract($connection);
        $this->paymentModel = new Payment($connection);
        $this->companyModel = new Company($connection);
    }

    public function contractChart()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'home', 'home');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $res->result = $this->contractModel->reportChart($body);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function paymentChart()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'home', 'home');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $res->result = $this->paymentModel->reportChart($body);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function paymentPrint()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'home', 'home');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $payment = $this->paymentModel->getByIdPrint($body['paymentId']);
            $company = $this->companyModel->getById(1);

            $res->result = [
                'payment' => $payment,
                'company' => $company,
                'currentDate' => date('Y-m-d H:i:s'),
            ];
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}