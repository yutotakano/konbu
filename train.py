from keras.models import Sequential
from keras.layers import Dense, Conv2D, MaxPooling2D, Flatten
import dataset

model = Sequential()

chars = 'あいうえおかきくけこさしすせそたちつてとなにのひふへまみむもやゆよらりん';
# add model layers

model.add(Conv2D(16, kernel_size=(3, 3), activation='relu', input_shape=(24, 15, 1))) 
model.add(MaxPooling2D(pool_size=(2, 2), strides=None))
model.add(Conv2D(64, kernel_size=(3, 3), activation='relu'))
model.add(MaxPooling2D(pool_size=(2, 2), strides=None))
model.add(Flatten())
model.add(Dense(500, activation='relu'))
model.add(Dense(len(chars), activation='softmax'))

# Prepare model for training
model.compile(optimizer='adam', loss='categorical_crossentropy', metrics=['accuracy'])

(x_train, x_test, y_train, y_test) = dataset.generate([char for char in chars])

# Run
model.fit(x_train, y_train, validation_data=(x_test, y_test), epochs=10, shuffle=True)
# Epoch 10/10
# loss: 0.0069 - accuracy: 0.9979 - val_loss: 0.0593 - val_accuracy: 0.9872

model.save('model.h5')

model.summary()