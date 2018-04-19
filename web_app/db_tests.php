<?php
/*
Tests various db connection functions.

Assert code taken from http://php.net/manual/en/function.assert.php
*/

include('mainPageStats.php');
$table_name = "CarDatabase";

// Create a handler function
function my_assert_handler($file, $line, $code, $desc = null)
{
    echo "Assertion failed at $file:$line: $code";
    if ($desc) {
        echo ": $desc";
    }
    echo "\n";
}

// Active assert and make it quiet
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, 'my_assert_handler'); // Set up the callback


function test_db_connection()
{
    // Tests that the function successfully connects to MySQL db.
    $debug = true;
    $DB_CONNECTION = new DbConnection($debug);
    if(assert($DB_CONNECTION)) {
        echo "TEST PASSED. Succsesfully connected to database.<br>";
    } else {
        echo "TEST FAILED. Error connecting to database. Test failed.<br>";
    }
}


function test_db_query_should_pass()
{
    // Tests that a valid query successfully executes.
    $debug = true;
    $DB_CONNECTION = new DbConnection($debug);

    $good_sql = "SELECT * FROM `".$table_name."`.`Counter`;";
    $passing_query = $DB_CONNECTION->executeQuery($good_sql, $_SERVER["SCRIPT_NAME"]);
    if(assert($passing_query)) {
        $total_people = 0;
        echo "TEST PASSED. DB successfully queried.<br>";
    } else {
        echo "TEST FAILED. Error querying DB.<br>";
    }
}


function test_db_query_should_fail()
{
    // Tests a failing query.
    $debug = true;
    $DB_CONNECTION = new DbConnection($debug);

    $bad_sql = "SELECT * FROM `".$table_name."`.`Counter` WHERE `invalid_column` = 3";
    $failing_query = $DB_CONNECTION->executeQuery($bad_sql, $_SERVER["SCRIPT_NAME"]);
    echo $failing_query;
    if(!assert($failing_query)) {
        echo "<br>TEST PASSED. Error querying DB. This is expected, invalid SQL query.<br>";
    } else {
        echo "TEST FAILED. Successfuly queried DB, this should fail. Incorrect behavior.<br>";
    }
}


function test_peopleStats() {
    // Tests the 'peopleAllTime()' function by asserting that the array it return is non empty
    $all_people = peopleStats("all");
    if(assert($all_people != array())) {
        echo "TEST PASSED.";
    } else {
        echo "ERROR TEST FAILED.";
    }
}

function test_peopleCount() {
    // Tests the 'peopleYear()' function by asserting that the array it return is non empty
    $all_people = peopleCount("all");
    if(assert($all_people != array())) {
        echo "TEST PASSED.";
    } else {
        echo "ERROR TEST FAILED.";
    }
}

function test_get_stats() {
    // Tests that the stat functions gets scalar values from mysql pdo statements
    $people = peopleAllTime();
    $ave_people = get_ave($people);
    $median_people = get_median($people);
    $mode_people = get_mode($people);
    $max_people = max_people($people);
    $min_people = min_people($people);
    if (assert(is_scalar($ave_people)) &&
        assert(is_scalar($median_people)) &&
        assert(is_scalar($mode_people)) &&
        assert(is_scalar($max_people[0])) &&
        assert(is_scalar($min_people[0])))
    {
        echo "TEST PASSED.";
    } else {
        echo "TEST FAILED.";
    }
}

function test_add_entry() {
    // Tests the funciton that adds an entry to the db.
    $num_people_before = numPeopleAllTime();
    add_entry(1, "2017-12-22 14:08:00");
    $num_people_after = numPeopleAllTime();
    $diff = $num_people_after - $num_people_before;
    if(assert($diff == 1)) {
        echo "TEST PASSED.";
    } else {
        echo "TEST FAILED.";
    }
}

function test_num_people_between() {
    // Tests that the 'num_people_between()' function returns a scalar value.
    $num_people = num_people_between("2017-12-22 14:08:00", "2018-12-22 14:08:00");
    if(assert(is_scalar($num_people))) {
        echo "TEST PASSED.";
    } else {
        echo "TEST FAILED.";
    }
}


test_db_connection();
echo "<br>";
test_db_query_should_pass();
echo "<br>";
test_db_query_should_fail();
echo "<br>";
test_db_query_should_pass();
echo "<br>";
test_people_all_time();
echo "<br><br>";
test_people_year();
echo "<br><br>";
test_people_month();
echo "<br><br>";
test_people_day();
echo "<br><br>";
test_people_hour();
echo "<br><br>";
test_get_stats();
echo "<br><br>";
test_add_entry();
echo "<br><br>";
test_num_people_between();
?>
