# SyntaxError

Pahout treats syntax errors as one type of hint.
This is because when a parse error occurs, it does not affect the confirmation of other files.

## Before

```php
<?php
funcion f[] ( ... ) // SyntaxError: syntax error, unexpected 'f' (T_STRING)
```

## After

Let's write the correct PHP script.

## Reference
