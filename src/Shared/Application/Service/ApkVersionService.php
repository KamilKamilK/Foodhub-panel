<?php declare(strict_types=1);

namespace App\Shared\Application\Service;

class ApkVersionService
{
    protected string $apkDir = '';
    protected string $latestApkVersion = '';

    public function setApkDir(string $apkDir): bool
    {
        if (!is_dir($apkDir)) {
            return false;
        }
        $this->apkDir = $apkDir;
        return true;
    }

    public function getApkDir(): string
    {
        return $this->apkDir;
    }

    public function setLatestApkVersion(): bool
    {
        if (empty($this->getApkDir())) {
            return false;
        }

        $apkFiles = scandir($this->apkDir);
        natsort($apkFiles);
        $apkFiles = array_values($apkFiles);
        $apkFiles = array_reverse($apkFiles);
        $this->latestApkVersion = substr($apkFiles[0], 0, -4);

        return !empty($this->latestApkVersion);
    }

    public function getLatestApkVersion(): string
    {
        return $this->latestApkVersion;
    }

    public function isApkUpToDate(string $apkVersion): bool
    {
        return strnatcmp($apkVersion, $this->latestApkVersion) !== -1;
    }

    public function isLatestApkValid(): bool
    {
        return !in_array($this->latestApkVersion, ['.', '..']);
    }

    public function getLatestApkDecoded(): string
    {
        $path = "{$this->apkDir}/{$this->latestApkVersion}.apk";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return "data:file/{$type};base64," . base64_encode($data);
    }
}
