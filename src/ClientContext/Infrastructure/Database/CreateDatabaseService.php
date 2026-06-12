<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Database;

use App\ClientContext\Application\Service\DatabaseProvisioningServiceInterface;
use App\ClientContext\Domain\Exception\DatabaseCreationFailedException;
use App\ClientContext\Domain\Exception\DatabaseGrantAllPrivilegesFailedException;
use App\ClientContext\Domain\Exception\ClientUserCreationFailedException;
use Doctrine\DBAL\Connection;

class CreateDatabaseService implements DatabaseProvisioningServiceInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function createDatabase(string $dbName): void
    {
        $safe = $this->sanitizeIdentifier($dbName);
        $stmt = $this->connection->prepare(
            "CREATE DATABASE $safe ENCODING 'utf8' LC_COLLATE = 'pl_PL.utf-8' LC_CTYPE = 'pl_PL.utf-8' TEMPLATE template0"
        );

        if (!$stmt->execute()) {
            throw new DatabaseCreationFailedException();
        }
    }

    public function createUser(string $username, string $password): void
    {
        $safe = $this->sanitizeIdentifier($username);
        $stmt = $this->connection->prepare(
            "CREATE USER $safe WITH ENCRYPTED PASSWORD :password"
        );

        if (!$stmt->execute(['password' => $password])) {
            throw new ClientUserCreationFailedException();
        }
    }

    public function grantPrivileges(string $database, string $username): void
    {
        $safeDb   = $this->sanitizeIdentifier($database);
        $safeUser = $this->sanitizeIdentifier($username);
        $stmt = $this->connection->prepare(
            "GRANT ALL PRIVILEGES ON DATABASE $safeDb TO $safeUser"
        );

        if (!$stmt->execute()) {
            throw new DatabaseGrantAllPrivilegesFailedException();
        }
    }

    public function changeDatabase(string $dbName): bool
    {
        $params           = $this->connection->getParams();
        $params['dbname'] = $dbName;

        if ($this->connection->isConnected()) {
            $this->connection->close();
        }

        $this->connection->__construct(
            $params,
            $this->connection->getDriver(),
            $this->connection->getConfiguration(),
            $this->connection->getEventManager(),
        );

        return $this->connection->connect();
    }

    private function sanitizeIdentifier(string $identifier): string
    {
        return preg_replace('/[^a-z0-9_]/', '', strtolower($identifier));
    }
}
