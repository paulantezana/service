<?php

require_once MODEL_PATH . '/Company.php';

class CompanyController extends Controller
{
    protected $connection;
    protected $companyModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->companyModel = new Company($connection);
    }

    public function home()
    {
        try {
            $company = $this->companyModel->getById(1);
            $this->render('admin/company.view.php', [
                'company' => $company,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }
    public function backoup()
    {
        try {
            $company = $this->companyModel->getById(1);
            $this->render('admin/backoup.view.php', [
                'company' => $company,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
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
            $this->companyModel->updateById($body['companyId'], [
                'document_number'=> htmlspecialchars($body['documentNumber']),
                'social_reason'=> htmlspecialchars($body['socialReason']),
                'commercial_reason'=> htmlspecialchars($body['commercialReason']),
                'fiscal_address'=> htmlspecialchars($body['fiscalAddress']),
                'email'=> htmlspecialchars($body['email']),
                'phone'=> htmlspecialchars($body['phone']),
                'representative'=> htmlspecialchars($body['representative']),

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

    public function uploadLogoSquare(){
        $res = new Result();
        try {
            $companyId = $_POST['companyId'];
            
            if(isset($_FILES['logo'])){
                $posterPath = uploadAndValidateFile($_FILES['logo'], '/upload/company/', 'logo_square' . $companyId, 102400, ['jpeg','jpg','png']);

                $currentDate = date('Y-m-d H:i:s');
                $this->companyModel->updateById($companyId, [
                    'logo'=> $posterPath,
    
                    'updated_at' => $currentDate,
                    'updated_user_id' => $_SESSION[SESS_KEY],
                ]);
            } else {
                throw new Exception('No se especificó ningun archivo');
            }

            $res->success = true;
            $res->message = 'El registro se actualizo exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function uploadLogoLarge(){
        $res = new Result();
        try {
            $companyId = $_POST['companyId'];

            if(isset($_FILES['logo'])){
                $posterPath = uploadAndValidateFile($_FILES['logo'], '/upload/company/', 'logo_large' . $companyId, 102400, ['jpeg','jpg','png']);

                $currentDate = date('Y-m-d H:i:s');
                $this->companyModel->updateById($companyId, [
                    'logo_large'=> $posterPath,
    
                    'updated_at' => $currentDate,
                    'updated_user_id' => $_SESSION[SESS_KEY],
                ]);
            } else {
                throw new Exception('No se especificó ningun archivo');
            }

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
            if (($body['documentNumber'] ?? '') == '') {
                $res->message .= 'Falta ingresar el número de documento | ';
                $res->success = false;
            }

            if (($body['socialReason'] ?? '') == '') {
                $res->message .= 'Falta ingresar la rason social | ';
                $res->success = false;
            }
        }

        if ($type == 'update') {
            if (($body['companyId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el id del company | ';
                $res->success = false;
            }
        }

        $res->message = trim(trim($res->message),'|');

        return $res;
    }
}
