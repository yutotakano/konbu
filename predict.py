from cv2 import cv2
import numpy as np
import urllib
import os
import sys
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
from keras.models import load_model


chars = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん';

def crop_minAreaRect(img, points):
    highest_point = min([point[1] for point in points])
    lowest_point = max([point[1] for point in points])
    leftmost_point = min([point[0] for point in points])
    rightmost_point = max([point[0] for point in points])

    # rgb = cv2.cvtColor(img, cv2.COLOR_GRAY2RGB)
    # cv2.imshow('image2', cv2.rectangle(rgb, (leftmost_point, lowest_point), (rightmost_point, highest_point), (0, 255, 255), 1))
    # cv2.waitKey(1500)

    return img[highest_point:lowest_point + 1, leftmost_point:rightmost_point + 1]
    
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

    threshed = np.pad(threshed, ((1,1), (1,1)), 'constant', constant_values=255)
    contours, hierarchy = cv2.findContours(threshed, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    # cv2.namedWindow('image', cv2.WINDOW_NORMAL)
    # cv2.resizeWindow('image', 720,240)
    # cv2.namedWindow('image2', cv2.WINDOW_NORMAL)
    # cv2.resizeWindow('image2', 720,240)
    contours.sort(key=(lambda x: cv2.boundingRect(x)[0])) 
    # rgb = cv2.cvtColor(threshed, cv2.COLOR_GRAY2RGB)
    temp_points = [] # cumulative list of points for characters
    letters_data = []
    for index, c in enumerate(contours[1:]): # include last one, but don't do anything on it
        x, y, w, h = cv2.boundingRect(c)

        temp_points.append((x, y))
        temp_points.append((x+w, y+h))
        
        # cv2.imshow('image', cv2.rectangle(rgb, (x,y), (x+w, y+h), (255, 255, 0), 1))
        # cv2.waitKey(1000)

        if not temp_points:
            continue

        if index != len(contours[1:]) - 1:
            # Next bounding box
            n_x, n_y, n_w, n_h = cv2.boundingRect(contours[1:][index + 1])
    
            end_of_newpoint_within_some_previous = any([(n_x + n_w) < p[0] for p in temp_points])
            min_of_temps_x = min([item[0] for item in temp_points])
            if end_of_newpoint_within_some_previous or (n_x + n_w - min_of_temps_x <= 19):
                continue

        cropped = crop_minAreaRect(threshed, temp_points)
        (cropped_h, cropped_w) = np.shape(cropped)
        croppeds = []
        if cropped_w > 24:
            croppeds.append(cropped[:, :round(cropped_w/2)])
            croppeds.append(cropped[:, cropped_w-round(cropped_w/2):])
        else:
            croppeds.append(cropped)
        
        for crop in croppeds:
            (crop_h, crop_w) = np.shape(crop)
            pad_t = round ((24 - crop_h) / 2)
            pad_b = 24 - crop_h - pad_t
            pad_l = round ((24 - crop_w) / 2)
            pad_r = 24 - crop_w - pad_l
            padded = np.pad(crop, ((pad_t, pad_b), (pad_l, pad_r)), 'constant', constant_values=255)
            letters_data.append(padded)
            # cv2.imshow('aa', padded)
            # cv2.waitKey(2000)
        temp_points = []
    
    output = []
    for letter_data in letters_data:
        letter_data = cv2.resize(letter_data, (24, 24))
        letter_data = np.reshape(letter_data, (24, 24, 1))
        letter_data = np.array([letter_data], dtype=float) / 255.0
        percentages = np.round(model.predict(letter_data), 3)
        output.append((chars[np.argmax(percentages)], np.max(percentages)))
    
    if len(output) > 4:
        output_copy = [a for a in output]
        output_copy.sort(key=lambda x: (-1)*x[1])
        fourth_largest = output_copy[3][1]

        output = filter(lambda x: x[1] >= fourth_largest, output)

    print(''.join([a[0] for a in output]))

if __name__ == "__main__":
    main()
