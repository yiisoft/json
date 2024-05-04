<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii JSON</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/json/v/stable.png)](https://packagist.org/packages/yiisoft/json)
[![Total Downloads](https://poser.pugx.org/yiisoft/json/downloads.png)](https://packagist.org/packages/yiisoft/json)
[![Build Status](https://github.com/yiisoft/json/workflows/build/badge.svg)](https://github.com/yiisoft/json/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/json/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/json/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/json/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fjson%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/json/master)
[![static analysis](https://github.com/yiisoft/json/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/json/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/json/coverage.svg)](https://shepherd.dev/github/yiisoft/json)

The package provides methods to encode and decode JSON.

- It always throws `\JsonException` instead of returning false on error.
- It has sensible defaults, so you don't have to specify flags all the time.
- It has handy method to encode for HTML safely.
- It handles `\JsonSerializable`, `\DateTimeInterface`, and `\SimpleXMLElement` well.

## Requirements

- PHP 7.4 or higher.
- `JSON` PHP extension.
- `SimpleXML` PHP extension.

## Installation

The package could be installed via [composer](https://getcomposer.org/download/)

```shell
composer require yiisoft/json
```

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

## Documentation

- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii JSON is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
