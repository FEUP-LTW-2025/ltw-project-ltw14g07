<?php function generate_random_token() {
  return bin2hex(openssl_random_pseudo_bytes(32));
} ?>