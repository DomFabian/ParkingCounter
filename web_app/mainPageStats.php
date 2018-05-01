<?php
	include('db_connection.php');
    $table_name = "CarDatabase";
    $secretKey = "ourSecretArduinoKey";

//Combines all functions from the original file that retrieve count and timestamp for month, day, etc.
//Takes a string argument that is the period of time
	function peopleStats($period){
		global $table_name;
				$debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		if(gettype($period) != "string") {
				// Error.
				return 0;
		}
		switch($period)
		{
			case 'all':
                $people="SELECT car_count, time from {$table_name}";
				break;
			case 'year':
				$people="SELECT car_count, time FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 365 DAY) < time";
				break;
			case 'month':
				$people="SELECT car_count, time FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 30 DAY) < time";
				break;
			case 'week':
				$people="SELECT car_count, time FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 7 DAY) < time";
				break;
			case 'day':
				$people="SELECT car_count, time FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 1 DAY) < time";
				break;
			case 'hour':
				$people="SELECT car_count, time FROM {$table_name} WHERE time >= DATE_SUB(NOW(),INTERVAL 1 HOUR);";
				break;
			default:
				echo "Invalid time period";
				return 0;
				break;
		}
		$people_query=$DB_CONNECTION->executeQuery($people, $_SERVER["SCRIPT_NAME"]);
		$DB_CONNECTION->disconnect();
		return $people_query;
	}

//Combines all functions from the original file that retrieve just the count for a given time period
//Takes a string argument that is the period of time
	function peopleCount($period){
		global $table_name;
		$debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		if(gettype($period) != "string") {
				// Error.
				return 0;
		}
		switch($period)
		{
			case 'all':
//				$people="SELECT count(*) from `".$table_name."`.`count`";
                $people="SELECT count(*) from {$table_name}";
				break;
			case 'year':
				$people="SELECT count(*) FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 365 DAY) < time";
				break;
			case 'month':
				$people="SELECT count(*) FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 30 DAY) < time";
				break;
			case 'day':
				$people="SELECT count(*) FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 1 DAY) < time";
				break;
			case 'hour':
				$people="SELECT count(*) FROM {$table_name} WHERE (CURRENT_TIMESTAMP - INTERVAL 1 HOUR) < time";
				break;
		}
		$people_query = $DB_CONNECTION->executeQuery($people, $_SERVER["SCRIPT_NAME"]);
		$result = $people_query->fetch();
		$people_count = $result[0];
		$DB_CONNECTION->disconnect();

		return $people_count;
	}

    // Returns the number of people within each timeframe
    // example: if $timeframe == 'month', it will return the total number of people counted in each month.
    // This is used for getting statistical functions for certain timeframes.
    function get_num_people_for_every($timeframe) {
        global $table_name;
        if(gettype($timeframe) != "string") {
            // Error.
            return 0;
        }
        $people_all_time = peopleStats('all');
        switch ($timeframe) {
            case 'hour':
                $sql = "SELECT COUNT(*) AS count, HOUR(`time`) AS hour FROM {$table_name} GROUP BY HOUR(`time`)";
                break;
            case 'day':
                $sql = "SELECT COUNT(*) as count, DATE(`time`) as day FROM {$table_name} GROUP BY DATE(`time`)";
                break;
            case 'month':
                $sql = "SELECT COUNT(*) as count, MONTH(`time`) as month FROM {$table_name} GROUP BY MONTH(`time`)";
                break;
            case 'year':
                $sql = "SELECT COUNT(*) as count, YEAR(`time`) as year FROM {$table_name} GROUP BY YEAR(`time`)";
                break;
						default:
							echo "Error";
							return 0;
        }
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $ave_people_query = $DB_CONNECTION->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
        $DB_CONNECTION->disconnect();
        return $ave_people_query->fetchAll();
    }

    //Returns the average of the data provided
    function get_ave($pdo_statement) {
        
        $count = 0;
        $num = 0;
        foreach ($pdo_statement as $row) {
            $count += 1;
            $num += $row['count'];
        }
        if($count==0)
        {
            return 0;
        }
        else
        {
            return $num/$count;
        }
    }
    //Gets the average for predictive 
    function get_pave($pdo_statement) {
//        print_r($pdo_statement);
        $count = 0;
        $num = 0;
        $loop_count=0;
        $curr_day=0;
        foreach ($pdo_statement as $row) {
            $loop_count+=1;
            $timestamp = (string)$row['time'];
            $day = substr($timestamp, 8,  2);
//            $day_int= (int) day;
            $add = 1;
            if($loop_count==1)
            {
                $curr_day=$day;
            }
            if($day==$curr_day & $loop_count!=1)
            {
                $add=0;
            }
            $count += $add;
            $num += 1;
        }
//        echo "The number of rows was {$count}\n    ";
        if($count==0)
        {
            return 0;
        }
        else
        {
            return floor($num/7);
        }
    }
     function predict() {
        global $table_name;
		$debug = false;
		$DB_CONNECTION = new DbConnection($debug);
        ini_set("date.timezone", "America/Chicago");
        $hour = date('H');
        $sql= "SELECT * from {$table_name} HAVING DATE_FORMAT(`time`, '%H')={$hour}";
        
        $DB_CONNECTION = new DbConnection($debug);
        $pdo_statement = $DB_CONNECTION->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
        $DB_CONNECTION->disconnect();

        return $pdo_statement->fetchAll();
    }

    //Returns the median of the data provided
    function get_median($pdo_statement) {
        $count = 0;
        $num = 0;

        $count_array = array();
        foreach($pdo_statement as $row) {
            array_push($count_array, $row[0]);
            $count += 1;
        }
        $middleval = floor(($count-1)/2); // find the middle value

        if($count % 2) { // odd number, middle is the median
            $median = $count_array[$middleval];
        } else { // even number, calculate avg of 2 medians
            $low = $count_array[$middleval];
            $high = $count_array[$middleval+1];
            $median = (($low+$high)/2);
        }
        return $median;
    }

    //Returns the mode of the data provided
    function get_mode($pdo_statement) {
        $count_array = array();
        foreach($pdo_statement as $row) {
            array_push($count_array, $row[0]);
        }
        $values = array_count_values($count_array);
        $mode = array_search(max($values), $values);
        return $mode;
    }

    //Returns the maximum number of people within the data provided
    function max_people($pdo_statement) {
        $max = 0;
        $max_array = array(); // max_array[0] = max number of people, max_array[1] = timeframe that occurred.
        array_push($max_array, 0, 0);
        foreach($pdo_statement as $row) {
            if($row[0] >= $max) {
                $max = $row[0];
                $max_array[0] = $row[0];
                $max_array[1] = $row[1];
            }
        }
        return $max_array;
    }

    //Returns the minimum number of people within the data provided
    function min_people($pdo_statement) {
        $min = 9999;
        $min_array = array(); // min_array[0] = min number of people, min_array[1] = timeframe that occurred.
        array_push($min_array, 0, 0);
        foreach($pdo_statement as $row) {
            if($row[0] <= $min) {
                $min = $row[0];
                $min_array[0] = $row[0];
                $min_array[1] = $row[1];
            }
        }
        return $min_array;
    }

    //Returns the number of people between two different times
    function num_people_between($start, $end) {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $num_people_between = "SELECT count(*) FROM {$table_name} WHERE time BETWEEN '".$start."' AND '".$end."'";
        $num_people_between_query = $DB_CONNECTION->executeQuery($num_people_between, $_SERVER["SCRIPT_NAME"]);
        $result = $num_people_between_query->fetch();
        $num_people_between_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $num_people_between_count;
    }

    //Adds entry into database
    function add_entry($car_count, $timestamp) {
        global $table_name;
        $sql = "INSERT INTO {$table_name} (time) VALUES ('".$timestamp."')";
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $DB_CONNECTION->executeQuery($sql);
        $DB_CONNECTION->disconnect();
        return true;
    }

    function insertIntoDatabase($table) {
        /* This function takes one parameter and returns an error code.
           Parameter is the name of the database table.
           Error code 0: could not insert into database.
           Error code 1: successful insert into database. */
        $debug = false;
        $COMMON = new DbConnection($debug);
        
        $sql = "INSERT INTO $table (`time`) VALUES (CURRENT_TIMESTAMP);";
        $rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
        if (empty($rs)) {
            return 0;
        }
        else {
            $row = $rs->fetch(PDO::FETCH_ASSOC);
            return 1;
        }    
    }
function handleArduinoPing($key) {
        /* This function takes one parameter and returns an
           integer error code. handleArduinoPing() is the script
           run by the webserver whenever the Arduino of the
           PedestrianCounter detects that somebody has walked by
           and sends a message to the webserver. This function
           will be called whenever a POST request is made to the
           webserver. The function ensures that a specific $key is
           provided in the body of the HTTP POST request before
           sending any information to the database.
           Parameter is a string key from the Arduino.
           Error code 0: unable to handle Arduino ping (DB error).
           Error code -1: invalid key provided by Arduino.
           Error code 1: successful handle of Arduino ping. */
        
        $debug = false;
        $COMMON = new DbConnection($debug);
        global $secretKey;
        global $table_name;

        // check for invalid Arduino key or non-string input
        if ($key != $secretKey) {
            return -1;
        }
        else {
            $statusCode = insertIntoDatabase($tableName);
            return $statusCode;
        }
    }

?>
