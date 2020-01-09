<?php
namespace Yiisoft\Json\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Json\Json;

class JsonTest extends TestCase
{
    public function testEncodeBasic(): void
    {
        $data = '1';
        $this->assertSame('"1"', Json::encode($data));
    }

    public function testEncodeSimpleArray(): void
    {
        $data = [1, 2];
        $this->assertSame('[1,2]', Json::encode($data));
        $data = ['a' => 1, 'b' => 2];
        $this->assertSame('{"a":1,"b":2}', Json::encode($data));
    }

    public function testEncodeSimpleObject(): void
    {
        $data = new \stdClass();
        $data->a = 1;
        $data->b = 2;
        $this->assertSame('{"a":1,"b":2}', Json::encode($data));
    }

    public function testEncodeEmpty(): void
    {
        $data = [];
        $this->assertSame('[]', Json::encode($data));
        $data = new \stdClass();
        $this->assertSame('{}', Json::encode($data));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/957
     */
    public function testEncodeNullObject(): void
    {
        $data = (object)null;
        $this->assertSame('{}', Json::encode($data));
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
        $data = new Data([]);
        $this->assertSame('[]', Json::encode($data));
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
        $data = '&<>"\'/';
        $this->assertSame('"\u0026\u003C\u003E\u0022\u0027\/"', Json::htmlEncode($data));
    }

    public function testHtmlEncodeBasic(): void
    {
        $data = '1';
        $this->assertSame('"1"', Json::htmlEncode($data));
    }

    public function testHtmlEncodeSimpleArray(): void
    {
        $data = [1, 2];
        $this->assertSame('[1,2]', Json::htmlEncode($data));
        $data = ['a' => 1, 'b' => 2];
        $this->assertSame('{"a":1,"b":2}', Json::htmlEncode($data));
    }

    public function testHtmlEncodeSimpleObject(): void
    {
        $data = new \stdClass();
        $data->a = 1;
        $data->b = 2;
        $this->assertSame('{"a":1,"b":2}', Json::htmlEncode($data));
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/957
     */
    public function testHtmlEncodeNullObject(): void
    {
        $data = (object) null;
        $this->assertSame('{}', Json::htmlEncode($data));
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

    public function testsHtmlEncodeSplStack(): void
    {
        $postsStack = new \SplStack();
        $postsStack->push(new Post(915, 'record1'));
        $postsStack->push(new Post(456, 'record2'));

        $this->assertSame('{"1":{"id":456,"title":"record2"},"0":{"id":915,"title":"record1"}}', Json::encode($postsStack));
    }

    public function testDecodeEmptyValue(): void
    {
        $json = '';
        $actual = Json::decode($json);
        $this->assertNull($actual);
    }

    public function testDecodeBasic(): void
    {
        $json = '"1"';
        $this->assertSame('1', Json::decode($json));
    }

    public function testsDecodeArray(): void
    {
        $json = '{"a":1,"b":2}';
        $this->assertSame(['a' => 1, 'b' => 2], Json::decode($json));
    }

    public function testsDecodeInvalidJsonThrowsException(): void
    {
        $json = '{"a":1,"b":2';
        $this->expectException(\JsonException::class);
        Json::decode($json);
    }

    public function testHandleJsonError(): void
    {
        $jsonClass = new \Reflectionclass(Json::class);
        $errors = $jsonClass->getConstant('ERRORS');

        // Basic syntax error
        try {
            $json = "{'a': '1'}";
            Json::decode($json);
        } catch (\JsonException $e) {
            $this->assertSame($errors['JSON_ERROR_SYNTAX'], $e->getMessage());
        }

        // Unsupported type since PHP 5.5
        try {
            $fp = fopen('php://stdin', 'r');
            $data = ['a' => $fp];
            Json::encode($data);
            fclose($fp);
        } catch (\JsonException $e) {
            $this->assertSame($errors['JSON_ERROR_UNSUPPORTED_TYPE'], $e->getMessage());
        }
    }

    /**
     * @link https://github.com/yiisoft/yii2/issues/17760
     */
    public function testEncodeDateTime()
    {
        $input = new \DateTime('October 12, 2014');
        $output = Json::encode($input);
        $this->assertEquals('{"date":"2014-10-12 00:00:00.000000","timezone_type":3,"timezone":"UTC"}', $output);
    }
}
