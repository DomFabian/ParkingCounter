<?php
    $ave_people = get_num_people_for_every("year");
    if(empty($ave_people)) {
        $ave_people = 0;
    }
    $ave = get_ave($ave_people);
    echo "The average number of people per year is: ".$ave.".<br>";
    $median = get_median($ave_people);
    echo "The median number of people per year is: ".$median.".<br>";
    $mode = get_mode($ave_people);
    echo "The mode per year is: ".$mode.".<br>";
    $max_people = max_people($ave_people);
    echo "The maximum number of people passing by in one year is ".$max_people[0]." and this occured in ".$max_people[1].".<br>";
    $min_people = min_people($ave_people);
    echo "The minimum number of people passing by in one year (in a year where at least 1 person went by) is ".$min_people[0]." and this occured in ".$min_people[1].".<br>";
?>