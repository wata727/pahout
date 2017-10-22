# RedundantTernaryOperator

If the ternary operator returns boolean, depending on the condition expression, it is not necessary to use the ternary operator. It detects the following code:

- `$a === $b ? true : false`
- `$a == $b ? true : false`
- `$a !== $b ? true : false`
- `$a != $b ? true : false`

## Before

```php
<?php
$a = $b === $c ? true : false; // RedundantTernaryOperator: There is no need to use a ternary operator.
```

## After

```php
<?php
$a = $b === $c; // OK!
```

## Reference
