# ElvisOperator

As of PHP 5.3, you can use the "elvis operator".
You can avoid redundant expressions by using this.

## Before

```php
<?php
$a = $a ? $a : false; // ElvisOperator: Use elvis operator instead of ternary operator.
```

## After

```php
<?php
$a = $a ?: false; // OK!
```

## Reference

http://php.net/manual/language.operators.comparison.php