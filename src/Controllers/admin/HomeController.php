<?php

require_once(MODEL_PATH . '/User.php');
require_once(MODEL_PATH . '/Contract.php');
require_once(MODEL_PATH . '/Plan.php');
require_once(MODEL_PATH . '/Customer.php');

class HomeController extends Controller
{
    private $connection;
    private $userModel;
    private $contractModel;
    private $planModel;
    private $customerModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
        $this->contractModel = new Contract($connection);
        $this->planModel = new Plan($connection);
        $this->customerModel = new Customer($connection);
    }

    public function home()
    {
        try {
            $userCount = $this->userModel->count();
            $contractCount = $this->contractModel->count();
            $planCount = $this->planModel->count();
            $customerCount = $this->customerModel->count();

            $this->render('admin/dashboard.view.php', [
                'customerCount' => $customerCount,
                'userCount' => $userCount,
                'contractCount' => $contractCount,
                'planCount' => $planCount,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function getGlobalInfo()
    {
        $res = new Result();
        try {
            
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }
}
