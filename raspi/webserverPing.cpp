/*****************************************
** File:    webserverPing.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    04/25/18
** Section: 504
**
**   This file contains the function used by the Raspberry
** Pi to send a message to the webserver. This will be used
** to send the HTTP POST requests needed for entries to be
** made into the database.
**
***********************************************/

#include "My_Socket.h"

int sendWebserverPing(std::string host, std::string path, std::string secret_key) {
    /* This function hits the webserver with a POST request so that it
       knows to make a database entry. 
       Returns -1 if unable to create socket 
       Ths function takes a C++ string for the hostname, path, and secret key used
       by the Raspberry Pi client for use in creating an HTTP POST request. This 
       POST request has the effect of triggering a database insertion on the server
       side.
       Returns 1 if successful connection and request made.
       Returns -1 if unable to connect to webserver.
       Returns -2 if unable to send the data to webserver.
       Returns -3 if the webserver response was an empty string.
       Pre-conditions: My_Socket.h is included; compiled with My_Socket.cpp.
       Post-conditions: HTTP POST request sent and database entry made. */

    My_Socket c;
    int port = 80; // for plain old HTTP
    
    // create the message
    // payload of actual data being POSTed to webserver
    std::string payload = "key=" + secret_key + "&submit=Submit+Query";

    // create a variable to hold the length of the payload
    std::ostringstream oss;
    oss << payload.length();
    std::string payload_length = oss.str();

    // create a POST request in HTTP 1.1 format
    std::string msg = "";
    msg += ("POST /" + path + " HTTP/1.1\r\n");
    msg += ("Host: " + host + "\r\n");
    msg += "Connection: close\r\n";
    msg += "Content-Type: application/x-www-form-urlencoded\r\n";
    msg += ("Content-Length: " + payload_length + "\r\n");
    msg += "\r\n";
    msg += payload;

    std::cout << "Sent message:\n" << msg << std::endl;

    // connect to the host
    if (!c.conn(host, port))
        return -1;
     
    // send the data
    if (!c.send_data(msg))
        return -2;
     
    // receive the response from the webserver
    int numBytes = 1024;
    std::string reply = c.receive(numBytes);
    if (reply == "")
        return -3;

    std::cout << reply << std::endl;

    return 1;
}

int main() {

    std::string host = "projects.cse.tamu.edu";
    std::string path = "domfabian1/index.php";
    std::string secret_key = "ourSecretArduinoKey";

    int ret = sendWebserverPing(host, path, secret_key);
    std::cout << "Our function returned: " << ret << std::endl;
    return 0;
}
