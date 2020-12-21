from cv2 import cv2
import numpy as np
import urllib
import os
import sys
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
from keras.models import load_model


chars = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん';

def main():
    
    if len(sys.argv) != 2:
        exit('Incorrect amount of parameters. Call: python predict.py image.png')
    
    file = sys.argv[1]

    if 'http' in file:
        with urllib.request.urlopen(file) as req:
            nparray = np.asarray(bytearray(req.read()), dtype=np.uint8)
    else:
        stream = open(file, 'rb')
        bytes = bytearray(stream.read())
        nparray = np.array(bytes, dtype=np.uint8)
    data = cv2.imdecode(nparray, cv2.IMREAD_GRAYSCALE)
    if data is None:
        exit('Image is None.')

    model = load_model(os.path.dirname(os.path.abspath(__file__)) + '/model.h5')
    ret, threshed = cv2.threshold(data, 180, 255, cv2.THRESH_BINARY)
    chars_data = [
        threshed[:,6:21],
        threshed[:,21:36],
        threshed[:,36:51],
        threshed[:,51:66]]
    output = ''
    for char_data in chars_data:
        char_data = np.reshape(char_data, (24, 15, 1))
        char_data = np.array([char_data], dtype=float) / 255.0
        output += chars[np.argmax(np.round(model.predict(char_data), 3))]

    print(output)

if __name__ == "__main__":
    main()
