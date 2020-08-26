<?php
declare(strict_types=1);

namespace Yiisoft\Json\Tests;

final class Data implements \JsonSerializable
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
