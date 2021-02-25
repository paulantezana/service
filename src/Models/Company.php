<?php

class Company extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('companies', 'company_id', $connection);
    }
}
