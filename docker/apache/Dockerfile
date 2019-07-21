FROM httpd:2.4

# Copy apache vhost file to proxy php requests to php-fpm container
COPY url-shortener.conf /usr/local/apache2/conf/url-shortener.conf

RUN echo "Include /usr/local/apache2/conf/url-shortener.conf" \
    >> /usr/local/apache2/conf/httpd.conf
