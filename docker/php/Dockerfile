FROM php:8.2-apache

# MySQL接続機能と、サーバー名設定
RUN docker-php-ext-install pdo_mysql mysqli \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ⬇️ ここを追加！ /var/www/html へのアクセスを強制的に許可する設定
RUN echo '<Directory /var/www/html>' >> /etc/apache2/apache2.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf \
    && echo '    AllowOverride All' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf