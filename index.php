<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quiz</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <a href="panel.php?">
        <img id="wficon" src="public/wfIcon.svg">
    </a>
    <a href="https://docs.google.com/document/d/1AUrHIvbGsYO7f4EYAtMFNuGt9jjg0Bvz6orIihyAkIc/edit?usp=sharing" target="_blank">
        <img id="madeicon" src="public/madeIcon.svg">
    </a>

    <button id="refreshbutton" onclick="refresh()"><img id="refreshicon" src="public/refreshIcon.svg"></button>
    <form id="trophycta" action="leaderboard.php">
        <button class="trophybutton"><img id="iconsvg" src="public/trophyIcon.svg"></button>
    </form>
    <button id="themebutton" onclick="darkMode()"><img id="iconsvg" src="public/moonIcon.svg"></button>
    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz</h1>
        
        <?php

        // Number of questions to serve the user. Keep in mind if the URL specifies question quantity, this will be overridden.
        $numberOfQuestions = 5;

        // Fetch questions from DB
        $server = mysqli_connect("localhost", "root", "");
        $connection = mysqli_select_db($server, "quiz_db");

        $query = "SELECT `question`, `questionid`, `option1`, `option2`, `option3`, `option4` FROM `questions`";
        $result = mysqli_query($server, $query);

        $questionArr = array();

        // Fetch each row and format it into the desired structure
        while ($row = mysqli_fetch_assoc($result)) {
            $question = array(
                'options' => array(
                    $row['option1'],
                    $row['option2'],
                    $row['option3'],
                    $row['option4'],
                ),
                'identifier' => $row['questionid'],
            );
    
            // Add question to the main array
            $questionArr[$row['question']] = $question;
        }

        $query = "SELECT `questionid`, `answer` FROM `questions`";
        $result = mysqli_query($server, $query);

        $answerdict = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $answerdict[$row['questionid']] = $row['answer'];
        }

        mysqli_close($server);

        $answerdict_json = json_encode($answerdict);

        echo "<script>let answerdict = " . $answerdict_json . ";</script>";

        if (isset($_GET['number'])) {
            $numberOfQuestions = $_GET['number'];
            echo "<script>console.log('Number of Qs => $numberOfQuestions - pulled from URL')</script>";
        } else {
            echo "<script>console.log('Number of Qs => $numberOfQuestions - default value defined in PHP')</script>";
        }

        if ($numberOfQuestions > sizeof($questionArr)) {
            $numberOfQuestions = sizeof($questionArr);
            echo "<script>console.log('Number of Qs => $numberOfQuestions - limited by maximum count')</script>";
        }

        echo "<script>const numberOfQuestions = $numberOfQuestions;</script>";

        function shuffle_assoc(&$array) {
            $keys = array_keys($array);
            shuffle($keys);
            $new = [];
            foreach ($keys as $key) {
                $new[$key] = $array[$key];
            }
            $array = $new;
        }

        shuffle_assoc($questionArr);
        $questionArr = array_slice($questionArr, 0, $numberOfQuestions);

        foreach ($questionArr as $q => $a) {

            $options = $a['options'];
            $identifier = $a['identifier'];

            echo "<h2 class='question'>$q</h2>";
            echo "<div class='flexcont' id='cont$identifier'>";

            $indexArr = [0,1,2,3];
            shuffle($indexArr);
            $count = 0;
            foreach ($indexArr as $i) {
                echo "<button class='flexbutton' onclick='chooseAns(this)' id='$identifier,$i,$count'>$options[$i]</button>";
                $count += 1;
            }

            echo "</div>";
        }

        ?>

        <h2 class='question'>Enter your name to save your results.</h2>
        <form action="leaderboard.php" method="POST">
                <input id="nameinput" type="text" name="name">
                <input id="scoreinput" type="hidden" name="score" value="0"/>
                <button id="submitbutton" disabled>Submit</button>
        </form>
        <h2 id="validationText">Please enter your name.</h2>

    </div>
    
    <script>

        console.log("JS recognises Number of Qs => " + numberOfQuestions + " from PHP");

        let answertrack = 0;
        let score = 0;

        function chooseAns(element) {

            let idToParse = String(element.id);
            let idArray = idToParse.split(","); // Obtain questionNum and buttonNum from id - id stored as "questionNum,buttonNum"
            // Index 0 = questionNum
            // Index 1 = buttonNum

            let questionNum = idArray[0];
            let buttonNum = idArray[1];
            let orderFromleft = idArray[2];

            let group = document.getElementById("cont"+questionNum);
            if (group) {
                let buttons = group.getElementsByClassName("flexbutton");
                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = "true";
                }
                answertrack = answertrack + 1;
                console.log("Answered "+answertrack+"/"+numberOfQuestions);
                if (answerdict[questionNum] == buttonNum) {
                    element.setAttribute('style', 'background-color: #47de60 !important');
                    score = score + 1;
                    let scoreTracker = document.getElementById("scoreinput");
                    scoreTracker.value = score;
                    for (let i = 0; i < buttons.length; i++) {
                        buttons[i].classList.add("incorrect");
                    }
                    buttons[orderFromleft].classList.remove("incorrect");
                    buttons[orderFromleft].classList.add("correct");
                } else {
                    element.setAttribute('style', 'background-color: #db3535 !important');
                    for (let i = 0; i < buttons.length; i++) {
                        if (answerdict[questionNum] == buttons[i].id.split(",")[1]) {
                            buttons[i].classList.add("correct");
                        } else {
                            buttons[i].classList.add("incorrect");
                        }
                    }   
                }
                
                if (answertrack >= numberOfQuestions) {
                    validateName();
                }
                return "Clicked the " + buttonNum + " button for question " + questionNum;

            }
        }

        let nameInput = document.getElementById("nameinput");
        nameInput.addEventListener("input", validateName);

        function validateName() {
            let validationText = document.getElementById("validationText");
            let submitButton = document.getElementById("submitbutton")
            let nameInput = document.getElementById("nameinput");
            name = nameInput.value;
            if (name == "" || name == " " || name.length == 0) {
                validationText.innerHTML = "Please enter your name.";
                submitButton.disabled = true;
            } else if (name.length < 3) {
                validationText.innerHTML = "Your name cannot be shorter than 3 characters.";
                submitButton.disabled = true;
            } else if (name.length > 20) {
                validationText.innerHTML = "Your name cannot be longer than 20 characters.";
                submitButton.disabled = true;
            } else {
                if (answertrack >= numberOfQuestions) {
                    validationText.innerHTML = "Press 'Submit' to save your results.";
                    submitButton.disabled = false;
                } else {
                    validationText.innerHTML = "Please answer all the questions before you save your results.";
                    submitButton.disabled = true;
                }
            }
        }

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