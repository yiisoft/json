<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use JsonSerializable;

final class Post implements JsonSerializable
{
    public function __construct(private $id, private $title)
    {
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
