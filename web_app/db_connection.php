<?php

class DbConnection
{
    var $conn;
    var $debug;

    var $db="database.cse.tamu.edu";
    var $dbname="domfabian1";
    var $user="domfabian1";
    var $pass="\$\$\$pass\$\$\$word\$\$\$";



    function DbConnection($debug)
    {
        $this->debug = $debug;
        $rs = $this->connect();
        return $rs;
    }

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    function connect()
    {
        // connect to MySQL DB Server
        try
        {
            $this->conn = new PDO('mysql:host='.$this->db.';dbname='.$this->dbname, $this->user, $this->pass);
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
    }

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    function executeQuery($sql)
    {
        if($this->debug == true) { echo("$sql <br>\n"); }
        $rs = $this->conn->query($sql);
        return $rs;
    }


// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    function disconnect() {
        $this->conn = null;
    }

}

?>
