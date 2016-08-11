FROM ubuntu:14.04

MAINTAINER Luca Mattivi <luca@smartdomotik.com>

ENV PROJECT_PATH /var/www
ENV DEBIAN_FRONTEND noninteractive
ENV HTPASSWD='foo:$apr1$odHl5EJN$KbxMfo86Qdve2FH4owePn.'

RUN apt-get update && apt-get upgrade -y --force-yes

# Use PHP5.6 instead of PHP5.5 (need to manually add repo key)
RUN apt-get install -y --force-yes software-properties-common python-software-properties
RUN add-apt-repository ppa:ondrej/php5-5.6
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C

# Utilities, Apache, PHP, and supplementary programs
RUN apt-get update && apt-get install -y --force-yes \
    npm \
    git \
    nano \
    wget \
    htop \
    apache2 \
    libapache2-mod-php5 \
    curl \
    php5-cgi \
    php5-mysql \
    php5-intl \
    php5-mcrypt \
    gettext \
    php5-redis

#RUN ln -s "$(which nodejs)" /usr/bin/node

# Apache mods
RUN a2enmod php5
RUN a2enmod rewrite
RUN a2enmod expires

# PHP.ini file: enable <? ?> tags and quieten logging
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php5/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php5/apache2/php.ini

# Apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV TERM xterm

# Apache2 conf
RUN echo "ServerName localhost" | sudo tee /etc/apache2/conf-available/fqdn.conf
RUN a2enconf fqdn

# Set the timezone.
RUN sudo echo "Europe/Paris" > /etc/timezone
RUN sudo dpkg-reconfigure -f noninteractive tzdata

# Port to expose
EXPOSE 80

# VirtualHost
COPY apache-vhost.conf /etc/apache2/sites-available/rancher.conf
RUN a2dissite 000-default
RUN a2ensite rancher


# must copy project before composer for artisan
COPY . $PROJECT_PATH
WORKDIR $PROJECT_PATH

# composer
#RUN chmod +x $PROJECT_PATH/artisan
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN composer update --lock
RUN composer install --no-interaction --optimize-autoloader


# Folder permissions
RUN chown -R www-data:www-data $PROJECT_PATH
RUN chown -R www-data:root logs/

# Starting scripts
RUN chmod +x start.sh
CMD ./start.sh