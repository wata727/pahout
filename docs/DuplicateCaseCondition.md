# DuplicateCaseCondition

In switch statement, when duplicate case conditions are included, the later is ignored.

## Before

```php
<?php
switch ($a) {
    case 1:
        echo "hoge";
        break;
    case 1: // DuplicateCaseCondition: Duplicate case condition found.
        echo "fuga";
        break;
    default:
        echo "meow";
        break;
}
```

## After

```php
<?php
switch ($a) {
    case 1:
        echo "hoge";
        break;
    case 2: // OK!
        echo "fuga";
        break;
    default:
        echo "meow";
        break;
}
```

## Reference

https://secure.php.net/manual/en/control-structures.switch.php
