<?php

class Result extends stdClass
{
    public $success;
    public $message;
    public $result;

    function __construct()
    {
        $this->success = false;
        $this->message = '';
        $this->result = null;
    }
}

function requireToVar($file, $parameter)
{
    ob_start();
    require($file);
    return ob_get_clean();
}

function authorization(PDO $connection, string $module, string $action, string $errorMessage = '')
{
    $res = new Result();
    if (!isset($_SESSION[SESS_KEY])) {
        if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
            http_response_code(403);
            die();
        } else {
            header('Location: ' . URL_PATH . '/page/login');
        }
        die();
    }

    $stmt = $connection->prepare('SELECT count(*) as count FROM user_role_authorizations as ur
                            INNER JOIN app_authorizations app ON ur.app_authorization_id = app.app_authorization_id
                            WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                            GROUP BY app.module');
    $stmt->execute([
        ':user_role_id' => $_SESSION[SESS_USER]['user_role_id'] ?? 0,
        ':module' => $module,
        ':action' => $action,
    ]);

    $data = $stmt->fetch();

    if ($data === false) {
        $res->message = 'Lo sentimos, no estás autorizado para realizar esta operación';

        if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
            http_response_code(403);
            die();
        } else {
            $content = requireToVar(VIEW_PATH . '/' . '403.view.php', [
                'message' => $res->message,
            ]);
            require_once(VIEW_PATH . '/' . 'layouts/basic.layout.php');
            die();
        }
    }

    $res->message = 'Acceso concedido';
    $res->success = true;
    return $res;
}

function menuIsAuthorized($menuName)
{
    $menu = json_decode(isset($_COOKIE['admin_menu']) ? $_COOKIE['admin_menu'] : '[]', true);
    if (count($menu) == 0) {
        return false;
    }

    if (gettype($menuName) === 'string') {
        $index = array_search($menuName, array_column($menu, 'module'));
        return is_numeric($index);
    } elseif (gettype($menuName) === 'array') {
        $valid = false;
        foreach ($menuName as $row) {
            $index = array_search($row, array_column($menu, 'module'));
            if (is_numeric($index)) {
                $valid = true;
            }
        }
        return $valid;
    } else {
        return false;
    }
}

function uploadAndValidateFile($file, $path, $fileName, $maxSize = 2097152, $mimeTypes = ['jpeg', 'jpg', 'png'])
{
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

    $fileName = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
    ), '', $fileName);

    if (in_array(strtolower($fileExt), $mimeTypes) === false) {
        throw new Exception('Extensión no permitida, elija un archivo .' . implode(', ', $mimeTypes));
    }
    if ($fileSize > $maxSize) {
        throw new Exception('Tamaño del archivo debe ser menor o igual a ' . $maxSize / 1024 / 1024 . ' MB');
    }

    $paths = explode('/', $path);
    $pathAux = '/';
    for ($i = 0; $i < count($paths); $i++) {
        if (!file_exists(ROOT_DIR . FILE_PATH . $pathAux . $paths[$i])) {
            mkdir(ROOT_DIR . FILE_PATH . $pathAux . $paths[$i]);
        }
        $pathAux .= $paths[$i] . '/';
    }

    $fileDir = FILE_PATH . $path . $fileName . '.' . strtolower($fileExt);
    move_uploaded_file($fileTmp, ROOT_DIR . $fileDir);

    return $fileDir;
}

function stringDateDiff($dt_menor, $dt_maior)
{
    if (is_string($dt_menor)) $dt_menor = date_create($dt_menor);
    if (is_string($dt_maior)) $dt_maior = date_create($dt_maior);

    $diff = date_diff($dt_menor, $dt_maior, false);

    $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;

    if ($diff->invert == 1) {
        return -1 * $total;
    } else {
        return $total;
    }
}

function stringDateDiffMonth($dt_menor, $dt_maior)
{
    $d1 = new DateTime($dt_menor);
    $d2 = new DateTime($dt_maior);
    $intervalMonth = $d2->diff($d1);
    return $intervalMonth->format('%m');
}
