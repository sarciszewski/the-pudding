#!/bin/bash
# Run this every day
shred /tmp/ip_hash_key.key
php randomString.php 63 > /tmp/ip_hash_key.key