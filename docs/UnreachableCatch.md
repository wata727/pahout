# UnreachableCatch

For example, `Exception` is the base class for all user-defined exceptions, so when you catch it, the later exception handling will no be reached. This checks for `Exception` and `Throwable`.

## Before

```php
<?php
class ApplicationError extends Exception {}

try {
    something();
} catch (Exception $e) {
    echo "general error";
} catch (ApplicationError $e) { // UnreachableCatch: This exception handling will not be reached.
    echo "application error";
}
```

## After

```php
<?php
class ApplicationError extends Exception {}

try {
    something();
} catch (ApplicationError $e) { // OK!
    echo "application error";
} catch (Exception $e) {
    echo "general error";
}
```

## Reference

https://secure.php.net/manual/en/class.exception.php
