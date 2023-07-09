#!/usr/bin/env bash

echo "Running postdeploy.sh"
heroku-php-apache2 public/
