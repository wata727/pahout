# RandomInt

`random_int()` and `random_byte()` added in PHP 7.0 generate cryptographically secure pseudo-random integers.
`rand()` and `mt_rand()` are cryptographically insecure.

## Before

```php
<?php
$rand = mt_rand(); // RandomInt: This function is not cryptographically secure. Consider using `random_int()`, `random_bytes()`, or `openssl_random_pseudo_bytes()` instead.
```

## After

```php
<?php
$rand = random_int(0, PHP_INT_MAX); // OK!
```

## Reference

https://secure.php.net/manual/function.mt-rand.php
