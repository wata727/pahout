# EscapeShellArg

There is a specification that `escapeshellcmd()` doesn't escape when single quotes or double quotes are paired.
This behavior allows attackers to pass arbitrary number of arguments. You must apply `escapeshellarg()` for each argument instead.

## Before

```php
<?php
$filename = 'test.php" "/etc/passwd';
$cmd = "ls \"$filename\"";
$cmd = escapeshellcmd($cmd); // EscapeShellArg: This function allows attackers to pass arbitrary number of arguments.
system($cmd);
```

## After

```php
<?php
$filename = 'test.php" "/etc/passwd';
$filename = escapeshellarg($filename); // OK!
$cmd = "ls $filename";
system($cmd);
```

## Reference

https://secure.php.net/manual/function.escapeshellcmd.php
