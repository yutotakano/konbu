<?php
include 'really-simple-captcha.php';

$captcha_instance = new ReallySimpleCaptcha();
$captcha_instance->chars = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん';
$captcha_instance->char_length = 1;

for ($i=0; $i < 50000; $i++) { 
  $word = $captcha_instance->generate_random_word();

  $captcha_instance->generate_image($word, $word);
}
?>