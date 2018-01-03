# MultipleCatch

In PHP 7.1 and later, you can specify multiple exceptions in a catch block using the pipe (|) character.
This is useful for when different exceptions from different class hierarchies are handled the same.

## Before

```php
<?php
try {
    hoge();
} catch (A $exn) {
    echo "catch!";
    fuga();
} catch (B $exn) { // MultipleCatch: Specifying `B` and `C` in this catch block avoids redundant catch blocks.
    echo "catch!";
} catch (C $exn) {
    echo "catch!";
}
```

## After

```php
<?php
try {
    hoge();
} catch (A $exn) {
    echo "catch!";
    fuga();
} catch (B | C $exn) { // OK!
    echo "catch!";
}
```

## Reference

https://secure.php.net/manual/language.exceptions.php
