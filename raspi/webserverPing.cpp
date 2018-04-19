#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>

#include <string>
#include <iostream>
#include <sstream>

int sendWebserverPing(std::string host, std::string path, std::string secret_key) {
    /* This function hits the webserver with a POST request so that it
       knows to make a database entry. 
       Returns -1 if unable to create socket */

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

    std::cout << "Send message:\n\n" << msg << std::endl;

    // prepare to create socket
    int sockfd, port, n;
    struct sockaddr_in serv_addr;
    struct hostent *server;
    char buffer[256];

    // create socket
    sockfd = socket(AF_INET, SOCK_STREAM, 0);

    if (sockfd < 0) {
        // error opening socket
        return -1;
    }

    // get the IP address of the hostname
    server = gethostbyname(host.c_str());

    if (server == NULL) {
        // no such host by that name
        return -2;
    }

    bzero((char*)&serv_addr, sizeof(serv_addr));
    serv_addr.sin_family = AF_INET;
    bcopy((char*)server->h_addr, (char*)&serv_addr.sin_addr.s_addr, server->h_length);
    serv_addr.sin_port = htons(port);

    if (connect(sockfd, (struct sockaddr*)&serv_addr, sizeof(serv_addr)) < 0) {
        //error connecting to webserver
        return -3;
    }

    // send the POST request
    n = write(sockfd, msg.c_str(), msg.length());

    if (n < 0) {
         //error writing to socket
         return -4;
    }

    // empty the buffer
    bzero(buffer, 256);

    // put the server's response in the buffer
    n = read(sockfd, buffer, 255);
    
    if (n < 0) {
        // error reading from socket
        return -5;
    }

    printf("%s\n", buffer);

    // close the socket
    close(sockfd);

    // success case
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
