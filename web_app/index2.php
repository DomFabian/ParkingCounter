<?php
/*****************************************
** File:    index.php
** Project: CSCE 315 Project 1, Spring 2018
** Author:  Dominick Fabian
** Date:    02/21/18
** Section: 504
** E-mail:  dominick@tamu.edu 
**
**   This file contains the index landing page for the
** 315 project 1. All GET and POST requests coming from
** the PedestrianCounter will be handled by this script.
** This file relies on the fact that the Arduino can send a 
** specially-crafted HTTP POST request to the server that
** no on else will know how to create.
**
***********************************************/

    include('MainPageStats.php');

    $tableName = '`CarDatabase`';

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        // if just visiting site, not sending data to it
        
?>
<h1>Howdy</h1>

If you're an Arduino, POST your key.<br>

<form action="index.php" method="post">
    Enter your key:<br>
    <input type="text" name="key" size="10" maxlength="30"><br>
    <input type="submit" name="submit">
</form>

<?php
    }
    else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // if attempting to send data to server

        $receivedKey = $_POST["key"];
        $statusCode = handleArduinoPing($receivedKey);

        // send the Arduino a simple message about its POST request
        echo ($statusCode == 1) ? "success" : "failure";
    }
?>
