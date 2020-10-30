from keras.models import Sequential
from keras.layers import Dense, Conv2D, Flatten
import dataset

model = Sequential()

chars = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん';
# add model layers

model.add(Conv2D(20, kernel_size=(3,3), activation='relu', input_shape=(24, 24, 1)))
model.add(Conv2D(50, kernel_size=(3,3), activation='relu'))
model.add(Flatten())
model.add(Dense(500, activation='relu'))
model.add(Dense(len(chars), activation='softmax'))

# Prepare model for training
model.compile(optimizer='adam', loss='categorical_crossentropy', metrics=['accuracy'])

(x_train, x_test, y_train, y_test) = dataset.generate([char for char in chars])

# Run
model.fit(x_train, y_train, validation_data=(x_test, y_test), epochs=10, shuffle=True)

model.save('model.h5')