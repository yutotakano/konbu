from cv2 import cv2
import numpy as np
import os
import sys
from keras.models import load_model

# Model is specified: 
model = load_model('model.h5')
print('Loaded model from disk')
# summarize model.
model.summary()