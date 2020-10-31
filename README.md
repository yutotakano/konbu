# konbu

A simple machine learning OCR model to solve Hiragana versions of really-simple-captcha.

This model was created to supplement [yutotakano/LINEbot-SJO](https://github.com/yutotakano/LINEbot-SJO), which requires the web scraper to solve a simple captcha in order to get to the desired page. The captcha consists of four hiragana characters, and is available on the target server as a temporary PNG. Konbu splits the image into four sections to individually OCR them.

Inside `/really-simple-captcha` are the modified versions of [BcChloe-simple-captcha](https://github.com/ifNoob/BcChloe-simple-captcha), which itself is a modified version of [really-simple-captcha](https://ja.wordpress.org/plugins/really-simple-captcha/). While not included in the repo due to the sheer size, running `php run.php` will generate 50,000 images of single-character hiraganas.

The model was trained on the above dataset using Keras, and the final output is `model.h5`. `predict.py` takes an input image of four characters (as per the captcha), and outputs the result to stdout.

## Demo

![Image](https://i.imgur.com/vnladYn.png)