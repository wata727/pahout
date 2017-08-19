# NullCoalescingOperator

As of PHP 7, you can use the "null coalescing operator".
You can avoid redundant expressions by using this.

## Before

```php
<?php
$a = isset($b) ? $b : false; // NullCoalescingOperator: Use null coalecing operator instead of ternary operator.
```

## After

```php
<?php
$a = $b ?? false; // OK!
```

## Reference

http://php.net/manual/language.operators.comparison.php#language.operators.comparison.coalesce
