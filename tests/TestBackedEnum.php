<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

enum TestBackedEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case TEST = 'test';
}
