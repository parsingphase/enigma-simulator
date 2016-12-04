# Version: 0.0.1
FROM php:5.6-cli
MAINTAINER Richard George "richard@phase.org"
RUN apt-get update
VOLUME ["/app"]

# Memory Limit
RUN echo "memory_limit=-1" > $PHP_INI_DIR/conf.d/memory-limit.ini

# Time Zone
RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini

WORKDIR /root
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.sig');"
RUN pwd
RUN ls
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-setup.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer self-update

RUN apt-get install -y libxslt-dev git curl unzip zlib1g-dev
RUN docker-php-ext-install xsl zip

ENTRYPOINT [ "/bin/bash" ]