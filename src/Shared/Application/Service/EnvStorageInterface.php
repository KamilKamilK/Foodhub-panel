<?php declare(strict_types=1);

namespace App\Shared\Application\Service;

interface EnvStorageInterface
{
    public function writeEnv(string $path, array $variables): void;
}
