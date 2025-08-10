<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests\TestEnvironments\Php81\Enums;

enum StringEnum: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Test = 'test';
}
