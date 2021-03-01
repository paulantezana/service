<?php

class Database
{
    private $connection;
    public function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        if(APP_DEV){
            // $this->connection = new PDO('sqlite:' . __DIR__ . '/servicio.db');
            $this->connection = new PDO('mysql:host=localhost;dbname=redes', 'root', '', $options);
        } else {
            $this->connection = new PDO('mysql:host=localhost;dbname=paulpvad_extremonet', 'paulpvad_extremonet', 'e01H!*m#r6Kl', $options);
        }
        $this->connection->exec("SET CHARACTER SET UTF8");
    }
    public function getConnection()
    {
        return $this->connection;
    }
    public function closeConnection()
    {
        $this->connection = null;
    }
}
