<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>General Knowledge Quiz</title>
    <link href="style.css" rel="stylesheet" />
</head>
<body>

    <div class="maincont">
        <h1>General Knowledge Quiz</h1>

        <div class="subcont">
            <p id="questionlabel" class="label">QuestionLabel</p>
            <p id="answerlabel" class="label">AnswerLabel</p>
            <p id="scorelabel" class="label">ScoreLabel</p>

            <input id="answercheckbox" type="checkbox">
            <form id="answerdropdown" action="#">
                <select name="Choose Your Answer">
                    <option value="ans1">Answer1</option>
                    <option value="ans2">Answer2</option>
                    <option value="ans3">Answer3</option>
                    <option value="ans4">Answer4</option>
                </select>
            </form>
            <input id="answertext" type="text">

        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>

    window.addEventListener('DOMContentLoaded', function() {

    // Initialise selection of question & answer labels
    let questionLabel = document.getElementById("questionlabel")
    let answerLabel = document.getElementById("answerlabel")
    let scoreLabel = document.getElementById("scorelabel")

    questionLabel.innerHTML = "Q Init"
    answerLabel.innerHTML = "A Init"
    scoreLabel.innerHTML = "# Marks"

    // Hide by default all input options to allow for dynamic input type based on question
    // (i.e. allowing some questions to be multiple choice, others text input etc, others checkbox)
    let checkboxInput = document.getElementById("answercheckbox");
    let dropdownInput = document.getElementById("answerdropdown");
    let textInput = document.getElementById("answertext");

    checkboxInput.style.display = "none";
    dropdownInput.style.display = "none";
    textInput.style.display = "none";

    function toggleInput(name) {
        let choice = null
        if (name == "checkbox") {choice = checkboxInput}        
        if (name == "dropdown") {choice = dropdownInput}
        if (name == "text") {choice = textInput}
        if (choice && name) {
            if (choice.style.display == "none") {
                choice.style.display = "block";
                console.log("made" + choice + " - block")
            } else {
                choice.style.display = "none";
                console.log("made" + choice + " - none")
            }
        } else {
            return null;
        }
    }

    function getQuestion(index, type) {
        let question = "<?php echo getQuestion("question");?>";
        let answer = "<?php echo getQuestion("answer");?>";
        let value = "<?php echo getQuestion("value");?>";

        let array = [];

        if (type == "question") {
            array = question;
        } else if (type == "answer") {
            array = answer;
        } else if (type= "value") {
            array = value;
        }

        return array;
    }

    questionLabel.innerHTML = getQuestion(1, "question");
    answerLabel.innerHTML = getQuestion(1, "answer");
    scoreLabel.innerHTML = getQuestion(1, "value") + " Marks";

});

    </script>
</body>
</html>

<?php
    function getQuestion($type) {

        $db_username = "root";
        $db_password = "";
        $host = "localhost";
        $database = "quiz_db";
    
        $server = mysqli_connect($host, $db_username, $db_password);
        $connection = mysqli_select_db($server, $database);
        
        $query = "SELECT * FROM `questions`";
        $result = mysqli_query($server, $query);


        if ( !$result ) {
            echo mysqli_error($server);
            die;
        } else {
            $row_cnt = $result->num_rows;
            if ($type == "index") {
                return $row_cnt;
            } else {
                return mysqli_fetch_array($result);
            }
        }
    }
?>