<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>dbPanel</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <form id="homecta" action="panel.php">
        <button id="homebutton"><img id="iconsvg" src="public/backIcon.svg"></button>
    </form>
    <a href="https://docs.google.com/document/d/1AUrHIvbGsYO7f4EYAtMFNuGt9jjg0Bvz6orIihyAkIc/edit?usp=sharing" target="_blank">
        <img id="madeicon" src="public/madeIcon.svg">
    </a>
</body>


<?php

if (isset($_POST['creatingnew'])) {
    //create info
    $requestContent = array(
        $_POST["question"],
        $_POST["questionid"],
        $_POST["option1"],
        $_POST["option2"],
        $_POST["option3"],
        $_POST["option4"],
        $_POST["answer"]
    );
    
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");

    $requestContent[6] = intval($requestContent[6]) - 1;
    $query = "INSERT INTO `questions`(`question`, `questionid`, `option1`, `option2`, `option3`, `option4`, `answer`) VALUES ('$requestContent[0]','$requestContent[1]','$requestContent[2]','$requestContent[3]','$requestContent[4]','$requestContent[5]',$requestContent[6])";
    $result = mysqli_query($server, $query);
    echo "<script>location.href = 'panel.php';</script>";
    die();

} else if (isset($_POST['questionid'])) {
    //edit info
    $requestContent = array(
        $_POST["question"],
        $_POST["questionid"],
        $_POST["option1"],
        $_POST["option2"],
        $_POST["option3"],
        $_POST["option4"],
        $_POST["answer"]
    );
    
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");

    $requestContent[6] = intval($requestContent[6]) - 1;
    $query = "UPDATE `questions` SET `question`='$requestContent[0]',`option1`='$requestContent[2]',`option2`='$requestContent[3]',`option3`='$requestContent[4]',`option4`='$requestContent[5]',`answer`=$requestContent[6] WHERE questionid = '$requestContent[1]'";
    $result = mysqli_query($server, $query);
    echo "<script>location.href = 'panel.php';</script>";
    die();
} else if (isset($_POST['action'])) {
    //delete info
    
    $requestContent = $_POST["action"];

    $action = explode(",", $requestContent)[0];
    $questionID = explode(",", $requestContent)[1];
    
    $server = mysqli_connect("localhost", "root", "");
    $connection = mysqli_select_db($server, "quiz_db");
    
    if ($action == "delete") {
        $query = "DELETE FROM `questions` WHERE questionid = '$questionID'";
        $result = mysqli_query($server, $query);
        echo "<script>location.href = 'panel.php';</script>";
        die();
    } else if ($action == "edit") {
        $server = mysqli_connect("localhost", "root", "");
        $connection = mysqli_select_db($server, "quiz_db");

        $query = "SELECT `question`, `option1`, `option2`, `option3`, `option4`, `answer` FROM `questions` WHERE questionid = '$questionID'";
        $result = mysqli_query($server, $query);

        $entry = mysqli_fetch_array($result);
        echo "
            <form class='editform' action='panelprocess.php' method='post'>
                <h1 id='formheader'>Editing Question '".$questionID."'</h1>
                <input type='hidden' name='questionid' value='".$questionID."'>
                <label>Question</label>
                <input type='text' name='question' value='".$entry["question"]."'>
                <label>Option 1</label>
                <input type='text' name='option1' value='".$entry["option1"]."'>
                <label>Option 2</label>
                <input type='text' name='option2' value='".$entry["option2"]."'>
                <label>Option 3</label>
                <input type='text' name='option3' value='".$entry["option3"]."'>
                <label>Option 4</label>
                <input type='text' name='option4' value='".$entry["option4"]."'>
                <label>Answer (Option #)</label>
                <input type='text' name='answer' value='".($entry["answer"]+1)."'>
                <input id='editsubmit' type='submit' value='Save Changes'></input>
            </form>
        ";
    } else if ($action == "create") {
        echo "
            <form class='editform' action='panelprocess.php' method='post'>
                <h1 id='formheader'>Creating New Question</h1>
                <input type='hidden' name='creatingnew' value='YES'>
                <label>Question ID</label>
                <input type='text' name='questionid' value='exampleID'>
                <label>Question</label>
                <input type='text' name='question' value='What is XYZ?'>
                <label>Option 1</label>
                <input type='text' name='option1' value='Choice One'>
                <label>Option 2</label>
                <input type='text' name='option2' value='Choice Two'>
                <label>Option 3</label>
                <input type='text' name='option3' value='Choice Three'>
                <label>Option 4</label>
                <input type='text' name='option4' value='Choice Four'>
                <label>Answer (Option #)</label>
                <input type='text' name='answer' value='Set this to the index for the correct option. (0-3)'>
                <input id='createsubmit' type='submit' value='Create'></input>
            </form>
        ";
    }
}
?>