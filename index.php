<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>General Knowledge Quiz</title>
    <link href="style.css" rel="stylesheet" />
</head>
<body>
    
    <div class="maincontainer">

        <h1>General Knowledge Quiz</h1>
        <div class="subcontainer">

            <p id="questionlabel" class="label">QuestionLabel</p>

            <div id="answerSection">
                <input id="answertext" type="text" style="display:none;">
                <form id="answerdropdown" action="#" style="display:none;">
                    <select id="dropdownOptions" name="Choose Your Answer"></select>
                </form>
                <input id="answercheckbox" type="checkbox" style="display:none;">
            </div>

            <button id="nextButton">Next</button>

            <div id="userNameSection" style="display:none;">
                <label for="userName">Enter your name:</label>
                <input id="userName" type="text">
                <button id="submitName">Submit</button>
            </div>

        </div>

    </div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>

    questions = {
        "1":["What is 1+1?","text",[]],
        "2":["Third letter of alphabet?","dropdown",["a","b","c"]],
        "3":["Is 25 a multiple of 5?","checkbox",[]],
        "4":["What is 1+1?","text",[]],
        "5":["What is 1+1?","text",[]],
        "6":["What is 1+1?","text",[]],
        "7":["What is 1+1?","text",[]],
        "8":["What is 1+1?","text",[]],
        "9":["What is 1+1?","text",[]],
        "10":["What is 1+1?","text",[]]
    }

    answerdict = {
        "1":"2",
        "2":"c",
        "3":true,
        "4":"2",
        "5":"2",
        "6":"2",
        "7":"2",
        "8":"2",
        "9":"2",
        "10":"2"
    }

    let currentQuestionIndex = 1;
    let answers = [];

    let questionLabel = document.getElementById('questionlabel');
    let answerText = document.getElementById('answertext');
    let answerDropdown = document.getElementById('answerdropdown');
    let dropdownOptions = document.getElementById('dropdownOptions');
    let answerCheckbox = document.getElementById('answercheckbox');
    let nextButton = document.getElementById('nextButton');
    let userNameSection = document.getElementById('userNameSection');
    let userNameInput = document.getElementById('userName');
    let submitNameButton = document.getElementById('submitName');

    function displayQuestion() {
        if (currentQuestionIndex <= Object.keys(questions).length) {
            let question = questions[currentQuestionIndex];
            questionLabel.innerHTML = question[0];
        
            answerText.style.display = 'none';
            answerDropdown.style.display = 'none';
            answerCheckbox.style.display = 'none';

            if (question[1] === 'text') {
                answerText.style.display = 'inline-block';
            } else if (question[1] === 'dropdown') {
                answerDropdown.style.display = 'inline-block';
                dropdownOptions.innerHTML = '';
                question[2].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    dropdownOptions.appendChild(optionElement);
                });
            } else if (question[1] === 'checkbox') {
                answerCheckbox.style.display = 'inline-block';
            }
        } else {
            questionLabel.innerHTML = 'All questions answered. Please enter your name.';
            document.getElementById('answerSection').style.display = 'none';
            userNameSection.style.display = 'inline-block';
            nextButton.style.display = 'none';
        }
    }

    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex <= Object.keys(questions).length) {
            const question = questions[currentQuestionIndex];
            let answer;

            if (question[1] === 'text') {
                answer = answerText.value;
            } else if (question[1] === 'dropdown') {
                answer = dropdownOptions.value;
            } else if (question[1] === 'checkbox') {
                answer = answerCheckbox.checked;
            }

            answers.push({
                question: question[0],
                answer: answer
            });

            currentQuestionIndex++;
            displayQuestion();
        }
    });

    submitNameButton.addEventListener('click', () => {
        const userName = userNameInput.value;
        answers.push({
            userName: userName
        });

        console.log(answers);

        let person = answers[10].userName;
        let score = 0;
        for (let i = 1; i < Object.keys(answerdict).length+1; i++) {
            console.log("( " + answers[i-1].answer + " || " + answerdict[i] + ") Verdict: " + (String(answers[i-1].answer) == String(answerdict[i])))
            if (String(answers[i-1].answer) == String(answerdict[i])) {

                score += 1;
            }
        }
        console.log(person + " got a score of " + score)



        alert('Thank you for completing the quiz!');
        currentQuestionIndex = 1;
        answers = [];
        document.getElementById('answerSection').style.display = 'block';
        userNameSection.style.display = 'none';
        nextButton.style.display = 'inline-block';
        displayQuestion();


    });

    displayQuestion();

    </script>
</body>
</html>

<?php
    /*function getQuestion($type) {

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
    }*/
    
?>
