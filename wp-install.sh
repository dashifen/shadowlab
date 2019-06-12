#!/usr/bin/env bash

# to install WordPress in the way that we need it for the purposes of
# this plugin, run this script.

# 1. download wordpress
curl -O https://wordpress.org/latest.tar.gz

# 2. unzip wordpress
tar -zxvf latest.tar.gz

# 3. copy wordpress files into the www root of our site
rsync -aq wordpress/ wwwroot

# 4. remove the wordpress directory and archive
rm -rf wordpress
rm -f latest.tar.gz

# 5. remove the "extra" themes and plugins
rm -rf wwwroot/wp-content/themes/twenty*
rm -rf wwwroot/wp-content/plugins/akismet
rm -f wwwroot/wp-content/plugins/hello.php