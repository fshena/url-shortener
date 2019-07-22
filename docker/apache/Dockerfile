FROM httpd:2.4

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install --no-install-recommends -y \
        wget \
        vim \
        git \
        unzip \
        composer \
        php-xml \
        php-curl \
        php-json \
        php-mbstring

# Copy apache vhost file to proxy php requests to php-fpm container
COPY ./docker/apache/url-shortener.conf /usr/local/apache2/conf/url-shortener.conf

COPY . /var/www/beat

WORKDIR /var/www/beat

RUN echo "Include /usr/local/apache2/conf/url-shortener.conf" \
    >> /usr/local/apache2/conf/httpd.conf

RUN composer install && composer dump-autoload
