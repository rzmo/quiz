<!DOCTYPE html>
<lang="en">
<head>
    <meta charset="utf-8">
    <title>Panel Log - Queries</title>
    <link rel="icon" type="image/png" href="../public/icon.png">
    <link href="../public/style.css" rel="stylesheet"/>
</head>
<body>
    <form id="homecta" action="../index.php">
        <button id="homebutton"><img id="iconsvg" src="../public/homeIcon.svg"></button>
    </form>
    <a href="https://docs.google.com/document/d/1AUrHIvbGsYO7f4EYAtMFNuGt9jjg0Bvz6orIihyAkIc/edit?usp=sharing" target="_blank">
        <img id="madeicon" src="../public/madeIcon.svg">
    </a>
    <form id="usercta" action="../index.php" method="POST">
        <input type="hidden" name="resetHash" value="yes">
        <button type="submit" id="homebutton"><img id="iconsvg" src="../public/logoutIcon.svg"></button>
    </form>
    <form id="historycta" action="queries.php">
        <button id="downbutton"><img id="iconsvg" src="../public/arrayIcon.svg"></button>
    </form>
    <form id="historycta" action="../panel.php">
        <button id="homebutton"><img id="iconsvg" src="../public/backIcon.svg"></button>
    </form>

    <div id="dbtablecont">
        <table class="dbtable">
            <tr>
                <th>Action</th>
                <th>ID</th>
                <th>Content</th>
                <th>User</th>
                <th>Date & Time</th>
            </tr>
            <?php
                $server = mysqli_connect("localhost", "root", "");
                $connection = mysqli_select_db($server, "quiz_db");

                $query = "SELECT `action`, `qid`, `content`, `user`, `date` FROM `readablelog`";
                $result = mysqli_query($server, $query);
                if ( !$result ) {
                    echo mysqli_error($server);
                    die;
                } else {
                    while ($row = mysqli_fetch_array($result))
                    {
                        echo "<tr>";
                        echo "<td>". $row["action"] . "</td>";
                        echo "<td>". $row["qid"] . "</td>";
                        echo "<td>". $row["content"] . "</td>";
                        echo "<td>". $row["user"] . "</td>";
                        echo "<td>". $row["date"] . "</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </table>
        <form action='readable.php' method='post'>
            <input type='hidden' name='action' value='wipe'>
            <button class='wipebutton'><img id="wipeicon" src='../public/deleteIcon.svg'></button>
        </form>
    </div>

</body>
</html>


<script>

    let mh = sessionStorage.getItem("masterhash");
    let ph = sessionStorage.getItem("passhash");

    console.log("hash " + mh);
    if (ph != mh) {
        window.location.href = "../login.html";
    } else if (!ph) {
        window.location.href = "../login.html";
    }

</script>

<?php
if (isset($_POST["action"])) {
    if ($_POST["action"] == "wipe") {
        $server = mysqli_connect("localhost", "root", "");
        $connection = mysqli_select_db($server, "quiz_db");

        $query = "DELETE FROM `readablelog`";
        $result = mysqli_query($server, $query);
        echo "<script>window.location.href = 'readable.php';</script>";
    }
}
?>