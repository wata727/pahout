# DuplicateKey

When duplicate keys are in an array, it will be overwritten with the specified value later.

## Before

```php
<?php
$array = ["a" => 1, "a" => 2]; // DuplicateKey: Duplicate key found in array.
```

## After

```php
<?php
$array = ["a" => 1, "b" => 2]; // OK!
```

## Reference

https://secure.php.net/manual/en/language.types.array.php
