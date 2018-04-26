<?php
    include('mainPageStats.php');
?>
<script>
    $(document).ready(function(){
    loadmain();
});

function loadmain(){
    $("#live").load("index.php");
    setTimeout(loadmain, 2000);
}
//function update() {
//    $.get("index.php", function(data) {
//        $("#live").html(data);
//        window.setTimeout(update, 10000);
//    });
}
</script>
<html>
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
        </style>
    </head>
    <body>
        <h1>CSCE 315 Project</h1>
        <h2>TAMU Rec Center - Arduino Person Counter</h2><br>
        <hr>
        <div class="main">
            <button onclick="location.href='admin.php'">Admin Login</button>
            <form action="" method="get">
                <p class="timeframe-selector">
                    Number of people in the past:
                    <select name="past_timeframe">
                      <option value="">Select an Option</option>
                      <option value="hour" <?php if ($_GET['past_timeframe'] == 'hour') echo 'selected="selected"';?>>
                        Hour
                      </option>
                      <option value="day" <?php if ($_GET['past_timeframe'] == 'day') echo 'selected="selected"';?>>
                        Day
                      </option>
                      <option value="month" <?php if ($_GET['past_timeframe'] == 'month') echo 'selected="selected"';?>>
                        Month
                      </option>
                      <option value="year" <?php if ($_GET['past_timeframe'] == 'year') echo 'selected="selected"';?>>
                        Year
                      </option>
                      <option value="all_time" <?php if ($_GET['past_timeframe'] == 'all_time') echo 'selected="selected"';?>>
                        All Time
                      </option>
                    </select>
                    <input type="submit" name="past_timeframe_submit"><br>
                </p>
            </form>

            <p>
                <?php
                    if(isset($_GET['past_timeframe'])) {
                        switch ($_GET['past_timeframe']) {
                            case "hour":
                                $num_people = peopleCount("hour");
                                $people = peopleStats("hour");
                                if(empty($num_people)) {
                                    $num_people = 0;
                                }
                                echo "<p>Number of people in the past hour: ".$num_people."</p>";
                                break;
                            case "day":
                                    $num_people = peopleCount("day");
                                    $people = peopleStats("day");
                                    if(empty($num_people)) {
                                        $num_people = 0;
                                    }
                                    echo "<p>Number of people in the past day: ".$num_people."</p>";
                                break;
                            case "month":
                                    $num_people = peopleCount("month");
                                    $people = peopleStats("month");
                                    if(empty($num_people)) {
                                        $num_people = 0;
                                    }
                                    echo "<p>Number of people in the past month: ".$num_people."</p>";
                                break;
                            case "year":
                                    $num_people = peopleCount("year");
                                    $people = peopleStats("year");
                                    if(empty($num_people)) {
                                        $num_people = 0;
                                    }
                                    echo "<p>Number of people in the past year: ".$num_people."</p>";
                                break;
                            case "all_time":
                                $num_people = peopleCount("all");
                                $people = peopleStats("all");
                                if(empty($num_people)) {
                                    $num_people = 0;
                                }
                                echo "<p>Number of people all time: ".$num_people."</p>";
                                break;
                        }
                    }
                ?>
            </p>




            <form action="" method="get">
                <p class="timeframe-selector">View statistics by:
                    <select name="stat_timeframe">
                      <option value="">Select an Option</option>
                      <option value="hour" <?php if ($_GET['stat_timeframe'] == 'hour') echo 'selected="selected"';?>>
                        Hour
                      </option>
                      <option value="day" <?php if ($_GET['stat_timeframe'] == 'day') echo 'selected="selected"';?>>
                        Day
                      </option>
                      <option value="month" <?php if ($_GET['stat_timeframe'] == 'month') echo 'selected="selected"';?>>
                        Month
                      </option>
                      <option value="year" <?php if ($_GET['stat_timeframe'] == 'year') echo 'selected="selected"';?>>
                        Year
                      </option>
                    </select>
                    <input type="submit" name="stat_timeframe_submit"><br>
                </p>
            </form>

            <p class="stats">
                <?php
                    if(isset($_GET['stat_timeframe'])) {
                        switch ($_GET['stat_timeframe']) {
                            case "hour":
                                include("hour.php");
                                break;
                            case "day":
                                include("day.php");
                                break;
                            case "month":
                                include("month.php");
                                break;
                            case "year":
                                include("year.php");
                                break;
                        }
                    }
                ?>
            </p>




            <form action="" method="get">
                <p class="timeframe-selector">Get number of people between
                    <input type="datetime-local" value="<?php echo $_GET['start'] ?>" name="start">
                    and
                    <input type="datetime-local" value="<?php echo $_GET['end'] ?>" name="end">
                    <input type="submit" name="between_timeframe_submit"><br>
                </p>
            </form>

            <p class="between-timeframe">
                <?php
                    if(isset($_GET['between_timeframe_submit'])) {
                        $start = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_GET['start'])));
                        $end = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_GET['end'])));
                        if($start >= $end) {
                            echo "<p class='error'>ERROR: Starting date must come before ending date.</p>";
                        } else {
                            echo "Number of people between ".$start." and ".$end.": ".num_people_between($start, $end);
                        }
                    }
                ?>
            </p>
        </div>
        <div class="live">
            <p class="livetime">Real-time statistics:
            <?php
                $num_people = peopleCount("all");
                                $people = peopleStats("all");
                                if(empty($num_people)) {
                                    $num_people = 0;
                                }
                                echo "{$num_people}";
            ?>
            </p>
        </div>

    </body>
</html>
