<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Past Results</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <a href="login.html">
        <img id="wficon" src="public/wfIcon.svg">
    </a>
    <a href="https://docs.google.com/document/d/1AUrHIvbGsYO7f4EYAtMFNuGt9jjg0Bvz6orIihyAkIc/edit?usp=sharing" target="_blank">
        <img id="madeicon" src="public/madeIcon.svg">
    </a>
    <form id="usercta" action="login.html">
        <button type="submit" id="homebutton"><img id="iconsvg" src="public/accountIcon.svg"></button>
    </form>

    <button id="refreshbutton" onclick="refresh()"><img id="refreshicon" src="public/refreshIcon.svg"></button>
    <form id="trophycta" action="index.php">
        <button class="iconbutton"><img id="iconsvg" src="public/backIcon.svg"></button>
    </form>
    <button id="themebutton" onclick="darkMode()"><img id="iconsvg" src="public/moonIcon.svg"></button>
    <button id="downbutton" onclick="scrollToBottom()" hidden><img id="iconsvg" src="public/downIcon.svg"></button>
    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz - Past Results</h1>

        <?php
        if (isset($_POST['name']) and $_POST['name'] != "") {
            echo "<h2 class='anntext'> Heya, ".$_POST["name"].", you got ".$_POST["score"]." correct! </h2>";
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

                $query = "SELECT * FROM `scores` ORDER BY score DESC";
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
                    }
                    if ($tempcount > 60) {
                        echo "<script>document.getElementById('downbutton').hidden = false;</script>";
                    }
                }

            ?>
        </table>
        </div>
        <form action="index.php" style="margin-bottom: 40px;">
            <button class="iconbutton"><img id="iconsvg" src="public/backIcon.svg"></button>
        </form>
    </div>

    <script>

        function refresh() {
            console.log("Refreshing");
            let icon = document.getElementById("refreshicon");
            icon.style.transform = "rotate(360deg)"
            setTimeout(reloadPage, 800);
        }

        function reloadPage() {
            location.reload();
        }

        function darkMode() {
            document.body.classList.toggle('dark-mode');

            let elements = document.getElementsByTagName('button');
            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.toggle("dark-mode");
            }
        }
    </script>

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

mysqli_close($server);

?>

<script>
    function scrollToBottom() {
        const totalHeight = document.documentElement.scrollHeight;
        const currentPosition = window.scrollY;
        const viewportHeight = window.innerHeight;
        const distanceToBottom = totalHeight - (currentPosition + viewportHeight);

        if (currentPosition <= totalHeight / 2) {
            // If current position is closer to the top half of the page
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        } else {
            // If current position is closer to the bottom half of the page
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }

    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const context = this;
            const args = arguments;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    const throttledScrollHandler = throttle((event) => {
        const totalHeight = document.documentElement.scrollHeight;
        const currentPosition = window.scrollY;
        const viewportHeight = window.innerHeight;

        let buttonEl = document.getElementById("downbutton");
        let imageEl = buttonEl.querySelector("#iconsvg");

        if (currentPosition <= totalHeight / 2) {
            imageEl.src = "public/downIcon.svg";
        } else {
            imageEl.src = "public/upIcon.svg";
        }
    }, 50); // Adjust the throttle interval (in milliseconds) as needed

    document.addEventListener("scroll", throttledScrollHandler);
</script>