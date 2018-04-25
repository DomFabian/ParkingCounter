#include "My_Socket.h"

int sendWebserverPing(std::string host, std::string path, std::string secret_key) {
    /* This function hits the webserver with a POST request so that it
       knows to make a database entry. 
       Returns -1 if unable to create socket */

    My_Socket c;
    int port = 80;
    
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

    std::cout << "Send message:\n" << msg << std::endl;

    // connect to the host
    if (!c.conn(host, port))
        return -1;
     
    // send the data
    if (!c.send_data(msg))
        return -2;
     
    // receive the response from the webserver
    std::string reply = c.receive(1024);
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
