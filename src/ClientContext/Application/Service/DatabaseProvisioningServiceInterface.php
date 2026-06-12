<?php declare(strict_types=1);

namespace App\ClientContext\Application\Service;

interface DatabaseProvisioningServiceInterface
{
    public function createDatabase(string $dbName): void;
    public function createUser(string $username, string $password): void;
    public function grantPrivileges(string $database, string $username): void;
}
