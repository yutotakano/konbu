from cv2 import cv2
import numpy as np
import os
from keras.utils import to_categorical
from sklearn.model_selection import train_test_split

def generate(chars):
    # Initialise empty list of lists
    chars_data = []
    chars_labels = []

    # Start at -1 since I add one when a new character begins
    i = -1
    for filename in os.listdir("really-simple-captcha/images"):
    
        # for each image: read the image, resize and append it to the dataset, and add the appropriate label for it
        # Also use this workaround instead of imread() because ot UTF-8 filename handling in Windows.
        stream = open(os.path.join("really-simple-captcha/images", filename), 'rb')
        bytes = bytearray(stream.read())
        nparray = np.array(bytes, dtype=np.uint8)
        data = cv2.imdecode(nparray, cv2.IMREAD_GRAYSCALE)

        if data is not None:
            # If first occurrence, add category.
            i += 1 if '00001' == filename[1:6] else 0

            # Apply threshold    
            ret, threshed = cv2.threshold(data, 180, 255, cv2.THRESH_BINARY)

            # Already 24x15, so just add channel dimension, and append
            threshed = np.reshape(threshed, (24, 15, 1))
            chars_data.append(threshed)
            chars_labels.append(i)
    
    # create an array of character data, divide the matrix by 255.0 to make values for each pixel within 0 and 1
    data = np.array(chars_data) / 255.0

    # make a binary matrix with each item in the label
    labels = to_categorical(chars_labels)

    return train_test_split(data, labels, test_size=0.1)