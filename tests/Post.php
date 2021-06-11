<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use JsonSerializable;

final class Post implements JsonSerializable
{
    private $id;
    private $title;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
