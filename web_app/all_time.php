<?php
    $num_people = numPeopleAllTime();
    $people = peopleAllTime();
    if(empty($num_people)) {
        $num_people = 0;
    }
    echo "<p>Number of people all time: ".$num_people."</p><br>";
?>
