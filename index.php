<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Styles/main.css">
    <title>Trivia</title>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Add in PHP File
    require_once 'pull_data.php';
    // Fetch quiz data from API
    $data = fetchQuizData();
    // Check if data was fetched successfully
    if ($data !== null) {
        // Check response_code then proceeed
        if ($data['response_code'] === 0) {
            // Display questions and answers
            foreach ($data['results'] as $result) {
                echo "<h1 class='Question'>" . htmlspecialchars_decode($result['question']) . "</h1>";
                echo "<table class='answer-table'>";
                
                // Extracting correct and incorrect answers
                $correctAnswer = htmlspecialchars_decode($result['correct_answer']);
                $incorrectAnswers = array_map('htmlspecialchars_decode', $result['incorrect_answers']);
                
                // Combining correct and incorrect answers
                $allAnswers = array_merge([$correctAnswer], $incorrectAnswers);
                
                // Shuffle the combined answers
                shuffle($allAnswers);
                
                // Counter for columns
                $columnCount = 0;
                
                // Loop through the answers to create table cells
                foreach ($allAnswers as $answer) {
                    // If it's the first cell in a row, create a new row
                    if ($columnCount % 2 == 0) {
                        echo "<tr>";
                    }
                    echo "<td class='answer-cell' onclick='revealAnswer(this)'>$answer</td>";
                    $columnCount++;
                    // If it's the last cell in a row, close the row
                    if ($columnCount % 2 == 0) {
                        echo "</tr>";
                    }
                }
                
                // If there's an odd number of answers, close the row
                if ($columnCount % 2 != 0) {
                    echo "</tr>";
                }
                echo "</table>";
            }            
        }
        else {
            if($data['response_code'] === 5) {
                echo "<h2 class=error>An error occurred: Rate Limit - Too many requests have occurred. Please try again in 5 seconds.</h1>";
            }
            else {
                echo "An unknown error occured.";
            }
        }
    } 
    ?>

<script>
    function revealAnswer(cell) {
        // Get the correct answer from PHP
        var correctAnswer = "<?php echo $correctAnswer; ?>";
        
        // Get the current background color of the cell
        var currentColor = cell.style.backgroundColor;
        
        // Check if the background color is already changed
        if (currentColor === "green" || currentColor === "red") {
            // Reload the page if the color is already changed
            window.location.reload();
            return; // Exit the function
        }
        
        // Get the table element
        var table = cell.closest('table');
        
        // Get all cells in the table
        var cells = table.querySelectorAll('.answer-cell');
        
        // Loop through each cell to compare and change color
        cells.forEach(function(cell) {
            if (cell.textContent.trim() === correctAnswer.trim()) {
                cell.style.backgroundColor = "green"; // Correct answer
            } else {
                cell.style.backgroundColor = "red"; // Incorrect answer
            }
        });
    }
</script>



</body>
</html>
