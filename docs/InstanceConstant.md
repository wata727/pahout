# InstanceConstant

As of PHP 5.3.0, it's possible to reference class using a variable.
Class constants are defined for classes, but they can also be referenced from instances. So it is not necessary to get the class name from the instance.

## Before

```php
<?php
$instance = new Hoge();
get_class($instance)::CONSTANT; // InstanceConstant: You can access class constants from instances.
```

## After

```php
<?php
$instance = new Hoge();
$instance::CONSTANT; // OK!
```

## Reference

https://secure.php.net/manual/language.oop5.constants.php
