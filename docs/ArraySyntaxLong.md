# ArraySyntaxLong

As of PHP 5.4, you can also use the short array syntax.
There is no reason other than compatibility to keep using old syntax.

## Before

```php
<?php
$a = array(1, 2, 3); // ArraySyntaxLong: Use [...] syntax instead of array(...) syntax.
```

## After

```php
<?php
$a = [1, 2, 3]; // OK!
```

## Reference

http://php.net/manual/language.types.array.php
