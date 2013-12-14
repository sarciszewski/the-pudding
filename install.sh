#!/bin/bash
# ============================================================================ #
# Install script: Run this with sudo (or as root) to install dependencies.     #
# Created by: Scott Arciszewski                                                #
# License: WTFPL                                                               #
# ============================================================================ #

if [[ $EUID -ne 0]]; then
  echo "Isn't it a bit stupid to try to install something without using sudo?" 1>&2
  echo "Make sure you read the source code, btw. I might've snuck in an rm -rf /" 1>&2
  exit 1
fi

# ============================================================================ #
# _______________________________ DEPENDENCIES _______________________________ #
apt-get install php5-dev php-pear php5-mcrypt php5-json

# scrypt is used for password hashing
pecl install scrypt

# HTML Purifier is used to clean up most XSS attack attempts
pear channel-discover htmlpurifier.org
pear install hp/HTMLPurifier

# ============================================================================ #


# No rm -rf / here, I just wanted you to read the damn thing ;)
# "Use the source, Luke!" ~ every hacker on IRC since the dawn of the Internet