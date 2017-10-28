# DuplicateKey

If the keys of the array duplicate, it will be overwritten with the specified value later.

NOTE: Array keys are cast in some cases. However, it is not possible to detect duplicate keys in such cases.

## Before

```php
<?php
$array = ["a" => 1, "a" => 2]; // DuplicateKey: Duplicate keys found in array.
```

## After

```php
<?php
$array = ["a" => 1, "b" => 2]; // OK!
```

## Reference

https://secure.php.net/manual/en/language.types.array.php
