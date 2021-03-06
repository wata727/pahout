## v0.7.0 (2019-03-06)

### Enhancements

- Add `--extensions` option ([#51](https://github.com/wata727/pahout/pull/51))

### Others

- CI against PHP 7.1.26, 7.2.15 and 7.3.2 ([#52](https://github.com/wata727/pahout/pull/52))

## v0.6.1 (2019-01-29)

### Bugfix

- Allow to parse PHP-standardized version ([#49](https://github.com/wata727/pahout/pull/49))

## v0.6.0 (2018-12-30)

### Enhancements

- Add new JSONThrowOnError tool ([#40](https://github.com/wata727/pahout/pull/40))
- Add new DeclareStrictTypes tool ([#43](https://github.com/wata727/pahout/pull/43))
- Add new IsCountable tool ([#44](https://github.com/wata727/pahout/pull/44))

### Changes

- Make Pahout aware of runtime version ([#41](https://github.com/wata727/pahout/pull/41))
- Remove RedundantTernaryOperator ([#46](https://github.com/wata727/pahout/pull/46))
- Remove LooseReturnCheck ([#47](https://github.com/wata727/pahout/pull/47))

### Others

- CI against PHP 7.1.25, 7.2.13 and 7.3.0 ([#38](https://github.com/wata727/pahout/pull/38))
- Pahout requires php-ast >= 0.1.7 ([#39](https://github.com/wata727/pahout/pull/39))
- Add example messages to JSONThrowOnError documentation ([#42](https://github.com/wata727/pahout/pull/42))
- Update utils/ast.php ([#45](https://github.com/wata727/pahout/pull/45))

## v0.5.1 (2018-11-03)

- Allow to use php-ast v1.0.0 ([#37](https://github.com/wata727/pahout/pull/37))

## v0.5.0 (2018-10-14)

From this release, it updates the php-ast AST version to 60 (Previously this is 40). Please update your php-ast extension version.

### Enhancements

- Add new `--only-tools` option ([#33](https://github.com/wata727/pahout/pull/33))

### Changes

- Bump php-ast AST version to 60 ([#35](https://github.com/wata727/pahout/pull/35))

### Others

- CI against PHP 7.2.2 and 7.1.14 ([#31](https://github.com/wata727/pahout/pull/31))
- CI against PHP 7.2.7 and 7.1.19 ([#32](https://github.com/wata727/pahout/pull/32))
- CI against PHP 7.1.23 and 7.2.11 ([#36](https://github.com/wata727/pahout/pull/36))

## v0.4.0 (2018-01-07)

Annotations were introduced in this release. Please see the [README](https://github.com/wata727/pahout/blob/0.4.0/README.md#annotation) for details.

### Enhancements

- Introduce annotations to control hints ([#21](https://github.com/wata727/pahout/pull/21))
- Add AmbiguousReturnCheck ([#24](https://github.com/wata727/pahout/pull/24))
  - NOTE: The tool name changed to LooseReturnCheck on [#28](https://github.com/wata727/pahout/pull/28)
- Add UnneededRegularExpression ([#25](https://github.com/wata727/pahout/pull/25))

### Changes

- Change the message of SyntaxError ([#23](https://github.com/wata727/pahout/pull/23))
- Revise messages and documentations ([#26](https://github.com/wata727/pahout/pull/26))
- Rename tool names ([#28](https://github.com/wata727/pahout/pull/28))

### Bugfix

- Handling array destructuring for DuplicateKey ([#27](https://github.com/wata727/pahout/pull/27))

### Others

- CI against PHP 7.2 ([#19](https://github.com/wata727/pahout/pull/19))
- CI against PHP 7.1.12 and 7.2 ([#20](https://github.com/wata727/pahout/pull/20))
- Revise README ([#22](https://github.com/wata727/pahout/pull/22))

## v0.3.0 (2017-12-21)

We relaxed dependencies from this release. There is no change in the behavior of the analysis.

### Enhancements

- Parses `.php-version` to change default php_version ([#16](https://github.com/wata727/pahout/pull/16))

### Changes

- Relax composer requirements ([#18](https://github.com/wata727/pahout/pull/18))

## v0.2.0 (2017-11-05)

In this release we have added 4 tools. These tools will make your code better :)

### Enhancements

- Add RedundantTernaryOperator ([#9](https://github.com/wata727/pahout/pull/9))
- Add DuplicateKey ([#12](https://github.com/wata727/pahout/pull/12))
- Add UnreachableCatch ([#13](https://github.com/wata727/pahout/pull/13))
- Add DuplicateCaseCondition ([#14](https://github.com/wata727/pahout/pull/14))

### BugFix

- Use Howdah for DuplicateKey ([#15](https://github.com/wata727/pahout/pull/15))

### Others

- CI Settings ([#1](https://github.com/wata727/pahout/pull/1))
- Fix tests ([#10](https://github.com/wata727/pahout/pull/10))
- Add parser for development ([#11](https://github.com/wata727/pahout/pull/11))

## v0.1.1 (2017-08-22)

Fix installation when using `composer global require`

## v0.1.0 (2017-08-21)

First release
