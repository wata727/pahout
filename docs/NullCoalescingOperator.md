# NullCoalescingOperator

The null coalescing operator introduced in PHP 7 makes particular ternary operators easier.

## Before

```php
<?php
$a = isset($b) ? $b : false; // NullCoalescingOperator: Use the null coalecing operator instead of the ternary operator.
```

## After

```php
<?php
$a = $b ?? false; // OK!
```

## Reference

https://secure.php.net/manual/language.operators.comparison.php#language.operators.comparison.coalesce
