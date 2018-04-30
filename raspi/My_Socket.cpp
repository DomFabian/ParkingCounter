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

bool My_Socket::conn(std::string address, int port) {
    /* This function takes in a string web address and an int
       port number and returns a Boolean as a success/error code.
       First, a C socket is created if one does not already exist.
       Then, an IP address lookup is performed to convert the
       domain name to an IPv4 address is it is not already an
       IP address. Finally, the socket API connect() method is
       called with all of the properly formatted arguments.
       Returns true if successful connection. Returns false 
       otherwise.
       Pre-conditions: none.
       Post-conditions: socket connection to webserver address
                        specified in the address parameter. */
    
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
         
        // resolve the hostname, if it is not an IP address
        if ((he = gethostbyname(address.c_str())) == NULL) {
            // gethostbyname() failed
            return false;
        }
         
        // cast the h_addr_list to in_addr
        addr_list = (struct in_addr **) he->h_addr_list;
 
        for (int i = 0; addr_list[i] != NULL; i++) {
        // iterate through and look for an entry that is not NULL
            server.sin_addr = *addr_list[i]; 
            break;
        }
    }
     
    // passed an IP address already, not a hostname
    else
        server.sin_addr.s_addr = inet_addr( address.c_str() );
     
    server.sin_family = AF_INET;
    server.sin_port = htons(port);
     
    // try to connect to webserver
    if (connect(sock, (struct sockaddr *)&server, sizeof(server)) < 0) {
        // could not connect to webserver
        return 1;
    }
     
    std::cout << "Connected" << std::endl;
    return true;
}
 
bool My_Socket::send_data(std::string data) {
    /* This function takes in a C++ string of data to send
       to the webserver previously connected to and returns
       a Boolean success/error code. The message will be 
       encoded properly for the C socket API send() method.
       Return true if successfully able to send data and
       false otherwise.
       Pre-conditions: socket connection with remote server should
                       already be made. Will return false if no
                       socket is already connected.
       Post-conditions: data is sent to remote server and response
                        is likely on its way back to the client. */

    return send(sock, data.c_str(), strlen(data.c_str()), 0) > 0;
}
 
std::string My_Socket::receive(int size = 512) {
    /* This function takes an int size parameter (with default value
       of 512 bytes) and returns a C++ string. The size parameter
       serves as the buffer size for the response of the remote
       server, and can be tweaked to brace for a large response.
       Future development could have an iterative way to store very
       large responses, but this is not necessary for this project.
       This function is a wrapper for the C socket API recv() method
       and returns the response sent from the webserver.
       Pre-conditions: socket connection should be made with the
                       remote server; response from remote server
                       should be incoming, otherwise an empty string
                       will be returned.
       Post-conditions: response from remote server processed and 
                        returned in a useable format. */
    
    char buffer[size];
    std::string reply;
     
    // receive reply from the webserver
    if (recv(sock, buffer, sizeof(buffer), 0) < 0) {
    // if the webserver did not respond
        return "";
    }
    
    // convert to string before returning
    reply = buffer;
    return reply;
}
