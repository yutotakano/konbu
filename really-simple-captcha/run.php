<?php
include 'siteguard-really-simple-captcha.php';

// Call this file through `php run.php` on the command line.
// Generates 18,000 images labelled "あ001.png" in the "images" directory (which has to exist).
// Indexing starts at 1 for each character.
mb_internal_encoding('utf-8');

$captcha_instance = new SiteGuardReallySimpleCaptcha();
$captcha_instance->tmp_dir = 'images/';
$captcha_instance->char_length = 1;
$captcha_instance->img_size = [15, 24]; // 72 - 6*2 / 4
$captcha_instance->base = [0, 18];
$chars_jp = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん'; // 36 chars

for ($i = 0; $i < mb_strlen($chars_jp) - 1; $i++) {
    for ($k = 0; $k < 500; $k++) {
      $char_i = mb_substr($chars_jp, $i, 1);
      $captcha_instance->generate_image($char_i, $char_i);
    }
}
?>