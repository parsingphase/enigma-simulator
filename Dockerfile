# Version: 0.0.1
FROM php:7.0-cli
MAINTAINER Richard George "richard@phase.org"
RUN apt-get update
VOLUME ["/app"]

# Memory Limit
RUN echo "memory_limit=-1" > $PHP_INI_DIR/conf.d/memory-limit.ini

# Time Zone
RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /root
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.sig');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-setup.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); die(-1); } echo PHP_EOL;"
RUN php composer-setup.php && \
    mv composer.phar /usr/local/bin/composer && \
    composer self-update
RUN apt-get install -y libxslt-dev git curl unzip zlib1g-dev ant && \
    docker-php-ext-install xsl zip

ENTRYPOINT [ "/bin/bash" ]