/*****************************************
** File:    My_Socket.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    04/25/18
** Section: 504
**
**   This file contains the functions for the My_Socket
** class declared in My_Socket.h.
**
***********************************************/

#include "My_Socket.h"

// default constructor
My_Socket::My_Socket() {
    sock = -1;
    port = 0;
    address = "";
}

/**
    Connect to a host on a certain port number
*/
bool My_Socket::conn(std::string address, int port) {
    // create socket
    sock = socket(AF_INET, SOCK_STREAM, 0);
    if (sock == -1) {
    // could not create socket
        return false;
    }
     
    // set up address struct
    if (inet_addr(address.c_str()) == -1) {
        struct hostent *he;
        struct in_addr **addr_list;
         
        // resolve the hostname, its not an ip address
        if ((he = gethostbyname(address.c_str())) == NULL) {
            // gethostbyname() failed
            return false;
        }
         
        // cast the h_addr_list to in_addr , since h_addr_list also has the ip address in long format only
        addr_list = (struct in_addr **) he->h_addr_list;
 
        for (int i = 0; addr_list[i] != NULL; i++) {
        // iterate through and look for an entry that is not NULL
            server.sin_addr = *addr_list[i]; 
            break;
        }
    }
     
    // passed an ip address, not a hostname
    else
        server.sin_addr.s_addr = inet_addr( address.c_str() );
     
    server.sin_family = AF_INET;
    server.sin_port = htons(port);
     
    // try to connect to remote server
    if (connect(sock, (struct sockaddr *)&server, sizeof(server)) < 0) {
        // could not connect to webserver
        return 1;
    }
     
    std::cout << "Connected" << std::endl;
    return true;
}
 
/**
    Send data to the connected host
*/
bool My_Socket::send_data(std::string data) {
    return send(sock, data.c_str(), strlen(data.c_str()), 0) > 0;
}
 
/**
    Receive data from the connected host
*/
std::string My_Socket::receive(int size = 512) {
    char buffer[size];
    std::string reply;
     
    // receive reply from the webserver
    if (recv(sock, buffer, sizeof(buffer), 0) < 0) {
    // if the webserver did not respond
        return "";
    }
    
    // convert to string first
    reply = buffer;
    return reply;
}
