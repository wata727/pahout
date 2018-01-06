# ArrayPushShorthand

If you use `array_push()` to add one element to an array it's better to use `$array[] =` because in that way there is no overhead of calling the function.

## Before

```php
<?php
array_push($array, 1); // ArrayPushShorthand: Since `array_push()` has the function call overhead, Consider using `$array[] =`.
```

## After

```php
<?php
$array[] = 1; // OK!
```

## Reference

https://secure.php.net/manual/function.array-push.php
