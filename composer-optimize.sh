#!/usr/bin/env bash

if test -f "/home/dashifen/composer.phar"; then
  /home/dashifen/composer.phar dumpautoload --optimize
else
  composer dumpautoload --optimize
fi