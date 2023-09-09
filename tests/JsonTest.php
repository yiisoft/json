<?php

declare(strict_types=1);

namespace Yiisoft\Json\Tests;

use DateTime;
use DateTimeZone;
use JsonException;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use SplStack;
use stdClass;
use Yiisoft\Json\Json;

use function fclose;
use function fopen;
use function json_encode;

final class JsonTest extends TestCase
{
    public function testEncodeBasic(): void
    {
        $this->assertSame('"1"', Json::encode('1'));
    }

    public function testEncodeDoesNotEscapeSlashesAndUnicode(): void
    {
        $this->assertSame('"/🎁"', Json::encode('/🎁'));
    }

    public function testEncodeSimpleArray(): void
    {
        $this->assertSame('[1,2]', Json::encode([1, 2]));
        $this->assertSame('{"a":1,"b":2}', Json::encode(['a' => 1, 'b' => 2]));
    }

    public function testEncodeSimpleObject(): void
    {
        $data = new stdClass();
        $data->a = 1;
        $data->b = 2;
        $this->assertSame('{"a":1,"b":2}', Json::encode($data));
    }

    public function testEncodeEmpty(): void
    {
        $this->assertSame('[]', Json::encode([]));
        $this->assertSame('{}', Json::encode(new stdClass()));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/957
     */
    public function testEncodeNullObject(): void
    {
        $this->assertSame('{}', Json::encode((object)null));
    }

    public function testEncodeJsonSerializable(): void
    {
        $data = new Post(42, 'json serializable');
        $this->assertSame('{"id":42,"title":"json serializable"}', Json::encode($data));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/12043
     */
    public function testEncodeWithSerializableReturningEmptyArray(): void
    {
        $this->assertSame('[]', Json::encode(new Data([])));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/12043
     */
    public function testEncodeWithSerializableReturningEmptyObject(): void
    {
        $data = new Data((object)null);
        $this->assertSame('{}', Json::encode($data));
    }

    public function testsHtmlEncodeEscapesCharacters(): void
    {
        $this->assertSame('"\u0026\u003C\u003E\u0022\u0027\/"', Json::htmlEncode('&<>"\'/'));
    }

    public function testHtmlEncodeBasic(): void
    {
        $this->assertSame('"1"', Json::htmlEncode('1'));
    }

    public function testHtmlEncodeSimpleArray(): void
    {
        $this->assertSame('[1,2]', Json::htmlEncode([1, 2]));
        $this->assertSame('{"a":1,"b":2}', Json::htmlEncode(['a' => 1, 'b' => 2]));
    }

    public function testHtmlEncodeSimpleObject(): void
    {
        $data = new stdClass();
        $data->a = 1;
        $data->b = 2;
        $this->assertSame('{"a":1,"b":2}', Json::htmlEncode($data));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/957
     */
    public function testHtmlEncodeNullObject(): void
    {
        $this->assertSame('{}', Json::htmlEncode((object) null));
    }

    public function testHtmlEncodeJsonSerializable(): void
    {
        $data = new Post(42, 'json serializable');
        $this->assertSame('{"id":42,"title":"json serializable"}', Json::htmlEncode($data));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/10278
     */
    public function testsHtmlEncodeXmlDocument(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <file>
          <apiKey>ieu2iqw4o</apiKey>
          <methodProperties>
            <FindByString>Kiev</FindByString>
          </methodProperties>
        </file>';

        $document = simplexml_load_string($xml);
        $this->assertSame('{"apiKey":"ieu2iqw4o","methodProperties":{"FindByString":"Kiev"}}', Json::encode($document));
    }

    public function testEncodeSimpleXmlElement(): void
    {
        $data = new SimpleXMLElement('<value>42</value>');
        $this->assertSame('["42"]', Json::encode($data));
    }

    public function testEncodeSimpleXmlElementWithinArray(): void
    {
        $data = [new SimpleXMLElement('<value>42</value>')];
        $this->assertSame('[["42"]]', Json::encode($data));
    }

    public function testEncodeEmptySimpleXmlElement(): void
    {
        $data = new SimpleXMLElement('<value/>');
        $this->assertSame('{}', Json::encode($data));
    }

    public function testsHtmlEncodeSplStack(): void
    {
        $postsStack = new SplStack();
        $postsStack->push(new Post(915, 'record1'));
        $postsStack->push(new Post(456, 'record2'));

        $this->assertSame('{"1":{"id":456,"title":"record2"},"0":{"id":915,"title":"record1"}}', Json::encode($postsStack));
    }

    public function testDecodeEmptyValue(): void
    {
        $this->assertNull(Json::decode(''));
    }

    public function testDecodeBasic(): void
    {
        $this->assertSame('1', Json::decode('"1"'));
    }

    public function testsDecodeArray(): void
    {
        $this->assertSame(['a' => 1, 'b' => 2], Json::decode('{"a":1,"b":2}'));
    }

    public function testsDecodeInvalidJsonThrowsException(): void
    {
        $this->expectException(JsonException::class);
        Json::decode('{"a":1,"b":2');
    }

    public function testsDecodeWithFlagsInvalidJsonThrowsException(): void
    {
        $this->expectException(JsonException::class);
        Json::decode('{"a":1,"b":2', true, 512, JSON_INVALID_UTF8_IGNORE);
    }

    public function testHandleJsonError(): void
    {
        // Basic syntax error
        try {
            Json::decode("{'a': '1'}");
        } catch (JsonException $e) {
            $this->assertSame('Syntax error', $e->getMessage());
        }

        $fp = fopen('php://stdin', 'rb');
        $data = ['a' => $fp];

        try {
            Json::encode($data);
        } catch (JsonException $e) {
            $this->assertSame('Type is not supported', $e->getMessage());
        } finally {
            fclose($fp);
        }
    }

    /**
     * @link https://github.com/yiisoft/yii2/issues/17760
     */
    public function testEncodeDateTime(): void
    {
        $input = new DateTime('October 12, 2014', new DateTimeZone('UTC'));
        $this->assertEquals('{"date":"2014-10-12 00:00:00.000000","timezone_type":3,"timezone":"UTC"}', Json::encode($input));
    }

    public function testEncodeDateTimeExtended()
    {
        $input = new DateTimeExtended('2023-09-09 10:00:00');

        $this->assertEquals(
            '{"public":"public property","date":"2023-09-09 10:00:00.000000","timezone_type":3,"timezone":"UTC"}',
            Json::encode($input),
        );
        $this->assertSame(json_encode($input), Json::encode($input));
    }
}
