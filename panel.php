<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Panel</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <a href="https://docs.google.com/document/d/1AUrHIvbGsYO7f4EYAtMFNuGt9jjg0Bvz6orIihyAkIc/edit?usp=sharing" target="_blank">
        <img id="madeicon" src="public/madeIcon.svg">
    </a>
    <div id="headerbar">
    </div>
    <form id="homecta" action="index.php">
        <button id="homebutton"><img id="iconsvg" src="public/homeIcon.svg"></button>
    </form>
    <form id="usercta" action="index.php" method="POST">
        <input type="hidden" name="resetHash" value="yes">
        <button type="submit" id="homebutton"><img id="iconsvg" src="public/logoutIcon.svg"></button>
    </form>
    <form id="historycta" action="logs/readable.php">
        <button id="homebutton"><img id="iconsvg" src="public/historyIcon.svg"></button>
    </form>


    <div id="dbtablecont">
        <table class="dbtable">
            <tr>
                <th>Question ID</th>
                <th>Question</th>
                <th>Option 1</th>
                <th>Option 2</th>
                <th>Option 3</th>
                <th>Option 4</th>
                <th>Answer</th>
                <th>Actions</th>
            </tr>
            <?php
                $server = mysqli_connect("localhost", "root", "");
                $connection = mysqli_select_db($server, "quiz_db");

                $query = "SELECT `question`, `questionid`, `option1`, `option2`, `option3`, `option4`, `answer` FROM `questions`";
                $result = mysqli_query($server, $query);
                if ( !$result ) {
                    echo mysqli_error($server);
                    die;
                } else {
                    while ($row = mysqli_fetch_array($result))
                    {
                        echo "<tr>";
                        echo "<td>". $row["questionid"] . "</td>";
                        echo "<td>". $row["question"] . "</td>";
                        echo "<td>". $row["option1"] . "</td>";
                        echo "<td>". $row["option2"] . "</td>"; 
                        echo "<td>". $row["option3"] . "</td>";
                        echo "<td>". $row["option4"] . "</td>";
                        echo "<td>". ($row["answer"]+1) . "</td>";
                        echo "<td id='tdid'> 
                        <form class='actionform' action='panelprocess.php' method='post'>
                            <input class='insertusername' type='hidden' name='username' value=''>
                            <input type='hidden' name='action' value='delete,".$row["questionid"]."'>
                            <button class='deletebutton actionbutton'><img src='public/deleteIcon.svg'></button>
                        </form>
                        <form class='actionform' action='panelprocess.php' method='post'>
                            <input class='insertusername' type='hidden' name='username' value=''>
                            <input type='hidden' name='action' value='edit,".$row["questionid"]."'>
                            <button class='editbutton actionbutton'><img src='public/editIcon.svg'></button>
                        </form>
                        </td>";
                        echo "</tr>";
                        echo "
                        <script>
                            let elements = document.querySelectorAll('.insertusername');
                            for (let i = 0, item; item = elements[i]; i++) {
                                item.value = String(sessionStorage.getItem('username'));
                            }
                        </script>
                        ";
                    }
                }
            ?>
        </table>
        <form action='panelprocess.php' method='post'>
            <input type='hidden' name='action' value='create,NEW'>
            <button class='addbutton'><img id="addicon" src='public/addIcon.svg'></button>
        </form>
    </div>

    <form id="hiddenform">
        <input type="hidden" id="hiddenInput" name="hiddenInput">
    </form>

</body>
</html>

<script>

    let mh = sessionStorage.getItem("masterhash");
    let ph = sessionStorage.getItem("passhash");

    console.log("hash " + mh);
    if (ph != mh) {
        window.location.href = "login.html";
    } else if (!ph) {
        window.location.href = "login.html";
    }

    function updateUsernameHiddenValues() {
        let elements = document.querySelectorAll(".insertusername");
        for (let i = 0, item; item = elements[i]; i++) {
            item.value = String(sessionStorage.getItem("username"));
        }
    };


</script>