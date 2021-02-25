<?php

class PageController extends Controller
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function home()
    {
        try{
            $this->redirect('/admin');
            // $this->render('home.view.php', [], 'layouts/site.layout.php');
        } catch (Exception $e) {
            $this->render('500.view.php', [
                'message' => $e->getMessage(),
            ], 'layouts/site.layout.php');
        }
    }

    public function error404()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';

        if (strtolower($_SERVER['HTTP_ACCEPT']) === 'application/json') {
            http_response_code(404);
        } else {
            $this->render('404.view.php', [
                'message' => $message
            ], 'layouts/site.layout.php');
        }
    }
}
