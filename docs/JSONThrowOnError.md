# JSONThrowOnError

PHP 7.3 makes it to easy to handle `json_*` function errors easily.  
Previously, these function didn't throw an exception when an error occurred. But now, it throws `JsonException` if passed `JSON_THROW_ON_ERROR` as a option. This is a better way to handle errors.

## Before

```php
json_decode("{"); // JSONThrowOnError: Encourage to specify JSON_THROW_ON_ERROR option.
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "An error occurred";
}
```

## After

```php
try {
    json_decode("{", false, 512, JSON_THROW_ON_ERROR); // OK!
} catch (JsonException $exn) {
    echo "An error occurred";
}
```

## Reference

- https://secure.php.net/manual/en/function.json-decode.php
- https://secure.php.net/manual/en/function.json-encode.php
