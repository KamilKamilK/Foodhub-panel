FROM lsisoftware/php:7.4-apache

MAINTAINER LSI Software <dev@lsisoftware.pl>

# Apache
ADD ./.docker/app/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Node JS
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get install -y nodejs \
    && curl -L https://npmjs.org/install.sh | sh \
    && apt-get -y --no-install-recommends install wget \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# run as www-data && set chown for www-data which solves permission issues
#RUN usermod -u 1000 www-data
WORKDIR /var/www

COPY . ./

RUN chown -R www-data:www-data /var/www

EXPOSE 80
