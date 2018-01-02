# ElvisOperator

The Elvis operator introduced in PHP 5.3 makes particular ternary operators easier.

## Before

```php
<?php
$a = $a ? $a : false; // ElvisOperator: Use the Elvis operator instead of the ternary operator.
```

## After

```php
<?php
$a = $a ?: false; // OK!
```

## Reference

https://secure.php.net/manual/language.operators.comparison.php#language.operators.comparison.ternary
