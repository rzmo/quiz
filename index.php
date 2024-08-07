<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quiz</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <button class="difficulty" id="easybtn" onclick="newQuiz('easy', 'N/A')">
        EASY   
    </button>
    <button class="difficulty" id="medbtn" onclick="newQuiz('medium', 'N/A')">
        MEDIUM   
    </button>
    <button class="difficulty" id="hardbtn" onclick="newQuiz('hard', 'N/A')">
        HARD 
    </button>
    <button class="countgr" id="count5" onclick="newQuiz('N/A', '5')">
        5   
    </button>
    <button class="countgr" id="count10" onclick="newQuiz('N/A', '10')">
        10   
    </button>
    <button class="countgr" id="count25" onclick="newQuiz('N/A', '25')">
        25 
    </button>
    <button class="countgr" id="count50" onclick="newQuiz('N/A', '50')">
        50 
    </button>

    <script>
        function newQuiz(inpDifficulty, inpNumber) {
            let url = 'index.php';
            if (inpNumber != "N/A") {
                if ("<?php if (isset($_GET["difficulty"])) {echo ''.$_GET["difficulty"].'';} else {echo 'easy';};?>" == "easy" || "<?php if (isset($_GET["difficulty"])) {echo ''.$_GET["difficulty"].'';} else {echo 'easy';};?>" == "medium" || "<?php if (isset($_GET["difficulty"])) {echo ''.$_GET["difficulty"].'';} else {echo 'easy';};?>" == "hard") {
                    url = url + '?number=' + inpNumber;
                    url = url + '&difficulty=' + "<?php if (isset($_GET["difficulty"])) {echo ''.$_GET["difficulty"].'';} else {echo 'easy';};?>";
                }
            } else {
                if (variableNumberOfQuestions) {
                    url = url + '?difficulty=' + inpDifficulty;
                    url = url + '&number=' + variableNumberOfQuestions
                }
            }
            window.location.href = url;
        }
    </script>

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
    <form id="trophycta" action="leaderboard.php">
        <button class="trophybutton"><img id="iconsvg" src="public/trophyIcon.svg"></button>
    </form>
    <button id="themebutton" onclick="darkMode()"><img id="iconsvg" src="public/moonIcon.svg"></button>
    <button id="downbutton" onclick="scrollToBottom()" hidden><img id="iconsvg" src="public/downIcon.svg"></button>

    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz</h1>
        
        <?php


$db_username = "root";
$db_password = "";
$host = "localhost";
$database = "quiz_db";

$server = mysqli_connect($host, $db_username, $db_password);
$connection = mysqli_select_db($server, $database);

$result = mysqli_query($server, "SHOW TABLES LIKE 'questions';");
if ($result->num_rows < 1) {
    echo "<script>console.log('Generating new tables')</script>";
    
    $queries = array(
        "CREATE TABLE `querylog` (`action` text NOT NULL, `user` text NOT NULL, `date` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        "CREATE TABLE `questions` (`question` text NOT NULL,`questionid` text NOT NULL,`option1` text NOT NULL,`option2` text NOT NULL,`option3` text NOT NULL,`option4` text NOT NULL,`answer` int(11) NOT NULL,`difficulty` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        "CREATE TABLE `readablelog` (`action` text NOT NULL,`qid` text NOT NULL,`content` text NOT NULL,`date` text NOT NULL,`user` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        "CREATE TABLE `scores` (`name` varchar(20) DEFAULT NULL,`score` int(11) NOT NULL,`difficulty` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
        "CREATE TABLE `users` (`name` text NOT NULL,`passhash` text NOT NULL,`salt` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
    );

    foreach ($queries as $sql) {
        mysqli_query($server, $sql);
    }

} else {
    echo "<script>console.log('NOT generating new tables')</script>";
}

        // Number of questions to serve the user. Keep in mind if the URL specifies question quantity, this will be overridden.
        $numberOfQuestions = 10;

        // URL QUESTION DIFFICULTY SETTING
        if (isset($_GET['difficulty'])) {
            $questionDifficulty = $_GET['difficulty'];
            $query = "SELECT `question`, `questionid`, `option1`, `option2`, `option3`, `option4` FROM `questions` WHERE difficulty = '$questionDifficulty'";
            echo "<script>addEventListener('DOMContentLoaded', (event) => {document.getElementById('difficultyinput').value = '$questionDifficulty';});</script>";
        } else {
            $query = "SELECT `question`, `questionid`, `option1`, `option2`, `option3`, `option4` FROM `questions`";
        }

        // Fetch questions from DB
        $server = mysqli_connect("localhost", "root", "");
        $connection = mysqli_select_db($server, "quiz_db");

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
                'identifier' => $row['questionid']
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

        // URL QUESTION NUMBER SETTING
        if (isset($_GET['number'])) {
            $numberOfQuestions = $_GET['number'];
            echo "<script>console.log('Number of Qs => $numberOfQuestions - pulled from URL')</script>";
            echo "<script>let variableNumberOfQuestions = $numberOfQuestions;</script>";
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

        $qSize = 0;
        foreach ($questionArr as $q => $a) {

            $qSize += 1;
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

        if ($qSize > 9) {
            echo "<script>document.getElementById('downbutton').hidden = false;</script>";
        };

        ?>

        <h2 class='question'>Enter your name to save your results.</h2>
        <form action="leaderboard.php" method="POST">
                <input id="nameinput" type="text" name="name">
                <input id="scoreinput" type="hidden" name="score" value="0">
                <input id="difficultyinput" type="hidden" name="difficulty" value="">
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

<?php

if (isset($_POST["resetHash"])) {

    if ($_POST["resetHash"] == "yes") {
        echo "<script>console.log('logging out');</script>";
        echo "<script>sessionStorage.removeItem('username');sessionStorage.removeItem('masterhash');sessionStorage.removeItem('passhash');</script>";
    }
}

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