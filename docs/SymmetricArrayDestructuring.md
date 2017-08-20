# SymmetricArrayDestructuring

In PHP 7.1 and later, short array syntax can be used as an alternative to `list()`.

## Before

```php
<?php
list($a, $b) = $array; // SymmetricArrayDestructuring: Use [...] syntax instead of list(...) syntax.
```

## After

```php
<?php
[$a, $b] = $array; // OK!
```

## Reference

https://secure.php.net/manual/migration71.new-features.php#migration71.new-features.symmetric-array-destructuring
