<!-- ADMIN PASSWORD: 'TAMU2019' -->

<head>
    <style>
        body {background-color: #67809F;}
        h1, h2   {
            color: white;
            text-align: center;
        }
        p    {
            font-size: large;
        }
        .main {
            margin-top: 50px;
        }
        .timeframe-selector {
            font-size: 1.5em;
        }
        .error {
            color: red;
        }
        .entry {
            border-color: white;
            border-style: solid;
        }
    </style>
</head>

<button onclick="location.href='index.php'">Home</button><br>

<?php
    include('mainPageStats.php');

    if(isset($_POST['new_entry'])) {
        // New entry
        $num_people = $_POST['num_people'];
        $timestamp = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['timestamp'])));
        if(add_entry($num_people, $timestamp)) {
            echo "Entry successfully added.<br>";
        } else {
            echo "Error entering entry.<br>";
        }
    }

    if(isset($_POST['admin_password'])) {
        // Password typed in.
        if($_POST['admin_password'] == "TAMU2019") {
            // Correct password.
            echo "<form action='' method='post'>
                    <p>Add manual entry:<br>
                    <div class='entry'>
                        <label>Number of people: <input type='text' name=\"num_people\"> 
                        <label>Date and Time: </label> <input type='datetime-local' name=\"timestamp\">
                        <input type='submit' name='new_entry'>
                    </div>
                  </form>";
        } else {
            // Incorrect password.
            echo "<p class='error'>Incorrect Password.<br></p>";
            echo "<form action='' method='post'><p>Please enter admin password: <input type='password' name='admin_password'><input type='submit' name='submit'></p></form>";
        }
    } else {
        // Haven't typed in password yet.
        echo "<form action='' method='post'><p>Please enter admin password: <input type='password' name='admin_password'><input type='submit' name='submit'></p></form>";
    }
?>