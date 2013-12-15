#!/bin/bash
# Run this once per week

if [[ $EUID -ne 0]] 
  then
    echo "Not root!"
    exit 1
fi
apt-get update
apt-get upgrade
pear upgrade
