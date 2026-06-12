<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Setup\DTO;

class Device
{
    public function __construct(
        private readonly string $macAddress,
        private readonly string $name,
        private readonly string $model,
        private readonly ?string $platform,
        private readonly ?string $version,
    ) {
    }

    public function getMacAddress(): string  { return $this->macAddress; }
    public function getName(): string        { return $this->name; }
    public function getModel(): string       { return $this->model; }
    public function getPlatform(): ?string   { return $this->platform; }
    public function getVersion(): ?string    { return $this->version; }

    public function toArray(): array
    {
        return [
            'macAddress' => $this->macAddress,
            'name'       => $this->name,
            'model'      => $this->model,
            'platform'   => $this->platform,
            'version'    => $this->version,
        ];
    }
}
