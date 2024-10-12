#!/bin/bash

set -o errexit
set -o pipefail
set -o nounset

apt update

apt -y install \
    net-tools \
    mysql-client \
    php \
    php-mysql php-mbstring php-bcmath php-zip php-gd php-curl php-xml \
    php-fpm \
    pkg-config \
    default-libmysqlclient-dev \
    nginx

sudo chown -R ubuntu /var/www/html

mkdir /home/ubuntu/myapp
cd /home/ubuntu/myapp
