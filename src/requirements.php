<?php

$php_version = phpversion();
if (version_compare($php_version, '7.1.0', '<')) {
    fprintf(STDERR, "Pahout requires PHP version 7.1.0 or newer. The installed version is $php_version.\n");
    exit(1);
}

if (extension_loaded('ast')) {
    $ast_version = phpversion('ast');
    if (version_compare($ast_version, '0.1.7', '<')) {
        fprintf(STDERR, "php-ast extension was found. But, Pahout requires php-ast version 0.1.7 or newer. The installed version is $ast_version.\n");
        exit(1);
    }
} else {
    fprintf(STDERR, "php-ast extension could not be found. Pahout requires php-ast version 0.1.7 or newer.\n");
    exit(1);
}

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../autoload.php')) {
    require_once __DIR__ . '/../../../autoload.php';
} else {
    fprintf(STDERR, "Autoload file could not be found. Please run `composer install` at first.\n");
    exit(1);
}
