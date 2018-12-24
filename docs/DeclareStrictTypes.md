# DeclareStrictTypes

Strict typing is introduced from PHP 7.0. Previously, PHP will coerce values into the expected scalar type even if the value type is wrong.  
By declaring `strict_types` you can enable strict mode.

## Before

```php
<?php // DeclareStrictTypes: Missing strict type declaration. The default types are not strict.

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
```

## After

```php
<?php declare(strict_types=1); // OK!

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
```

## Reference

https://secure.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.strict
