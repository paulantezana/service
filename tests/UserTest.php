<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Core/Model.php');
require_once(__DIR__ . '/../src/Services/Database.php');
require_once(__DIR__ . '/../src/Models/User.php');

class UserTest extends TestCase
{
    protected $connection;
    protected $user;

    public function setUp() : void 
    {
        $database = new Database();
        $this->connection = $database->getConnection();
        $this->user = new User($this->connection);
    }

    public function testUserPaginate()
    {
        $user = $this->user->paginate(1);
        $this->assertIsArray($user['data']);
    }
}
