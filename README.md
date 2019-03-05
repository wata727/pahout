# Pahout
[![Build Status](https://travis-ci.org/wata727/pahout.svg?branch=master)](https://travis-ci.org/wata727/pahout)
[![Latest Stable Version](https://poser.pugx.org/wata727/pahout/v/stable)](https://packagist.org/packages/wata727/pahout)
[![Docker Hub](https://img.shields.io/badge/docker-ready-blue.svg)](https://hub.docker.com/r/wata727/pahout/)
[![MIT License](http://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

A pair programming partner for writing better PHP. Pahout means PHP mahout :elephant:

## Motivation

PHP has been added various features in the long history. However, due to the length of its history, many old syntax is scattered over the Internet. If a PHPer learned from them, the PHPer cannot know the excellent syntax and functions existing in the latest PHP version. This is a very sad thing.

So, I thought about making a linter like a pair programming partner who tells you a good way. It will help you write better PHP with you.

However, please do not try to fix all existing codes based on the hints of Pahout first. Pahout is a pair programming partner. When pairing programming, you don't check all existing codes, do you? My recommendation is to only check on newly created or modified files. For example, it is a good idea to set pre-commit hook as follows:

```sh
#!/bin/sh

git diff --name-only | grep .php | xargs vendor/bin/pahout
if ["$?" -ne 0]; then
    exit 1
fi
```

## Installation

Pahout requires the following environment:

- PHP 7.1 or newer
- [php-ast](https://github.com/nikic/php-ast) v0.1.7 or newer

### Using Composer

You can install with composer.

```
$ composer require --dev wata727/pahout
$ vendor/bin/pahout -V
```

### Using Docker

By using the [Docker image](https://hub.docker.com/r/wata727/pahout/), you can easily try Pahout without affecting the local environment.

```
$ docker run --rm -t -v $(pwd):/workdir wata727/pahout
```

## Quick Start

You are using PHP 7.1.8 in your project. What do you think of the following code?

```php
<?php

// Do something...

$response = get_awesome_response();
$error = isset($response['error']) ? $response['error'] : null;

// Do something...

```

Perhaps it is a familiar code. However, if you know the [null coalescing operator](https://secure.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.coalesce), you will write something like this:

```php
<?php

// Do something...

$response = get_awesome_response();
$error = $response['error'] ?? null; # Great!

// Do something...

```

Pahout will support such rewriting. Try to run on the above example.

```
$ pahout --php-version 7.1.8 test.php
test.php:8
    NullCoalescingOperator: Use the null coalecing operator instead of the ternary operator. [https://github.com/wata727/pahout/blob/master/docs/NullCoalescingOperator.md]

1 files checked, 1 hints detected.
```

Pahout tells you where the null coalescing operator can be used! For the list of hints provided by Pahout, please see this [documentation](docs).

If you want to analyze multiple files, you can specify more than one.

```
$ pahout --php-version 7.1.8 test.php test2.php ...
```

If you specify a directory name, all `.php` files under that directory will be covered.

```
$ pahout --php-version 7.1.8 src
```

## Configuration

You can change the configuration from the command line.

```
$ pahout --help
Description:
  A pair programming partner for writing better PHP

Usage:
  check [options] [--] [<files>...]

Arguments:
  files                              List of file names or directory names to be analyzed

Options:
      --php-version[=PHP-VERSION]    Target PHP version [default: runtime version]
      --ignore-tools[=IGNORE-TOOLS]  Ignore tool types [default: Nothing to ignore] (multiple values allowed)
      --ignore-paths[=IGNORE-PATHS]  Ignore files and directories [default: Nothing to ignore] (multiple values allowed)
      --extensions[=EXTENSIONS]      File extensions to be analyzed [default: php] (multiple values allowed)
      --vendor[=VENDOR]              Check vendor directory [default: false]
  -f, --format[=FORMAT]              Output format [default: "pretty", possibles: "pretty", "json"]
  -c, --config[=CONFIG]              Config file path [default: ".pahout.yaml"]
      --only-tools[=ONLY-TOOLS]      Check only the given tool types (multiple values allowed)
  -h, --help                         Display this help message
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

You can also change the configuration by preparing a configuration file called `.pahout.yaml`.

```yaml
php_version: 7.0.0
ignore_tools:
    - ShortArraySyntax
ignore_paths:
    - tests
    - bin
extensions:
    - php
    - module
    - inc
vendor: true
```

### PHP Version

Specify the PHP version of your project. The default is the runtime version. Pahout uses this version to select tools.

### Ignore Tools

In Pahout, what generates hints is called "Tool". You can specify the tool name you want to ignore. Please look at the documentation for the list of tool names.

### Only Tools

Contrary to `ignore_tools`, specify tools to check.

### Ignore Paths

You can specify the file or directory you want to ignore. If a directory name is specified, all files under that directory are ignored.

### Extensions

File extensions to be analyzed. Default is `php` only.

### Vendor

You can set whether to ignore the vendor directory.

Note: The vendor directory is ignored by default. Generally, you don't need to check the vendor directory.

### Format

Specify the output format. Currently only `pretty` and `json` are supported.

### Config

Specify a configuration file name. This is useful when you want to use the file name other than `.pahout.yaml` in the configuration file.

## Annotation

Using annotations, you can ignore hints for specific lines. Specify the tool name you want to ignore with `@rebel`.

```php
<?php

/** @rebel NullCoalescingOperator */
$error = isset($response['error']) ? $response['error'] : null;
```

The following comments will work in the same way.

```php
<?php

/**
* @rebel NullCoalescingOperator
*/
$error = isset($response['error']) ? $response['error'] : null;

$error = isset($response['error']) ? $response['error'] : null; # @rebel NullCoalescingOperator
```

## Author

[Kazuma Watanabe](https://github.com/wata727)
