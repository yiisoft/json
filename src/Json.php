<?php

namespace Yiisoft\Json;

/**
 * Json is a helper class providing JSON data encoding and decoding.
 * It enhances the PHP built-in functions `json_encode()` and `json_decode()`
 * by throwing exceptions when decoding fails.
 */
final class Json
{
    private const ERRORS = [
        'JSON_ERROR_DEPTH' => 'Maximum stack depth exceeded',
        'JSON_ERROR_STATE_MISMATCH' => 'State mismatch (invalid or malformed JSON)',
        'JSON_ERROR_CTRL_CHAR' => 'Control character error, possibly incorrectly encoded',
        'JSON_ERROR_SYNTAX' => 'Syntax error',
        'JSON_ERROR_UTF8' => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        'JSON_ERROR_RECURSION' => 'Recursion detected',
        'JSON_ERROR_INF_OR_NAN' => 'Inf and NaN cannot be JSON encoded',
        'JSON_ERROR_UNSUPPORTED_TYPE' => 'Type is not supported',
        'JSON_ERROR_INVALID_PROPERTY_NAME' => 'The decoded property name is invalid',
        'JSON_ERROR_UTF16' => 'Single unpaired UTF-16 surrogate in unicode escape',
    ];

    /**
     * Encodes the given value into a JSON string.
     *
     * Note that data encoded as JSON must be UTF-8 encoded according to the JSON specification.
     * You must ensure strings passed to this method have proper encoding before passing them.
     *
     * @param mixed $value the data to be encoded.
     * @param int $options the encoding options. For more details please refer to
     * <http://www.php.net/manual/en/function.json-encode.php>. Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR`.
     * @param int $depth the maximum depth.
     * @return string the encoding result.
     * @throws \JsonException if there is any encoding error.
     */
    public static function encode(
        $value,
        int $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        int $depth = 512
    ): string {
        $shouldRethrowErrors = self::shouldRethrowErrors($options);

        $value = self::processData($value);

        if ($shouldRethrowErrors) {
            set_error_handler(
                static function () {
                    self::rethrowJsonError(JSON_ERROR_SYNTAX);
                },
                E_WARNING
            );
        }

        $json = json_encode($value, $options, $depth);

        if ($shouldRethrowErrors) {
            restore_error_handler();
            self::rethrowJsonError(json_last_error());
        }

        return $json;
    }

    private static function shouldRethrowErrors(int $options): bool
    {
        if (!self::hasFlag($options, JSON_THROW_ON_ERROR)) {
            return false;
        }

        return PHP_VERSION_ID < 70300;
    }

    private static function hasFlag(int $flags, int $flag): bool
    {
        return ($flags & $flag) === $flag;
    }

    /**
     * Encodes the given value into a JSON string HTML-escaping entities so it is safe to be embedded in HTML code.
     *
     * Note that data encoded as JSON must be UTF-8 encoded according to the JSON specification.
     * You must ensure strings passed to this method have proper encoding before passing them.
     *
     * @param mixed $value the data to be encoded
     * @return string the encoding result
     * @throws \JsonException if there is any encoding error
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
     * @param string $json the JSON string to be decoded
     * @param bool $asArray whether to return objects in terms of associative arrays.
     * @param int $depth the recursion depth.
     * @param int $options the decode options.
     * @return mixed the PHP data
     * @throws \JsonException if there is any decoding error
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
        $decode = json_decode($json, $asArray, $depth, $options);

        if (self::shouldRethrowErrors($options)) {
            self::rethrowJsonError(json_last_error());
        }
        return $decode;
    }

    /**
     * Handles [[encode()]] and [[decode()]] errors by throwing exceptions with the respective error message.
     *
     * @param int $lastError error code from [json_last_error()](http://php.net/manual/en/function.json-last-error.php).
     * @throws \JsonException if there is any encoding/decoding error.
     */
    private static function rethrowJsonError(int $lastError): void
    {
        if ($lastError === JSON_ERROR_NONE) {
            return;
        }
        $availableErrors = [];
        foreach (self::ERRORS as $constant => $message) {
            if (defined($constant)) {
                $availableErrors[constant($constant)] = $message;
            }
        }
        if (isset($availableErrors[$lastError])) {
            throw new \JsonException($availableErrors[$lastError], $lastError);
        }
        throw new \JsonException('Unknown JSON encoding/decoding error.');
    }

    /**
     * Pre-processes the data before sending it to `json_encode()`.
     * @param mixed $data the data to be processed
     * @return mixed the processed data
     */
    private static function processData($data)
    {
        if (is_object($data)) {
            if ($data instanceof \JsonSerializable) {
                return self::processData($data->jsonSerialize());
            }

            if ($data instanceof \DateTimeInterface) {
                return static::processData((array)$data);
            }

            if ($data instanceof \SimpleXMLElement) {
                $data = (array)$data;
            } else {
                $result = [];
                foreach ($data as $name => $value) {
                    $result[$name] = $value;
                }
                $data = $result;
            }
            if ($data === []) {
                return new \stdClass();
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = self::processData($value);
                }
            }
        }
        return $data;
    }
}
