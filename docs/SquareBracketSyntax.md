# SquareBracketSyntax

If you use `array_push()` to add one element to the array it's better to use `$array[] =` because in that way there is no overhead of calling a function.

## Before

```php
<?php
array_push($array, 1); // SquareBracketSyntax: Since `array_push()` has the function call overhead, let's use `$array[] =`.
```

## After

```php
<?php
$array[] = 1; // OK!
```

## Reference

http://php.net/manual/function.array-push.php
