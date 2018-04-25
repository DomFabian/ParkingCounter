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
