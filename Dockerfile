FROM php:7.3.2-alpine

# Setup build environment
RUN apk update && \
    apk add --virtual .build-deps --update --no-cache openssl ca-certificates && \
    update-ca-certificates

# Install php-ast
ENV EXT_AST_VERSION 1.0.1
ENV EXT_AST_URL "https://github.com/nikic/php-ast/archive/v${EXT_AST_VERSION}.tar.gz"
RUN mkdir -p /usr/src/php/ext/ast && \
    wget -qO- ${EXT_AST_URL} | tar xz --strip-components=1 -C /usr/src/php/ext/ast && \
    docker-php-ext-configure ast && \
    docker-php-ext-install ast

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Pahout
ENV PAHOUT_VERSION 0.7.0
RUN composer global require "wata727/pahout:${PAHOUT_VERSION}"

RUN apk del .build-deps

WORKDIR /workdir

ENTRYPOINT ["pahout"]
