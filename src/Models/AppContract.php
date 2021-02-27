<?php

class AppContract extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("app_contracts", "app_contract_id", $connection);
    }
}
