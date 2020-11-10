FROM debian

ENV WORKDIR /var/www/html
WORKDIR $WORKDIR

ENV PHP_VERSION 7.4

RUN apt-get update && apt-get install -y apache2 php git \
	php-mbstring php-xml php-zip php-mysql vim wget

COPY set-permissions httpd-foreground /usr/local/bin/
RUN chmod u+x /usr/local/bin/httpd-foreground \
	/usr/local/bin/set-permissions \
	&& rm /etc/apache2/sites-enabled/000-default.conf && \
	a2enmod rewrite

COPY 000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN echo "--------- Installing composer ---------" && \
	/usr/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	/usr/bin/php composer-setup.php --install-dir=/bin --filename=composer && \
	/usr/bin/php -r "unlink('composer-setup.php');"

# Download PHP repo certificates
RUN apt-get install -y apt-transport-https lsb-release ca-certificates && \
if [ ! -f /etc/apt/trusted.gpg.d/php.gpg ]; then \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg; \
fi
# Download PHP repo for php7.3 installation
RUN if [ ! -f /etc/apt/sources.list.d/php.list ]; then \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list; \
fi
# Install php 7.3
RUN apt-get update && \
	apt-get -y install php$PHP_VERSION php$PHP_VERSION-mbstring php$PHP_VERSION-xml php$PHP_VERSION-zip php$PHP_VERSION-mysql php$PHP_VERSION-bcmath php$PHP_VERSION-gd php$PHP_VERSION-common php$PHP_VERSION-curl php$PHP_VERSION-intl

RUN a2enmod php$PHP_VERSION

EXPOSE 80

CMD ["/usr/local/bin/httpd-foreground"]
