# ParkingCounter
This project includes code for an Arduino to gather data on parking lots and a nice web API.

## Setting up the Arduino
To set up the Arduino, verify and deploy the file raspi/main/main.ino to the Arduino Uno.
Whenever power is supplied to the Arduino, the program will automatically begin running.

## Setting up the Raspberry Pi
1. To set up the Raspberry Pi, first ensure that the Pi has Git and G++. If not, run
`apt-get install git && apt-get install g++` as root to install Git and G++.
2. Next, ensure that wiringPi is installed on the Pi. See 
[http://wiringpi.com/download-and-install/](http://wiringpi.com/download-and-install/).
3. Then, clone the ParkingCounter repository to the Pi by running
`git clone https://github.com/DomFabian/ParkingCounter.git`.
    * *If you prefer to save a little memory on the Pi, you can then run*
`rm -rf arduino/ web_app/` *to get rid of unneeded code.*
4. Finally, compile and run the source code with `g++ -o pi_code *.cpp && ./pi_code`
