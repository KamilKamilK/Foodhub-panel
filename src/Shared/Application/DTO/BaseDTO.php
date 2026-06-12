<?php declare(strict_types=1);

namespace App\Shared\Application\DTO;

use Symfony\Component\HttpFoundation\File\File;

abstract class BaseDTO
{
    public function toArray(): array
    {
        return $this->mapNestedPropsToArray(get_object_vars($this));
    }

    private function mapNestedPropsToArray(array $arr): array
    {
        foreach ($arr as $k => $elem) {
            if (is_object($elem)) {
                if ($elem instanceof File) {
                    $arr[$k] = $elem;
                } else {
                    $arr[$k] = $this->mapNestedPropsToArray(get_object_vars($elem));
                }
            }
            if (is_array($elem)) {
                $arr[$k] = $this->mapNestedPropsToArray($elem);
            }
        }
        return $arr;
    }
}
