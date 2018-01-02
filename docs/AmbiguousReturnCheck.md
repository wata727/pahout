# AmbiguousReturnCheck

When testing the return value of functions that return not only false but falsy values, you should use the === (or !==) operator.

For example, `strpos()` returns integers or false, so it may cause the following bug:

```php
<?php

if (strpos("abc", $argv[1])) {
    echo "`$argv[1]` is found in `abc`.";
} else {
    echo "`$argv[1]` is not found in `abc`.";
}
```

```
$ php test.php d
`d` is not found in `abc`.
$ php test.php b
`b` is found in `abc`.
$ php test.php a
`a` is not found in `abc`. # Why?
```

This is because the condition is treated as false when returning 0.

The following functions are checked:

- strpos
- mb_strpos
- stripos
- mb_stripos
- strrpos
- mb_strrpos
- array_search

## Before

```php
if (strpos($var, "a")) { // AmbiguousReturnCheck: Use the === (or !==) operator for testing the return value of `strpos`.
    echo "`a` is found.";
}
```

## After

```php
if (strpos($var, "a") !== false) { // OK!
    echo "`a` is found.";
}
```

## Reference

- https://secure.php.net/manual/en/function.strpos.php
- https://secure.php.net/manual/en/function.mb-strpos.php
- https://secure.php.net/manual/en/function.stripos.php
- https://secure.php.net/manual/en/function.mb-stripos.php
- https://secure.php.net/manual/en/function.strrpos.php
- https://secure.php.net/manual/en/function.mb-strrpos.php
- https://secure.php.net/manual/en/function.array-search.php
