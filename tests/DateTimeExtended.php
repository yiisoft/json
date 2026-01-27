<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use DateTime;

final class DateTimeExtended extends DateTime
{
    public string $public = 'public property';
    private string $private = 'private property';
}
