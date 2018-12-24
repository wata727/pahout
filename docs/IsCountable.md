# IsCountable

`is_countable` function has been added in PHP 7.3. Previously, in order to check whether an object is countable or not, it was necessary to always check whether it is an array or it implements Countable interface.

## Before

```php
<?php
$obj = get_object();
if (is_array($obj) || $obj instanceof Countable) { // IsCountable: You can use `is_countable` function instead.
    // Something
}
```

## After

```php
<?php
$obj = get_object();
if (is_countable($obj)) { // OK!
    // Something
}
```

## Reference

https://secure.php.net/manual/en/function.is-countable.php
