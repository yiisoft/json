<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii JSON</h1>
    <br>
</p>

The package provides methods to encode and decode JSON.

- It throws `\JsonException` even if PHP version is lower than 7.4.
- It has sensible defaults so you don't have to specify flags all the time.
- It has handy methods to encode for HTML safely.
- It handles `\JsonSerializable` and `\SimpleXMLElement` well. 

[![Latest Stable Version](https://poser.pugx.org/yiisoft/json/v/stable.png)](https://packagist.org/packages/yiisoft/json)
[![Total Downloads](https://poser.pugx.org/yiisoft/json/downloads.png)](https://packagist.org/packages/yiisoft/json)
[![Build Status](https://travis-ci.com/yiisoft/json.svg?branch=master)](https://travis-ci.com/yiisoft/json)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/json/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/json/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)

## General usage

Encoding:

```php
use \Yiisoft\Json\Json;

$data = ['name' => 'Alex', 'team' => 'Yii'];
$json = Json::encode($data);
```

Encoding for HTML:

```php
use \Yiisoft\Json\Json;

$data = ['name' => 'Alex', 'team' => 'Yii'];
$json = Json::htmlEncode($data);
```

Decoding:

```php
use \Yiisoft\Json\Json;

$json = '{"name":"Alex","team":"Yii"}';
$data = Json::decode($json);
```
