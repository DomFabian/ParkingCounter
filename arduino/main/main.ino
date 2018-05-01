/*****************************************
** File:    main.ino
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    04/26/18
** Section: 504
**
**   This file contains the Arduino code that will continuously
** read from the ultrasonic sensors and determine when a car has
** driven past in a certain direction. When it determines that a
** car has driven past, it will send a message over the serial
** connection to the Raspberry Pi.
**
***********************************************/

// define pins numbers
const int trig_pin[2] = {7, 8}; // ultrasonic sensor 1
const int echo_pin[2] = {9, 10}; // ultrasonic sensor 2
const int relay_pin = 6;

// define some variables
const int num_sensors = 2;
double duration[num_sensors];
int distance[num_sensors];
const double speed_of_sound_constant = 0.034;
const double time_differential_microsec = 10.0;
const double distance_threshold = 2000.0;

/* this design will use a finite state machine logic with 
   3 different states:
   0 - no motion detected.
   1 - motion on sensor 1 has been detected.
   2 - motion on sensor 2 has been detected within the valid
       time differential (AKA car passed). */
int state = 0;

bool car_has_passed() {
    // uses the global variable distance and state
    if (distance[0] < distance_threshold)
        state = 1;
    delayMicroseconds(time_differential_microsec);
    if (state == 1 && distance[1] < distance_threshold)
        state = 2;
    return state == 2;
}

void setup() {
    for (int i = 0; i < num_sensors; i++) {
        // set the trig_pins as outputs
        pinMode(trig_pin[i], OUTPUT);

        // set the echo_pins as inputs
        pinMode(echo_pin[i], INPUT);
    }

    // start the serial connection
    Serial.begin(9600);
}

void loop() {

    digitalWrite(relay_pin, LOW);

    for (int i = 0; i < num_sensors; i++) {
        // clear the trig_pins
        digitalWrite(trig_pin[i], LOW);
        delayMicroseconds(2);

        // set the trig_pin to HIGH for 10 micro seconds
        digitalWrite(trig_pin[i], HIGH);
        delayMicroseconds(10);
        digitalWrite(trig_pin[i], LOW);

        // read the sound wave travel time in microseconds
        duration[i] = pulseIn(echo_pin[i], HIGH);

        // calculate the distance in centimeters (range of 4000cm)
        distance[i] = duration[i] * speed_of_sound_constant / 2.0;
    }

    if ( car_has_passed() ) {
        // signal the Pi on the relay pin
        digitalWrite(relay_pin, HIGH);
        delayMicroseconds(10);
    }
    
}
