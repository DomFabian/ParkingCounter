<?php
    $ave_people = get_num_people_for_every("hour");
    if(empty($ave_people)) {
        $ave_people = 0;
    }
    $ave = get_ave($ave_people);
    echo "The average number of people per hour is: ".$ave.".<br>";
    $median = get_median($ave_people);
    echo "The median number of people per hour is: ".$median.".<br>";
    $mode = get_mode($ave_people);
    echo "The mode per hour is: ".$mode.".<br>";
    $max_people = max_people($ave_people);
    echo "The maximum number of people passing by in one hour is ".$max_people[0].".<br>";
    $min_people = min_people($ave_people);
    echo "The minimum number of people passing by in one hour (in an hour where at least 1 person went by) is ".$min_people[0].".<br>";
?>