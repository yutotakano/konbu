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
    
    # data = cv2.resize(data, (24, 24), interpolation=cv2.INTER_AREA)
    # data = np.reshape(data, (24, 24, 1))
    

    model = load_model(os.path.dirname(os.path.abspath(__file__)) + '/model.h5')

    ret, threshed = cv2.threshold(data, 175, 255, cv2.THRESH_BINARY)

    crop_rows = threshed[~np.all(threshed == 255, axis=1), :]
    cropped_image = crop_rows[:, ~np.all(crop_rows == 255, axis=0)]
    padded = np.pad(cropped_image, ((0, 2), (2, 0)), 'constant')

    letters_data = [padded[:, :15 + 2],
                    padded[:, 15 - 2:30 + 2],
                    padded[:, 30 - 2:45 + 2],
                    padded[:, 45 - 2:]]
    
    output = ''
    for letter_data in letters_data:
        letter_data = cv2.resize(letter_data, (24, 24))
        letter_data = np.reshape(letter_data, (24, 24, 1))
        letter_data = np.array([letter_data], dtype=float) / 255.0
        output += chars[np.argmax(np.round(model.predict(letter_data), 3))]
    
    print(output)

if __name__ == "__main__":
    main()