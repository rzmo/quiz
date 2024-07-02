<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Knowledge Quiz</title>
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    <button id="refreshbutton" onclick="refresh()"><img id="refreshicon" src="public/refreshIcon.svg"></button>
    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz</h1>
        <form action="leaderboard.php">
                <button id="backbutton">See Past Results</button>
        </form>
        
        <?php

        $members = array(
            "What is 1 + 1"=>["1","2","3","4"],
            "What is the closest planet to the Earth?"=>["Pluto", "Mars", "Neptune", "The Sun"],
            "How many continents are there?"=>["4", "5", "7", "9"],
            "Who was the Prime Minister during WWII?"=>["Hillary Clinton", "Winston Churchill", "Percy Jackson", "Rishi Sunak"],
            "What is the capital of Australia?"=>["Victoria", "Canberra", "Sydney", "Melbourne"],
            "What does CPU stand for?"=>["Central Processing Unit", "Control Panel Unit", "It's waffle", "Core Processor Unit"],
            "What is the largest internal organ in the human body?"=>["Lungs", "Heart", "Kidneys", "Liver"],
            "What percentage of the Earth is covered by water?"=>["51%", "61%", "71%", "81%"],
            "Who invented the World Wide Web?"=>["Tim Berners-Lee", "Stephen Hawking", "Alan Turing", "James D. Watson"],
            "What is the main ingredient of gnocchi?"=>["Rice", "Potato", "Pasta", "Chocolate"]
        );

        $count = 0;
        foreach ($members as $q => $a) {
            echo "<h2 class='question'>$q</h2>";
            echo "<div class='flexcont' id='cont$count'>";
            echo "<button class='flexbutton' onclick='chooseAns(this)' id='$count,0'>$a[0]</button>";
            echo "<button class='flexbutton' onclick='chooseAns(this)' id='$count,1'>$a[1]</button>";
            echo "<button class='flexbutton' onclick='chooseAns(this)' id='$count,2'>$a[2]</button>";
            echo "<button class='flexbutton' onclick='chooseAns(this)' id='$count,3'>$a[3]</button>";
            echo "</div>";
            $count += 1;
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
            "9": 1
        }

        let answertrack = 0;
        let score = 0;
        console.log("abc")

        function chooseAns(element) {

            let idToParse = String(element.id);
            let idArray = idToParse.split(","); // Obtain questionNum and buttonNum from id - id stored as "questionNum,buttonNum"
            // Index 0 = questionNum
            // Index 1 = buttonNum

            let questionNum = idArray[0]
            let buttonNum = idArray[1]

            let group = document.getElementById("cont"+questionNum);
            if (group) {
                let buttons = group.getElementsByClassName("flexbutton");
                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = "true";
                }
                answertrack = answertrack + 1;
                console.log(answertrack)
                if (answerdict[questionNum] == buttonNum) {
                    element.style.backgroundColor = "#47de60";
                    score = score + 1;
                    let scoreTracker = document.getElementById("scoreinput");
                    scoreTracker.value = score/2
                } else {
                    element.style.backgroundColor = "#db3535";
                }
                
                if (answertrack >= 20) {
                    validateName();
                }
                return "Clicked the " + buttonNum + " button for question " + questionNum;

            }
        }

        console.log("abc")
        document.addEventListener("DOMContentLoaded", (event) => {
            console.log("xyz")

            // Onclick function cannot be defined within the html, since function would be out of scope and 'not defined', thus add function after.
            let allButtons = document.getElementsByClassName("flexbutton");
            console.log(allButtons)
            for (let ind = 0; ind < allButtons.length; ind++) {
                console.log(ind + " => " + allButtons[ind].id)
                allButtons[ind].addEventListener("click", function(){
                    chooseAns(allButtons[ind]);
                });
                    
            }

        });

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
                if (answertrack >= 20) {
                    validationText.innerHTML = "Press 'Submit' to save your results.";
                    submitButton.disabled = false;
                } else {
                    validationText.innerHTML = "Please answer all the questions before you save your results.";
                    submitButton.disabled = true;
                }
            }
        }

        function refresh() {
            console.log("Refreshing")
        }

    </script>
</body>
</html>