<?php
include 'siteguard-really-simple-captcha.php';

// Call this file through `php run.php` on the command line.
// Generates 18,000 images labelled "あ001.png" in the "images" directory (which has to exist).
// Indexing starts at 1 for each character.
mb_internal_encoding('utf-8');

$captcha_instance = new SiteGuardReallySimpleCaptcha();
$captcha_instance->tmp_dir = 'images/';
$captcha_instance->char_length = 4;
$captcha_instance->img_size = [72, 24]; 
$captcha_instance->base = [6, 18];
$chars_jp = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん'; // 36 chars

for ($k = 0; $k < 10000; $k++) {
  $word = $captcha_instance->generate_random_word();
  $captcha_instance->generate_image($word, $word);
}
?>