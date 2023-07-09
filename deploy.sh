#!/usr/bin/env bash

echo "Running postdeploy.sh"

apt update

apt install -y nodejs

apt install -y npm

npm install

npm run build

heroku-php-apache2 public/
