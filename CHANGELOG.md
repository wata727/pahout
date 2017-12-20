## v0.3.0 (2017-12-21)

We relaxed dependencies from this release. There is no change in the behavior of the analysis.

### Changes

- Relax composer requirements ([#18](https://github.com/wata727/pahout/pull/18))

### Enhancements

- Parses `.php-version` to change default php_version ([#16](https://github.com/wata727/pahout/pull/16))

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
