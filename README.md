# konbu

A simple machine learning OCR model to solve Hiragana versions of really-simple-captcha.

This model was created to supplement [yutotakano/LINEbot-SJO](https://github.com/yutotakano/LINEbot-SJO), which requires the web scraper to solve a simple captcha in order to get to the desired page. The captcha on the target server consists of four hiragana characters, and is available on the server as a temporary PNG for about 30 minutes. I managed to find the same WordPress plugin used to generate those captchas, so I modified the code to produce the required dataset.

See the readme inside `/really-simple-captcha` for details of generating the dataset of 40,000 images and the script used.

The model was trained on the above dataset using Keras, and the final output is `model.h5`. `predict.py` takes an input image of four characters (as per the captcha), and outputs the result to stdout.

## Neural Network Summary

```
Layer (type)                 Output Shape              Param #
=================================================================
conv2d (Conv2D)              (None, 22, 13, 16)        160
_________________________________________________________________
max_pooling2d (MaxPooling2D) (None, 11, 6, 16)         0
_________________________________________________________________
conv2d_1 (Conv2D)            (None, 9, 4, 64)          9280
_________________________________________________________________
max_pooling2d_1 (MaxPooling2 (None, 4, 2, 64)          0
_________________________________________________________________
flatten (Flatten)            (None, 512)               0
_________________________________________________________________
dense (Dense)                (None, 500)               256500
_________________________________________________________________
dense_1 (Dense)              (None, 36)                18036
=================================================================
Total params: 283,976
Trainable params: 283,976
Non-trainable params: 0
_________________________________________________________________
```

# Accuracy

```
loss: 0.0069 - accuracy: 0.9979 - val_loss: 0.0593 - val_accuracy: 0.9872
```

As far as it has been tested with manual input, it has returned the correct result for all inputs.

## Screenshot

![Screenshot](https://i.imgur.com/QV6rD5m.png)