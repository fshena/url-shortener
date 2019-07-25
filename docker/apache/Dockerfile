FROM httpd:2.4

ENV COMPOSER_ALLOW_SUPERUSER=1

## Copy apache vhost file to proxy php requests to php-fpm container
COPY ./docker/apache/url-shortener.conf /usr/local/apache2/conf/url-shortener.conf

RUN echo "Include /usr/local/apache2/conf/url-shortener.conf" \
    >> /usr/local/apache2/conf/httpd.conf

