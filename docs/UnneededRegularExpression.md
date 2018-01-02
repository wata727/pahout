# UnneededRegularExpression

Under the following conditions, using `strpos()` instead of `preg_match()` will be faster.

1. Not using `matches` variables.
2. Not using a pattern including pattern modifiers.
3. Not using a pattern including meta characters.

## Before

```php
<?php

if (preg_match("/abc/", $var)) { // UnneededRegularExpression: Using `strpos()` instead of `preg_match()` will be faster.
    something($var);
}
```

## After

```php
<?php

if (strpos($var, "abc") !== false) {
    something($var);
}
```


## Reference

https://secure.php.net/manual/en/function.preg-match.php
