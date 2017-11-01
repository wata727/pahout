# DuplicateCaseCondition

In the switch statement that does not fall through, if the same condition is included, the latter is ignored. Even if you use fall through, writing multiple same condition is redundant.

NOTE: The switch statement uses the equal comparison operator. As a result, the same conditions may result, but Pahout does not detect it.

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
