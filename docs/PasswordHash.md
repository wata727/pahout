# PasswordHash

The `password_hash()` function added in PHP 5.5 is a wrapper for the `crypt()` function.
Also, since it is compatible with existing password hashes, you should use this.

## Before

```php
<?php
$password = crypt('secret text', generate_salt()); // PasswordHash: Use of `password_hash()` is encouraged.
```

## After

```php
<?php
$password = password_hash('secret text', PASSWORD_DEFAULT); // OK!
```

## Reference

https://secure.php.net/manual/function.password-hash.php
