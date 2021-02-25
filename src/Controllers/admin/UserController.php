<?php

require_once MODEL_PATH . '/User.php';
require_once MODEL_PATH . '/UserRole.php';

class UserController extends Controller
{
    protected $connection;
    protected $userModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function home()
    {
        try {
            authorization($this->connection, 'usuario', 'listar');
            $userRoleModel = new UserRole($this->connection);
            $userRole = $userRoleModel->getAll();

            $this->render('admin/user.view.php', [
                'userRole' => $userRole,
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
            authorization($this->connection, 'usuario', 'listar');
            $page = htmlspecialchars(isset($_GET['page']) ? $_GET['page'] : 1);
            $limit = htmlspecialchars(isset($_GET['limit']) ? $_GET['limit'] : 10);
            $search = htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '');

            $user = $this->userModel->paginate($page, $limit, $search);

            $res->view = $this->render('admin/partials/userTable.php', [
                'user' => $user,
            ], '', true);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function profile()
    {
        try {
            $user = $this->userModel->getById((int) $_SESSION[SESS_KEY]);
            $this->render('profile.view.php', [
                'user' => $user,
            ], 'layouts/admin.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/admin.layout.php');
        }
    }

    public function id()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $res->result = $this->userModel->getById($body['userId']);
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
            authorization($this->connection, 'usuario', 'crear');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body);
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $res->result = $this->userModel->insert([
                'userName' => htmlspecialchars($body['userName']),
                'email' => htmlspecialchars($body['email']),
                'password' => sha1(htmlspecialchars($body['password'])),
                'fullName' => htmlspecialchars($body['fullName']),
                'userRoleId' => htmlspecialchars($body['userRoleId']),
            ], $_SESSION[SESS_KEY]);
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        } catch (Exception $e) {
            $res->message = $e->getMessage();
        }
        echo json_encode($res);
    }

    public function updateProfile()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'updateProfile');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->updateById($body['userId'], [
                'email' => htmlspecialchars($body['email']),
                'user_name' => htmlspecialchars($body['userName']),
                'full_name' => htmlspecialchars($body['fullName']),

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

    public function update()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'update');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->updateById($body['userId'], [
                'email' => htmlspecialchars($body['email']),
                'user_name' => htmlspecialchars($body['userName']),
                'full_name' => htmlspecialchars($body['fullName']),
                'state' => htmlspecialchars($body['state']),
                'user_role_id' => htmlspecialchars($body['userRoleId']),

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

    public function updatePassword()
    {
        $res = new Result();
        try {
            authorization($this->connection, 'usuario', 'modificar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $validate = $this->validateInput($body, 'updatePassword');
            if (!$validate->success) {
                throw new Exception($validate->message);
            }

            $currentDate = date('Y-m-d H:i:s');
            $this->userModel->updateById($body['userId'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $_SESSION[SESS_KEY],

                'password' => sha1(htmlspecialchars($body['password'])),
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
            authorization($this->connection, 'usuario', 'eliminar');
            $postData = file_get_contents('php://input');
            $body = json_decode($postData, true);

            $this->userModel->deleteById(htmlspecialchars($body['userId']));
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

        if ($type == 'create' || $type == 'update' || $type == 'updateProfile') {
            if (($body['email'] ?? '') == '') {
                $res->message .= 'Falta ingresar el correo electrónico | ';
                $res->success = false;
            }

            if (($body['userName'] ?? '') == '') {
                $res->message .= 'Falta ingresar el nombre de usuario | ';
                $res->success = false;
            }

            if ($type != 'updateProfile') {
                if (($body['userRoleId'] ?? '') == '') {
                    $res->message .= 'Falta elegir un rol | ';
                    $res->success = false;
                }
            }
        }

        if ($type == 'update') {
            if (($body['userId'] ?? '') == '') {
                $res->message .= 'Falta ingresar el userId | ';
                $res->success = false;
            }
        }

        if ($type == 'create' || $type == 'updatePassword') {
            if (($body['password'] ?? '') == '') {
                $res->message .= 'Falta ingresar la contraseña | ';
                $res->success = false;
            }
            if (($body['passwordConfirm'] ?? '') == '') {
                $res->message .= 'Falta ingresar la confirmación contraseña | ';
                $res->success = false;
            }
            if ($body['password'] != $body['passwordConfirm']) {
                $res->message .= 'Las contraseñas no coinciden | ';
                $res->success = false;
            }
        }

        return $res;
    }
}
