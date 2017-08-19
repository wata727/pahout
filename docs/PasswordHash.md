# PasswordHash

The `password_hash()` function added in PHP 5.5 is a wrapper for `crypt()` function.
Also, since it is compatible with existing password hashes, you should use this.

## Before

```php
<?php
$password = crypt('secret text', generate_salt());
```

## After

```php
<?php
$password = password_hash('secret text', PASSWORD_DEFAULT);
```

## Reference

http://php.net/manual/function.password-hash.php
