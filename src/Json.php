<?php

declare(strict_types=1);

namespace Yiisoft\Json;

use DateTimeInterface;
use JsonException;
use JsonSerializable;
use SimpleXMLElement;
use stdClass;
use Traversable;

use function get_object_vars;
use function json_decode;
use function json_encode;
use function is_array;
use function is_object;
use function iterator_to_array;

/**
 * Json is a helper class providing JSON data encoding and decoding.
 * It enhances the PHP built-in functions `json_encode()` and `json_decode()`
 * by throwing exceptions when decoding fails.
 */
final class Json
{
    /**
     * Encodes the given value into a JSON string.
     *
     * Note that data encoded as JSON must be UTF-8 encoded according to the JSON specification.
     * You must ensure strings passed to this method have proper encoding before passing them.
     *
     * @param mixed $value The data to be encoded.
     * @param int $options The encoding options. For more details please refer to
     * {@see http://www.php.net/manual/en/function.json-encode.php}.
     * Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR`.
     * @param int $depth The maximum depth.
     *
     * @psalm-param int<1, 2147483647> $depth
     *
     * @throws JsonException if there is any encoding error.
     *
     * @return string The encoding result.
     */
    public static function encode(
        $value,
        int $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        int $depth = 512
    ): string {
        if (is_array($value)) {
            $value = self::processArray($value);
        } elseif (is_object($value)) {
            /** @psalm-var mixed $value */
            $value = self::processObject($value);
        }

        return json_encode($value, JSON_THROW_ON_ERROR | $options, $depth);
    }

    /**
     * Encodes the given value into a JSON string HTML-escaping entities so it is safe to be embedded in HTML code.
     *
     * Note that data encoded as JSON must be UTF-8 encoded according to the JSON specification.
     * You must ensure strings passed to this method have proper encoding before passing them.
     *
     * @param mixed $value The data to be encoded.
     *
     * @throws JsonException If there is any encoding error.
     *
     * @return string The encoding result.
     */
    public static function htmlEncode($value): string
    {
        return self::encode(
            $value,
            JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_THROW_ON_ERROR
        );
    }

    /**
     * Decodes the given JSON string into a PHP data structure.
     *
     * @param string $json The JSON string to be decoded.
     * @param bool $asArray Whether to return objects in terms of associative arrays.
     * @param int $depth The recursion depth.
     * @param int $options The decode options.
     *
     * @psalm-param int<1, 2147483647> $depth
     *
     * @throws JsonException If there is any decoding error.
     *
     * @return mixed The PHP data.
     */
    public static function decode(
        string $json,
        bool $asArray = true,
        int $depth = 512,
        int $options = JSON_THROW_ON_ERROR
    ) {
        if ($json === '') {
            return null;
        }
        return json_decode($json, $asArray, $depth, JSON_THROW_ON_ERROR | $options);
    }

    /**
     * Pre-processes the array before sending it to `json_encode()`.
     *
     * @param array $data The array to be processed.
     *
     * @return array The processed array.
     */
    private static function processArray(array $data): array
    {
        /** @psalm-var mixed $value */
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::processArray($value);
            } elseif (is_object($value)) {
                /** @psalm-var mixed */
                $data[$key] = self::processObject($value);
            }
        }

        return $data;
    }

    /**
     * Pre-processes the object before sending it to `json_encode()`.
     *
     * @param object $data The object to be processed.
     *
     * @return mixed The processed data.
     */
    private static function processObject(object $data)
    {
        if ($data instanceof JsonSerializable) {
            /** @psalm-var mixed $data */
            $data = $data->jsonSerialize();

            if (is_array($data)) {
                return self::processArray($data);
            }

            if (is_object($data)) {
                return self::processObject($data);
            }

            return $data;
        }

        if ($data instanceof DateTimeInterface) {
            return $data;
        }

        if ($data instanceof SimpleXMLElement) {
            return (array)$data ?: new stdClass();
        }

        if ($data instanceof Traversable) {
            return self::processArray(iterator_to_array($data)) ?: new stdClass();
        }

        return self::processArray(get_object_vars($data)) ?: new stdClass();
    }
}
