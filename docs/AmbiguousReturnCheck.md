# AmbiguousReturnCheck

In functions returning falsy value, do not compare return values ​​without using the `===` operator.

For example, `strpos` returns integer or false, so if you check this with the `==` operator, when returning 0, the condition is the same as false.

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
if (strpos($var, "a")) { // AmbiguousReturnCheck: Use the === operator for testing a function that returns falsy value.
    echo "`a` is found.";
}
```

## After

```php
if (strpos($var, "a") !== false) {
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
