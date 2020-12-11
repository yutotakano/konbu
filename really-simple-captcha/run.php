<?php
include 'siteguard-really-simple-captcha.php';

// Call this file through `php run.php` on the command line.
// Generates 50,000 images labelled "あいうえ00001.png" in the "images" directory (which has to exist).
// Indexing starts at 1 for each combination of 4 characters.

$captcha_instance = new SiteGuardReallySimpleCaptcha();
$captcha_instance->tmp_dir = 'images/';

for ($i=0; $i < 50000; $i++) { 
  $word = $captcha_instance->generate_random_word();

  $captcha_instance->generate_image($word, $word);
}
?>