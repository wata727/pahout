# VariableLengthArgumentLists

In PHP 5.6 and later, it has support for variable-length argument lists in user-defined functions.
Previously, we needed `func_num_args()`, `func_get_arg()` and `func_get_args()` to handle variable-length arguments, but it is no longer necessary.

## Before

```php
<?php
function sum() {
    $acc = 0;
    foreach (func_get_args() as $n) { // VariableLengthArgumentLists: Using variable length arguments may make it unnecessary to use `func_num_args()`, `func_get_arg()` and `func_get_args()`.
        $acc += $n;
    }
    return $acc;
}
```

## After

```php
<?php
function sum(...$numbers) { // OK!
    $acc = 0;
    foreach ($numbers as $n) {
        $acc += $n;
    }
    return $acc;
}
```

## Reference

http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list
