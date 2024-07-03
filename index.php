<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quiz</title>
    <link rel="icon" type="image/png" href="public/icon.png">
    <link href="public/style.css" rel="stylesheet"/>
</head>
<i>
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

        // Question array.
        $questionArr = array(
            "What is 1 + 1" => array(
                "options" => ["1","2","3","4"],
                "identifier" => "0",
            ),
            "What is the closest planet to the Earth?" => array(
                "options" => ["Pluto", "Mars", "Neptune", "The Sun"],
                "identifier" => "1",
            ),
            "How many continents are there?" => array(
                "options" => ["4", "5", "7", "9"],
                "identifier" => "2",
            ),
            "Who was the Prime Minister during WWII?" => array(
                "options" => ["Hillary Clinton", "Winston Churchill", "Percy Jackson", "Rishi Sunak"],
                "identifier" => "3",
            ),
            "What is the capital of Australia?" => array(
                "options" => ["Victoria", "Canberra", "Sydney", "Melbourne"],
                "identifier" => "4",
            ),
            "What does CPU stand for?" => array(
                "options" => ["Central Processing Unit", "Control Panel Unit", "It's waffle", "Core Processor Unit"],
                "identifier" => "5",
            ),
            "What is the largest internal organ in the human body?" => array(
                "options" => ["Lungs", "Heart", "Kidneys", "Liver"],
                "identifier" => "6",
            ),
            "What percentage of the Earth is covered by water?" => array(
                "options" => ["51%", "61%", "71%", "81%"],
                "identifier" => "7",
            ),
            "Who invented the World Wide Web?" => array(
                "options" => ["Tim Berners-Lee", "Stephen Hawking", "Alan Turing", "James D. Watson"],
                "identifier" => "8",
            ),
            "What is the main ingredient of gnocchi?" => array(
                "options" => ["Rice", "Potato", "Pasta", "Chocolate"],
                "identifier" => "9",
            ),
            "What does PHP stand for?" => array(
                "options" => ["Physics Hypertext Processor", "Private Hosting Protocol", "Personal Home Page", "Professional Hyperlink Program"],
                "identifier" => "10",
            ),
            "What is the capital of France?" => array(
                "options" => ["Berlin", "Madrid", "Turkmenistan", "Paris"],
                "identifier" => "11",
            ),
            "Who wrote 'To Kill a Mockingbird'?" => array(
                "options" => ["J.K Rowling", "Me", "Harper Lee", "Mark Twain"],
                "identifier" => "12",
            ),
            "What is the smallest country in the world?" => array(
                "options" => ["Monaco", "San Marino", "Vatican City", "Liechtenstein"],
                "identifier" => "13",
            ),
            "Which element has the chemical symbol 'O'?" => array(
                "options" => ["Oxygen", "Gold", "Silver", "Hydrogen"],
                "identifier" => "14",
            ),
            "In which year did the Titanic sink?" => array(
                "options" => ["1905", "1912", "1918", "1923"],
                "identifier" => "15",
            ),
            "Who painted the Mona Lisa?" => array(
                "options" => ["Pablo Picasso", "Leonardo da Vinci", "Michelangelo", "Vincent van Gogh"],
                "identifier" => "16",
            ),
            "Which is the largest ocean on Earth?" => array(
                "options" => ["Atlantic", "Indian", "Arctic", "Pacific"],
                "identifier" => "17",
            ),
            "How many states are there in the United States?" => array(
                "options" => ["48", "49", "50", "51"],
                "identifier" => "18",
            ),
            "Which country is known as the Land of the Rising Sun?" => array(
                "options" => ["China", "Japan", "South Korea", "Thailand"],
                "identifier" => "19",
            ) //20 questions in total
        );

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

            foreach ($options as $optionIndex => $option) {
                echo "<button class='flexbutton' onclick='chooseAns(this)' id='$identifier,$optionIndex,'>$option</button>";
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

        let answerdict = {
            "0": 1,
            "1": 1,
            "2": 2,
            "3": 1,
            "4": 1,
            "5": 0,
            "6": 3,
            "7": 2,
            "8": 0,
            "9": 1,
            "10": 2,
            "11": 3,
            "12": 2,
            "13": 2,
            "14": 0,
            "15": 1,
            "16": 1,
            "17": 3,
            "18": 2,
            "19": 1
        }

        let answertrack = 0;
        let score = 0;

        function chooseAns(element) {

            let idToParse = String(element.id);
            let idArray = idToParse.split(","); // Obtain questionNum and buttonNum from id - id stored as "questionNum,buttonNum"
            // Index 0 = questionNum
            // Index 1 = buttonNum

            let questionNum = idArray[0];
            let buttonNum = idArray[1];

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
                    buttons[buttonNum].classList.remove("incorrect");
                    buttons[buttonNum].classList.add("correct");
                } else {
                    element.setAttribute('style', 'background-color: #db3535 !important');
                    for (let i = 0; i < buttons.length; i++) {
                        if (answerdict[questionNum] == i) {
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


<!-- ne  ver eat alone
getting to yes
