<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Past Results</title>
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    
    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz - Past Results</h1>
        <form action="index.php">
                <button id="backbutton">Go Back</button>
        </form>

        <?php
        if (isset($_POST['name']) and $_POST['name'] != "") {
            echo "<h2 class='anntext'> Heya, ".$_POST["name"].", you got a score of ".$_POST["score"]."/10 </h2>";
        }
        ?>
        <div id="tablecont">
        <table>
            <tr>
                <th>Name</th>
                <th>Score</th>
            </tr>
            <?php
                if (isset($_POST['name']) and $_POST['name'] != "") {
                    echo "<tr>";
                    echo "<td>". $_POST["name"] . "</td>";
                    echo "<td>". $_POST["score"] . "</td>";
                    echo "</tr>";
                }

                $server = mysqli_connect("localhost", "root", "");
                $connection = mysqli_select_db($server, "quiz_db");

                $query = "SELECT * FROM `scores`";
                $result = mysqli_query($server, $query);
                if ( !$result ) {
                    echo mysqli_error($server);
                    die;
                } else {
                    $tempcount = 0;
                    while ($row = mysqli_fetch_array($result))
                    {
                        $tempcount += 1;
                        echo "<tr>";
                        echo "<td>". $row["name"] . "</td>";
                        echo "<td>". $row["score"] . "</td>";
                        echo "</tr>";
                        // ONLY DISPLAY FIRST 5 RESULTS (Not relevant to score, just order submitted.)
                        /*if ($tempcount > 5) {
                            die;
                        }*/
                    }
                }
                
            ?>
        </table>
        </div>
    </div>
</body>
</html>

<?php

if (isset($_POST['name']) and $_POST['name'] != "") {
    $name = $_POST["name"];
    $score = $_POST["score"];

    $db_username = "root";
    $db_password = "";
    $host = "localhost";
    $database = "quiz_db";

    $server = mysqli_connect($host, $db_username, $db_password);
    $connection = mysqli_select_db($server, $database);

//
//

    if (!$server) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO `scores` (`name`, `score`) VALUES ('$name', '$score');";

    if (!mysqli_query($server, $sql)) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>