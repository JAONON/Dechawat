FROM php:8.1-apache

# ติดตั้ง pdo_mysql เพื่อเชื่อมต่อ MySQL
RUN docker-php-ext-install pdo pdo_mysql

# (ไม่บังคับ) เปิด mod_rewrite ถ้าใช้ Laravel หรือมี .htaccess
RUN a2enmod rewrite

WORKDIR /var/www/html
