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
