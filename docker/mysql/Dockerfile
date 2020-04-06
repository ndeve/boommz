FROM mysql:5.6

RUN apt-get update && apt-get install -y \
    php \
    wget \
    php-mysql \
    pv


# Install Drush
RUN wget -O drush.phar https://github.com/drush-ops/drush-launcher/releases/download/0.6.0/drush.phar
RUN chmod +x drush.phar
RUN mv drush.phar /usr/local/bin/drush