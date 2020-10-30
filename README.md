<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii JSON</h1>
    <br>
</p>

The package provides methods to encode and decode JSON.

- It always throws `\JsonException` instead of returning false on error.
- It has sensible defaults, so you don't have to specify flags all the time.
- It has handy method to encode for HTML safely.
- It handles `\JsonSerializable`, `\DateTimeInterface`, and `\SimpleXMLElement` well. 

[![Latest Stable Version](https://poser.pugx.org/yiisoft/json/v/stable.png)](https://packagist.org/packages/yiisoft/json)
[![Total Downloads](https://poser.pugx.org/yiisoft/json/downloads.png)](https://packagist.org/packages/yiisoft/json)
[![Build Status](https://github.com/yiisoft/json/workflows/build/badge.svg)](https://github.com/yiisoft/json/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/json/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/json/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fjson%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/json/master)
[![static analysis](https://github.com/yiisoft/json/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/json/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/json/coverage.svg)](https://shepherd.dev/github/yiisoft/json)

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

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```php
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```php
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```php
./vendor/bin/psalm
```
