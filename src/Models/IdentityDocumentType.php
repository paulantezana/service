<?php

class IdentityDocumentType extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct('identity_document_types', 'code', $connection);
    }
}