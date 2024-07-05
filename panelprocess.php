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
    <form id="usercta" action="index.php" method="POST">
        <input type="hidden" name="resetHash" value="yes">
        <button type="submit" id="homebutton"><img id="iconsvg" src="public/logoutIcon.svg"></button>
    </form>
    <form id="homecta" action="index.php">
        <button id="homebutton"><img id="iconsvg" src="public/homeIcon.svg"></button>
    </form>
    <form id="historycta" action="panel.php">
        <button id="homebutton"><img id="iconsvg" src="public/backIcon.svg"></button>
    </form>
</body>


<?php
function checkQuestionIDExists($paramQuestionID) {

    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    if (!$server || !$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $query = "SELECT 1 FROM questions WHERE questionID = '$paramQuestionID'";
    
    $stmt = mysqli_prepare($server, $query);
        
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_store_result($stmt);
    
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    
    mysqli_stmt_close($stmt);
    mysqli_close($server);
    
    return $exists;
}

if (isset($_POST['creatingnew'])) {
    //create info
    $requestContent = array(
        $_POST["question"],
        $_POST["questionid"],
        $_POST["option1"],
        $_POST["option2"],
        $_POST["option3"],
        $_POST["option4"],
        $_POST["answer"],
        $_POST["difficulty"]
    );
    $username = $_POST["username"];

    if (!isset($requestContent[7])) {
        $requestContent[7] = "";
    }
    $placeholderNum = 0;
    while (checkQuestionIDExists('PLACEHOLDER_ID_'.$placeholderNum) == true) {
        $placeholderNum += 1;
    }
    for ($x = 0; $x <= 5; $x++) {
        if ($x != 1) {
            if (!isset($requestContent[$x]) || strlen($requestContent[$x]) < 1 || $requestContent[$x] == " ") {
                $requestContent[$x] = "PLACEHOLDER_".$placeholderNum;
            }
        }
    }
    if (!isset($requestContent[1]) || strlen($requestContent[1]) < 1 || $requestContent[1] == " ") {
        $requestContent[1] = "PLACEHOLDER_ID_".$placeholderNum;
    }
    if (!isset($requestContent[6]) || strlen(strval($requestContent[6])) < 1 || $requestContent[6] == " " || $requestContent[6] == "") {
        $requestContent[6] = 1;
    }

    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");

    $requestContent[6] = intval($requestContent[6]) - 1;
    $query = "INSERT INTO `questions`(`question`, `questionid`, `option1`, `option2`, `option3`, `option4`, `answer`, `difficulty`) VALUES ('$requestContent[0]','$requestContent[1]','$requestContent[2]','$requestContent[3]','$requestContent[4]','$requestContent[5]',$requestContent[6],'$requestContent[7]')";
    $result = mysqli_query($server, $query);
    $querytext = "INSERT INTO \`questions\`(\`question\`, \`questionid\`, \`option1\`, \`option2\`, \`option3\`, \`option4\`, \`answer\`, \`difficulty\`) VALUES (\'$requestContent[0]\',\'$requestContent[1]\',\'$requestContent[2]\',\'$requestContent[3]\',\'$requestContent[4]\',\'$requestContent[5]\',$requestContent[6],\'$requestContent[7]\')";
    $datetime = strval(date("d/m/Y H:i:s"));
    $query = "INSERT INTO `querylog`(`action`, `user`, `date`) VALUES ('".strval($querytext)."', '".$username."','".strval($datetime)."')";
    $result = mysqli_query($server, $query);
    $requestContent[6] += 1; 
    $answerIndex = $requestContent[6] + 1;
    $answerContent = $requestContent[$answerIndex];
    $querytext = "Question: <b>$requestContent[0]</b> <br>Options: <b>$requestContent[2]</b>, <b>$requestContent[3]</b>, <b>$requestContent[4]</b>, <b>$requestContent[5]</b> <br>Answer: <b>$answerContent</b> (Option $requestContent[6]) <br>Difficulty: <b>$requestContent[7]</b>";
    $query = "INSERT INTO `readablelog`(`action`, `qid`, `content`, `user`, `date`) VALUES ('CREATE', '$requestContent[1]', '".strval($querytext)."', '".$username."','".strval($datetime)."')";
    $result = mysqli_query($server, $query);
    echo "<script>location.href = 'panel.php';</script>";
    die();
//    $query = "INSERT INTO `readablelog`(`action`, `qid`, `content`, `user`, `date`) VALUES ('CREATE', '$requestContent[1]', '".strval($querytext)."', '".$username."','".strval($datetime)."')";
} else if (isset($_POST['questionid'])) {
    //edit info
    $requestContent = array(
        $_POST["question"],
        $_POST["questionid"],
        $_POST["option1"],
        $_POST["option2"],
        $_POST["option3"],
        $_POST["option4"],
        $_POST["answer"],
        $_POST["difficulty"]
    );
    $username = $_POST["username"];

    if (!isset($requestContent[7])) {
        $requestContent[7] = "";
    }
    $placeholderNum = 0;
    while (checkQuestionIDExists('PLACEHOLDER_ID_'.$placeholderNum) == true) {
        $placeholderNum += 1;
    }
    for ($x = 0; $x <= 5; $x++) {
        if ($x != 1) {
            if (!isset($requestContent[$x]) || strlen($requestContent[$x]) < 1 || $requestContent[$x] == " ") {
                $requestContent[$x] = "PLACEHOLDER_".$placeholderNum;
            }
        }
    }
    if (!isset($requestContent[1]) || strlen($requestContent[1]) < 1 || $requestContent[1] == " ") {
        $requestContent[1] = "PLACEHOLDER_ID_".$placeholderNum;
    }
    if (!isset($requestContent[6]) || strlen(strval($requestContent[6])) < 1 || $requestContent[6] == " " || $requestContent[6] == "") {
        $requestContent[6] = 1;
    }
    
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");

    $requestContent[6] = intval($requestContent[6]) - 1;
    $query = "UPDATE `questions` SET `question`='$requestContent[0]',`option1`='$requestContent[2]',`option2`='$requestContent[3]',`option3`='$requestContent[4]',`option4`='$requestContent[5]',`answer`=$requestContent[6],`difficulty`='$requestContent[7]' WHERE questionid = '$requestContent[1]'";
    $result = mysqli_query($server, $query);
    $datetime = strval(date("d/m/Y H:i:s"));
    $querytext = "UPDATE \`questions\` SET \`question\`=\'$requestContent[0]\',\`option1\`=\'$requestContent[2]\',\`option2\`=\'$requestContent[3]\',\`option3\`=\'$requestContent[4]\',\`option4\`=\'$requestContent[5]\',\`answer\`=$requestContent[6],\`difficulty\`=\'$requestContent[7]\' WHERE questionid = \'$requestContent[1]\'";
    $query = "INSERT INTO `querylog`(`action`, `user`, `date`) VALUES ('".strval($querytext)."', '".$username."','".strval($datetime)."')";
    $result = mysqli_query($server, $query);
    $requestContent[6] += 1; 
    $answerIndex = $requestContent[6] + 1;
    $answerContent = $requestContent[$answerIndex];
    $querytext = "Question: <b>$requestContent[0]</b> <br>Options: <b>$requestContent[2]</b>, <b>$requestContent[3]</b>, <b>$requestContent[4]</b>, <b>$requestContent[5]</b> <br>Answer: <b>$answerContent</b> (Option $requestContent[6]) <br>Difficulty: <b>$requestContent[7]</b>";
    $query = "INSERT INTO `readablelog`(`action`, `qid`, `content`, `user`, `date`) VALUES ('EDIT', '$requestContent[1]', '".strval($querytext)."', '".$username."','".strval($datetime)."')";
    $result = mysqli_query($server, $query);
    echo "<script>location.href = 'panel.php';</script>";
    die();
} else if (isset($_POST['action'])) {
    //delete info
    
    $requestContent = $_POST["action"];
    if (isset($_POST["username"])){
        $username = $_POST["username"];
    }

    $action = explode(",", $requestContent)[0];
    $questionID = explode(",", $requestContent)[1];
    
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    if ($action == "delete") {
        $query = "DELETE FROM `questions` WHERE questionid = '$questionID'";
        $result = mysqli_query($server, $query);
        $querytext = "DELETE FROM \`questions\` WHERE questionid = \'$questionID\'";
        $datetime = strval(date("d/m/Y H:i:s"));
        $query = "INSERT INTO `querylog`(`action`, `user`, `date`) VALUES ('".strval($querytext)."', '".$username."','".strval($datetime)."')";
        $result = mysqli_query($server, $query);
        $querytext = "N/A";
        $query = "INSERT INTO `readablelog`(`action`, `qid`, `content`, `user`, `date`) VALUES ('DELETE', '$questionID', '".strval($querytext)."', '".$username."','".strval($datetime)."')";
        $result = mysqli_query($server, $query);
        echo "<script>location.href = 'panel.php';</script>";
        die();
    } else if ($action == "edit") {
        $server = mysqli_connect("localhost", "root", "");
        $connection = mysqli_select_db($server, "quiz_db");

        $query = "SELECT `question`, `option1`, `option2`, `option3`, `option4`, `answer`, `difficulty` FROM `questions` WHERE questionid = '$questionID'";
        $result = mysqli_query($server, $query);

        $entry = mysqli_fetch_array($result);
        echo "
            <form class='editform' action='panelprocess.php' method='post'>
                <h1 id='formheader'>Editing Question '".$questionID."'</h1>
                <input class='insertusername' type='hidden' name='username' value=''>
                <input type='hidden' name='questionid' value='".$questionID."'>
                <label>Question</label>
                <input type='text' name='question' placeholder='What is xyz?' value='".$entry["question"]."' required>
                <label>Option 1</label>
                <input type='text' name='option1' placeholder='Choice One' value='".$entry["option1"]."' required>
                <label>Option 2</label>
                <input type='text' name='option2' placeholder='Choice Two' value='".$entry["option2"]."' required>
                <label>Option 3</label>
                <input type='text' name='option3' placeholder='Choice Three' value='".$entry["option3"]."' required>
                <label>Option 4</label>
                <input type='text' name='option4' placeholder='Choice Four' value='".$entry["option4"]."' required>
                <label>Answer (Option #)</label>
                <input type='number' name='answer' min='1' max='4' placeholder='Whatever number the correct option is (1-4)' value='".($entry["answer"]+1)."' required>
                <label>Difficulty</label>
                <input type='text' name='difficulty' placeholder='Leave blank to not classify' value='".($entry["difficulty"])."'>
                <input id='editsubmit' type='submit' value='Save Changes'></input>
            </form>
        ";
        echo "
        <script>
            let elements = document.querySelectorAll('.insertusername');
            for (let i = 0, item; item = elements[i]; i++) {
                item.value = String(sessionStorage.getItem('username'));
            }
        </script>
        ";
    } else if ($action == "create") {
        echo "
            <form class='editform' action='panelprocess.php' method='post'>
                <h1 id='formheader'>Creating New Question</h1>
                <input class='insertusername' type='hidden' name='username' value=''>
                <input type='hidden' name='creatingnew' value='YES'>
                <label>Question ID</label>
                <input type='text' name='questionid' placeholder='exampleID' value='' required>
                <label>Question</label>
                <input type='text' name='question' placeholder='What is xyz?' value='' required>
                <label>Option 1</label>
                <input type='text' name='option1' placeholder='Choice One' value='' required>
                <label>Option 2</label>
                <input type='text' name='option2' placeholder='Choice Two' value='' required>
                <label>Option 3</label>
                <input type='text' name='option3' placeholder='Choice Three' value='' required>
                <label>Option 4</label>
                <input type='text' name='option4' placeholder='Choice Four' value='' required>
                <label>Answer Index</label>
                <input type='number' name='answer' min='1' max='4' placeholder='Whatever number the correct option is (1-4)' value='' required>
                <label>Difficulty</label>
                <input type='text' name='difficulty' placeholder='Leave blank to not classify' value=''>
                <input id='createsubmit' type='submit' value='Create'></input>
            </form>
        ";
        echo "
        <script>
            let elements = document.querySelectorAll('.insertusername');
            for (let i = 0, item; item = elements[i]; i++) {
                item.value = String(sessionStorage.getItem('username'));
            }
        </script>
        ";
    } 
} else if (isset($_POST["toHash"])) {
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    $query = "SELECT * FROM `users`";
    $result = mysqli_query($server, $query);

    if ( !$result ) {
        echo mysqli_error($server);
        echo "<script>window.location.href = 'index.php';</script>";
        die;
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $nameToCheck = $_POST["userlogin"];
            if (strval($nameToCheck) == strval($row["name"])) {
                $tempPassHash = hash('sha256', strval($_POST["toHash"] . $row["salt"]));
                if ($tempPassHash == $row["passhash"]) {
                    $username = $row["name"];
                    echo "<script>sessionStorage.setItem('username', '$username');sessionStorage.setItem('masterhash', '$tempPassHash');sessionStorage.setItem('passhash', '$tempPassHash');</script>";    
                }
            }
        }
        echo "<script>window.location.href = 'login.html';</script>";
        exit;
    }
} else if (isset($_POST["wantedpass"])) {
    $tempSalt = generateRandomString();

    $tempPassHash = hash('sha256', strval($_POST["wantedpass"] . $tempSalt));
    $tempUsername = $_POST["wantedname"];

    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    $query = "INSERT INTO `users`(`name`, `passhash`, `salt`) VALUES ('$tempUsername','$tempPassHash','$tempSalt')";
    $result = mysqli_query($server, $query);

    echo "<script>sessionStorage.setItem('username', '$tempUsername');sessionStorage.setItem('masterhash', '$tempPassHash');sessionStorage.setItem('passhash', '$tempPassHash');</script>";    

    echo "<script>window.location.href = 'login.html';</script>";

} else if (isset($_POST["wantedpasso"])) {
    $tempSalt = generateRandomString();

    $tempPassHash = hash('sha256', strval($_POST["wantedpasso"] . $tempSalt));
    $tempUsername = $_POST["wantednameo"];

    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    $query = "INSERT INTO `users`(`name`, `passhash`, `salt`) VALUES ('$tempUsername','$tempPassHash','$tempSalt')";
    $result = mysqli_query($server, $query);

    echo "<script>window.location.href = 'panel.php';</script>";

}

function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

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