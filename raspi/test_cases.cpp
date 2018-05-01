/*****************************************
** File:    test_cases.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Author:  Dominick Fabian
** Email:   dominick@tamu.edu
** Date:    05/01/18
** Section: 504
**
**   This file contains the test case for the function in 
** webserverPing.cpp. There is only one function there.
**
***********************************************/

#include <iostream>
#include <string>

void test_sendWebserverPing() {
    std::string good_host_1 = "projects.cse.tamu.edu";
    std::string bad_host_1 = "edu.tamu.cse.projects";
    std::string good_path_1 = "index.php";
    std::string bad_path_1 = "not/sure/where/to/go.php";
    std::string good_key = "ourSecretArduinoKey";
    std::string bad_key = "idkTheKey";

    // should return 1
    int ret1 = sendWebserverPing(good_host_1, good_path_1, good_key);

    // should return -1
    int ret2 = sendWebserverPing(bad_host_1, good_path_1, good_key);

    // should return -1
    int ret3 = sendWebserverPing(good_host_1, bad_path_1, good_key);

    // should return -1
    int ret4 = sendWebserverPing(good_host_1, good_path_1, bad_key);

    // print the results to the screen
    std::cout << "test_sendWebserverPing() test ";
    if (ret1 == 1 && ret2 == -1 && ret3 == -1 && ret4 == -1)
        std::cout << "passed";
    else
        std::cout << "failed";
    std::cout << std::endl;
         
    
}

void test_all() {
    test_sendWebserverPing();
}

int main() {
    std::cout << "Testing all module functions:" << std::endl;
    test_all();
    return 0;
}