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
    <form id="registercta" action="register.html">
        <button id="registerbutton"><img id="iconsvg" src="public/registerIcon.svg"></button>
    </form>
    <div id="headerbar">
    </div>
    <form id="homecta" action="index.php">
        <button id="homebutton"><img id="iconsvg" src="public/homeIcon.svg"></button>
    </form>
    <form id="historycta" action="logs/readable.php">
        <button id="homebutton"><img id="iconsvg" src="public/historyIcon.svg"></button>
    </form>
    <form id="usercta" action="index.php" method="POST">
        <input type="hidden" name="resetHash" value="yes">
        <button type="submit" id="homebutton"><img id="iconsvg" src="public/logoutIcon.svg"></button>
    </form>
    <button id="downbutton" onclick="scrollToBottom()" hidden><img id="iconsvg" src="public/downIcon.svg"></button>


    <div id="dbtablecont">
        <input type="text" id="searchBar" onkeyup="searchFunction()" placeholder="Search by ID...">
        <table class="dbtable" id="questionTable">
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Option 1</th>
                <th>Option 2</th>
                <th>Option 3</th>
                <th>Option 4</th>
                <th>Answer</th>
                <th>Difficulty</th>
                <th>Actions</th>
            </tr>
            <?php
                $server = mysqli_connect("localhost", "root", "");
                $connection = mysqli_select_db($server, "quiz_db");

                $query = "SELECT `question`, `questionid`, `option1`, `option2`, `option3`, `option4`, `answer`, `difficulty` FROM `questions`";
                $result = mysqli_query($server, $query);
                if ( !$result ) {
                    echo mysqli_error($server);
                    die;
                } else {

                    $dbSize = 0;

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
                        if ($row["difficulty"] == "" or $row["difficulty"] == " ") {
                            echo "<td>Unset</td>";
                        } else if (isset($row["difficulty"])) {
                            echo "<td>". ucfirst($row["difficulty"]) . "</td>";
                        }
                        echo "<td style='flex-grow:3;display: inline-flex;float: left;'> 
                        <form style='float: left;' class='actionform' action='panelprocess.php' method='post'>
                            <input class='insertusername' type='hidden' name='username' value=''>
                            <input type='hidden' name='action' value='delete,".$row["questionid"]."'>
                            <button class='deletebutton actionbutton'><img src='public/deleteIcon.svg'></button>
                        </form>
                        <form style='float: left;' class='actionform' action='panelprocess.php' method='post'>
                            <input class='insertusername' type='hidden' name='username' value=''>
                            <input type='hidden' name='action' value='edit,".$row["questionid"]."'>
                            <button class='editbutton actionbutton'><img src='public/editIcon.svg'></button>
                        </form>
                        </td>";
                        echo "</tr>";
                        if (isset($dbSize)) {
                            $dbSize += 1;
                            if ($dbSize > 15) {
                                echo "<script>document.getElementById('downbutton').hidden = false;</script>";
                                unset($dbSize);
                            };
                        }
                    }
                    echo "
                    <script>
                        let elements = document.querySelectorAll('.insertusername');
                        for (let i = 0, item; item = elements[i]; i++) {
                            item.value = String(sessionStorage.getItem('username'));
                        }
                    </script>
                    ";
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

    function searchFunction() {
    // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchBar");
        filter = input.value.toUpperCase();
        table = document.getElementById("questionTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

</script>