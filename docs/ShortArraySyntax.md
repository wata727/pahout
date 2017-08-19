# ShortArraySyntax

As of PHP 5.4, you can also use the short array syntax.
There is no reason other than compatibility to keep using old syntax.

## Before

```php
<?php
$a = array(1, 2, 3); // ShortArraySyntax: Use [...] syntax instead of array(...) syntax.
```

## After

```php
<?php
$a = [1, 2, 3]; // OK!
```

## Reference

http://php.net/manual/en/language.types.array.php#language.types.array.syntax.array-func
