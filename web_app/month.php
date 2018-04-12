<?php
    $ave_people = get_num_people_for_every("month");
    if(empty($ave_people)) {
        $ave_people = 0;
    }
    $ave = get_ave($ave_people);
    echo "The average number of people per month is: ".$ave.".<br>";
    $median = get_median($ave_people);
    echo "The median number of people per month is: ".$median.".<br>";
    $mode = get_mode($ave_people);
    echo "The mode per month is: ".$mode.".<br>";
    $max_people = max_people($ave_people);
    echo "The maximum number of people passing by in one month is ".$max_people[0]." and this occured in month ".$max_people[1].".<br>";
    $min_people = min_people($ave_people);
    echo "The minimum number of people passing by in one month (in a month where at least 1 person went by) is ".$min_people[0]." and this occured in month ".$min_people[1].".<br>";
?>