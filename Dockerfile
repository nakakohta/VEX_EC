FROM php:8.2-apache

# MySQL接続機能と、サーバー名設定
RUN docker-php-ext-install pdo_mysql mysqli \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf