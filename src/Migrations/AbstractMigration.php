<?php declare(strict_types=1);

namespace App\Migrations;

use \Doctrine\Migrations\AbstractMigration as BaseMigration;

abstract class AbstractMigration extends BaseMigration
{
    public function isSetup(): bool
    {
        if (in_array('lsi_user', $this->sm->listTableNames()) && $this->connection->fetchArray('SELECT id FROM lsi_user LIMIT 1') !== false) {
            return false;
        }

        return true;
    }

    public function isUpdate(): bool
    {
        return !$this->isSetup();
    }
}
