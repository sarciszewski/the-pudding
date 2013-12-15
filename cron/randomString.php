<?php
/* CLI random String (base 64) */
if($argc < 1) {
  $l = 32;
} else {
  $l = $argv[1];
}
if($l < 1) { $l = 1; }
echo substr(
  base64_encode(
    openssl_random_pseudo_bytes(
      ceil ( $l * 3 / 4)
    )
  ), 0, $l
);