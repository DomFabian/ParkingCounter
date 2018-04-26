/*****************************************
** File:    My_Socket.h
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    04/25/18
** Section: 504
**
**   This file contains the class declaration for My_Socket,
** a wrapper class for the C/C++ socket API. This class will
** make sending and receiving data from a webserver quick and
** easy. It is generic enough to be applied to other projects
** too, which is neat.
** Code outline found at: 
** https://www.binarytides.com/code-a-simple-socket-client-class-in-c/
**
***********************************************/

// C-style includes
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <netdb.h>

// C++-style includes
#include <string>
#include <iostream>
#include <sstream>

class My_Socket {
private:
    int sock;
    std::string address;
    int port;
    struct sockaddr_in server;
     
public:
    My_Socket();
    bool conn(std::string, int);
    bool send_data(std::string data);
    std::string receive(int);
};
