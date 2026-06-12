<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use App\Shared\Application\Service\EnvStorageInterface;

class DotEnvService implements EnvStorageInterface
{
    public function writeEnv(string $path, array $variables = []): void
    {
        if (!$this->isDirExists($path)) {
            $this->createDir($path);
        }

        $file = $this->openFile($path);

        $content = '';
        foreach ($variables as $key => $variable) {
            $content .= "{$key}={$variable}\n";
        }

        fwrite($file, $content);
        fclose($file);
    }

    private function isDirExists(string $path): bool
    {
        return (is_dir(dirname($path)));
    }

    private function createDir(string $path): void
    {
        mkdir(dirname($path), 0755, true);
    }

    private function isEnvExists(string $path): bool
    {
        return (is_file($path) && is_readable($path));
    }

    private function openFile(string $path)
    {
        return fopen($path, 'w');
    }
}
