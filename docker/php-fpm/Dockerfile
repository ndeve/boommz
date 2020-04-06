FROM php:7.3-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN echo 'alias sf="php app/console"' >> ~/.bashrc

COPY .. /

CMD php -S 0.0.0.0:80 app/public/index.php