#!/bin/bash

set -o errexit
set -o pipefail
set -o nounset

apt update

apt -y install \
    net-tools \
    mysql-client \
    nginx \
    php-fpm \
    php-mysql php-mbstring php-bcmath php-zip php-gd php-curl php-xml \
    pkg-config \
    default-libmysqlclient-dev \

sudo chown -R ubuntu /var/www/html

mkdir /home/ubuntu/myapp
sudo chown -R ubuntu:ubuntu /home/ubuntu/myapp
