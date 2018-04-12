<?php
	include('db_connection.php');
    $table_name = "XXXXXX";
	
	//Grabs number of people and timestamp for all time 
	function peopleAllTime() {
		global $table_name;
        $debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		$total_people = "SELECT num_people, timestamp from `".$table_name."`.`Counter`";
		$total_people_query = $DB_CONNECTION->executeQuery($total_people, $_SERVER["SCRIPT_NAME"]);
		$DB_CONNECTION->disconnect();

		return $total_people_query;
	}

	//Grabs number of people and timestamp for past year
	function peopleYear() {
		global $table_name;
        $debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		$total_people_year = "SELECT num_people, timestamp FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 365 DAY) < timestamp";
		$total_people_year_query = $DB_CONNECTION->executeQuery($total_people_year, $_SERVER["SCRIPT_NAME"]);
		$DB_CONNECTION->disconnect();

		return $total_people_year_query;
	}


	//Grabs number of people and timestamp for past month
	function peopleMonth() {
		global $table_name;
        $debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		$total_people_month = "SELECT num_people, timestamp FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 30 DAY) < timestamp";
		$total_people_month_query = $DB_CONNECTION->executeQuery($total_people_month, $_SERVER["SCRIPT_NAME"]);
		$DB_CONNECTION->disconnect();

		return $total_people_month_query;
	}


	//Grabs number of people and timestamp for past week
	function peopleWeek() {
		global $table_name;
        $debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		$total_people_week = "SELECT num_people, timestamp FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 7 DAY) < timestamp";
		$total_people_week_query = $DB_CONNECTION->executeQuery($total_people_week, $_SERVER["SCRIPT_NAME"]);
	    $DB_CONNECTION->disconnect();

		return $total_people_week_query;
	}


	//Grabs number of people and timestamp for past day
	function peopleDay() {
		global $table_name;
        $debug = false;
		$DB_CONNECTION = new DbConnection($debug);
		$total_people_day = "SELECT num_people, timestamp FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 1 DAY) < timestamp";
		$total_people_day_query = $DB_CONNECTION->executeQuery($total_people_day, $_SERVER["SCRIPT_NAME"]);
		$DB_CONNECTION->disconnect();

		return $total_people_day_query;
	}

    //Grabs number of people and timestamp for past hour
    function peopleHour() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $total_people_hour = "SELECT num_people, timestamp FROM `".$table_name."`.`Counter` WHERE timestamp >= DATE_SUB(NOW(),INTERVAL 1 HOUR);";
        $total_people_hour_query = $DB_CONNECTION->executeQuery($total_people_hour, $_SERVER["SCRIPT_NAME"]);
        $DB_CONNECTION->disconnect();

        return $total_people_hour_query;
    }

    //Grabs number of people who entered for all time
    function numPeopleAllTime() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $total_people = "SELECT count(*) from `".$table_name."`.`Counter`";
        $total_people_query = $DB_CONNECTION->executeQuery($total_people, $_SERVER["SCRIPT_NAME"]);
        $result = $total_people_query->fetch();
        $total_people_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $total_people_count;
    }


    //Grabs number of people who have entered for past year
    function numPeopleYear() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $total_people_year = "SELECT count(*) FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 365 DAY) < timestamp";
        $total_people_year_query = $DB_CONNECTION->executeQuery($total_people_year, $_SERVER["SCRIPT_NAME"]);
        $result = $total_people_year_query->fetch();
        $total_people_year_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $total_people_year_count;
    }

    //Grabs number of people who have entered for past month
    function numPeopleMonth() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $total_people_month = "SELECT count(*) FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 30 DAY) < timestamp";
        $total_people_month_query = $DB_CONNECTION->executeQuery($total_people_month, $_SERVER["SCRIPT_NAME"]);
        $result = $total_people_month_query->fetch();
        $total_people_month_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $total_people_month_count;
    }

    //Grabs number of people who have entered for past day
    function numPeopleDay() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug); 
        $total_people_day = "SELECT count(*) FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 1 DAY) < timestamp";
        $total_people_day_query = $DB_CONNECTION->executeQuery($total_people_day, $_SERVER["SCRIPT_NAME"]);
        $result = $total_people_day_query->fetch();
        $total_people_day_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $total_people_day_count;
    }

    //Grabs number of people who have entered for past hour
    function numPeopleHour() {
        global $table_name;
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug); 
        $total_people_hour = "SELECT count(*) FROM `".$table_name."`.`Counter` WHERE (CURRENT_TIMESTAMP - INTERVAL 1 HOUR) < timestamp";
        $total_people_hour_query = $DB_CONNECTION->executeQuery($total_people_hour, $_SERVER["SCRIPT_NAME"]);
        $result = $total_people_hour_query->fetch();
        $total_people_hour_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $total_people_hour_count;
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
        $people_all_time = peopleAllTime();
        switch ($timeframe) {
            case 'hour':
                $sql = "SELECT COUNT(*) AS count, HOUR(`timestamp`) AS hour FROM `".$table_name."`.`Counter` GROUP BY HOUR(`timestamp`)";
                break;
            case 'day':
                $sql = "SELECT COUNT(*) as count, DATE(`timestamp`) as day FROM `".$table_name."`.`Counter` GROUP BY DATE(`timestamp`)";
                break;
            case 'month':
                $sql = "SELECT COUNT(*) as count, MONTH(`timestamp`) as month FROM `".$table_name."`.`Counter` GROUP BY MONTH(`timestamp`)";
                break;
            case 'year':
                $sql = "SELECT COUNT(*) as count, YEAR(`timestamp`) as year FROM `".$table_name."`.`Counter` GROUP BY YEAR(`timestamp`)";
                break;
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
        return $num/$count;
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
        $num_people_between = "SELECT count(*) FROM `".$table_name."`.`Counter` WHERE timestamp BETWEEN '".$start."' AND '".$end."'";
        $num_people_between_query = $DB_CONNECTION->executeQuery($num_people_between, $_SERVER["SCRIPT_NAME"]);
        $result = $num_people_between_query->fetch();
        $num_people_between_count = $result[0];
        $DB_CONNECTION->disconnect();

        return $num_people_between_count;
    }

    //Adds entry into database
    function add_entry($num_people, $timestamp) {
        global $table_name;
        $sql = "INSERT INTO `".$table_name."`.`Counter` (num_people, timestamp) VALUES ('".$num_people."', '".$timestamp."')";
        $debug = false;
        $DB_CONNECTION = new DbConnection($debug);
        $DB_CONNECTION->executeQuery($sql);
        $DB_CONNECTION->disconnect();
        return true;
    }
?>