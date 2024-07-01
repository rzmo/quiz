<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Knowledge Quiz</title>
    <link href="public/style.css" rel="stylesheet"/>
</head>
<body>
    
    <div class="maincontainer">

        <h1 id="header">General Knowledge Quiz</h1>
        
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
            echo "<button class='flexbutton' onclick='chooseAns($count, 0, this)'>$a[0]</button>";
            echo "<button class='flexbutton' onclick='chooseAns($count, 1, this)'>$a[1]</button>";
            echo "<button class='flexbutton' onclick='chooseAns($count, 2, this)'>$a[2]</button>";
            echo "<button class='flexbutton' onclick='chooseAns($count, 3, this)'>$a[3]</button>";
            echo "</div>";
            $count += 1;
        }

        ?>

        <h2 class='question'>Enter your name to save your results.</h2>
        <form action="public/leaderboard.php" method="POST">
                <input id="nameinput" type="text" name="name">
                <input id="scoreinput" type="hidden" name="score" value="0"/>
                <button id="submitbutton">Submit</button>
        </form>

    </div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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

        let score = 0;

        function chooseAns(questionNum, buttonNum, clickedButton) {

            let group = document.getElementById("cont"+questionNum);
            let buttons = group.getElementsByClassName("flexbutton");
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].disabled = "true";
            }​;
            if (answerdict[questionNum] == buttonNum) {
                clickedbutton.style.backgroundColor = "green";
                score += 1;
            } else {
                clickedbutton.style.backgroundColor = "red";
            };

            return "Clicked the " + buttonNum + " button for question " + questionNum;

        }

    </script>
</body>
</html>