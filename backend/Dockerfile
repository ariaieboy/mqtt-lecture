### step 1 backend
FROM composer:2 AS back
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs
###step 3 webserver
FROM ariaieboy/laraweb:8.3

COPY . /var/www/html
COPY --from=back /var/www/html/vendor /var/www/html/vendor
COPY --from=back /var/www/html/public /var/www/html/public

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
HEALTHCHECK CMD curl --fail http://localhost:8000/are-u-ok || exit 1
