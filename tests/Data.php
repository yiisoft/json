<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use JsonSerializable;

final class Data implements JsonSerializable
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->data;
    }
}
