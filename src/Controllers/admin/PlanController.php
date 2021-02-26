<?php

require_once MODEL_PATH . '/Plan.php';

class PlanController extends Controller
{
    protected $connection;
    protected $planModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->planModel = new Plan($connection);
    }

    public function home()
    {
        try {
            $this->render('admin/plan.view.php', [], 'layouts/admin.layout.php');
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

            $plan = $this->planModel->paginate($page, $limit, $search);

            $res->view = $this->render('admin/partials/planTable.php', [
                'plan' => $plan,
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

            $res->result = $this->planModel->getById($body['planId']);
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

            $res->result = $this->planModel->insert([
                'description'=> htmlspecialchars($body['description']),
                'speed'=> htmlspecialchars($body['speed']),
                'price'=> htmlspecialchars($body['price']),
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
            $this->planModel->updateById($body['planId'], [
                'description'=> htmlspecialchars($body['description']),
                'speed'=> htmlspecialchars($body['speed']),
                'price'=> htmlspecialchars($body['price']),

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

    public function delete()
    {
        $res = new Result();
        try {
            // authorization($this->connection, 'cliente', 'eliminar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $currentDate = date('Y-m-d H:i:s');
            $this->planModel->updateById($body['planId'], [
                'state'=> 0,

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
            if (($body['description'] ?? '') == '') {
                $res->message .= 'Falta ingresar la descripción | ';
                $res->success = false;
            }

            if (($body['speed'] ?? '') == '') {
                $res->message .= 'Falta ingresar el plan | ';
                $res->success = false;
            }

            if (($body['price'] ?? '') == '') {
                $res->message .= 'Falta ingresar el precio | ';
                $res->success = false;
            }
        }

        if ($type == 'update') {
            if (($body['planId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el id del plan | ';
                $res->success = false;
            }
        }

        $res->message = trim(trim($res->message),'|');

        return $res;
    }
}
