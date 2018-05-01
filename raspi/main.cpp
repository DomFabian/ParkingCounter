/*****************************************
** File:    main.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    05/01/18
** Section: 504
**
**   This file contains the main function to relay the
** messages from the Arduino over the serial communication
** to the webserver, using the C socket API.
**
** Note: this file requires that you compile all .cpp files.
** Note: this file requires wiringPi libraries.
**
***********************************************/

/* IMPORTANT NOTE: in order to use the below library, you
   must download and install wiringPi from GitHub. This
   library allows you to communicate with the Arduino Uno
   over the serial connection. Download and install details
   can be found at: http://wiringpi.com/download-and-install. */
#include <wiringPi.h>

#include <string>

int main() {
    // first initialize the wiringPi for GPIO
    wiringPiSetup();

    // define some variables
    const int relay_pin = 0;
    const double cooldown_time_microsec = 1500000;
    const std::string host = "projects.cse.tamu.edu";
    const std::string path = "domfabian1/index.php";
    const int port = 80;

    pinMode(relay_pin, INPUT);

    for (;;) {
    // constantly check to see if something is by the unit
    
        if (digitalRead(relay_pin) == HIGH) {
            // try to send a message to the webserver
            if (sendWebserverPing(host, path, port) < 0)
                std::cout << "Error: unsuccessful database entry" << std::endl;
        }
        // take a break so we don't see the same car multiple times
        delayMicroseconds(cooldown_time_microsec);
    }

    return 0;
}
