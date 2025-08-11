<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests\TestEnvironments\Php81\Enums;

use JsonException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Json\Json;

use function PHPUnit\Framework\assertSame;

final class EnumsTest extends TestCase
{
    public static function dataBackedEnum(): iterable
    {
        yield 'string enum' => ['"test"', StringEnum::Test];
        yield 'integer enum' => ['1', IntegerEnum::One];
        yield 'array with backed enum' => [
            '{"status":"active"}',
            ['status' => StringEnum::Active],
        ];

        $object = new stdClass();
        $object->status = StringEnum::Inactive;
        yield 'object with backed enum' => [
            '{"status":"inactive"}',
            $object,
        ];
    }

    /**
     * @dataProvider dataBackedEnum
     */
    public function testBackedEnum(string $expected, mixed $value): void
    {
        $result = Json::encode($value);

        assertSame($expected, $result);
    }

    public function testPureEnum(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Non-backed enums have no default serialization');
        Json::encode(PureEnum::Red);
    }
}
