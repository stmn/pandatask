#!/usr/bin/env bash

echo "Running postdeploy.sh"

sudo apt update

sudo apt install -y nodejs

sudo apt install -y npm

npm install

npm run build

heroku-php-apache2 public/
