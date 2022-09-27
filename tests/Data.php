<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use JsonSerializable;

final class Data implements JsonSerializable
{
    public function __construct(private $data)
    {
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->data;
    }
}
