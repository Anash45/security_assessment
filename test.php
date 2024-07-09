<?php

include './db_conn.php';
// include './functions.php';
// Read the JSON file
$jsonData = file_get_contents('data.json');

// Decode JSON data into an associative array
$data = json_decode($jsonData, true);

// Function to insert question with options into the database
function insertQuestionWithOptions($questionText, $options)
{
    global $conn;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert question
        $stmt = $conn->prepare("INSERT INTO questions (question_text) VALUES (?)");
        $stmt->bind_param("s", $questionText);
        $stmt->execute();
        $questionId = $stmt->insert_id;
        $stmt->close();

        // Insert options
        foreach ($options as $option) {
            $stmt = $conn->prepare("INSERT INTO options (question_id, option_text, recommendation, background) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $questionId, $option['text'], $option['recommendation'], $option['background']);
            $stmt->execute();
            $optionId = $stmt->insert_id;
            $stmt->close();

            // Insert references for each option
            foreach ($option['referenceLinks'] as $reference) {
                $stmtRef = $conn->prepare("INSERT INTO `references` (option_id, link_text, url) VALUES (?, ?, ?)");
                $stmtRef->bind_param("iss", $optionId, $reference['text'], $reference['url']);
                $stmtRef->execute();
                $stmtRef->close();
            }
        }

        // Commit transaction
        $conn->commit();

        return true;
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo "Failed to insert data: " . $e->getMessage();
        return false;
    }
}

// Assuming $conn is your database connection object (already established)

// Iterate through each question in the JSON data
foreach ($data as $questionText => $options) {
    // Prepare options array
    $optionsArray = [];

    // Iterate through each option for the current question
    foreach ($options as $optionText => $optionDetails) {
        // Prepare option data
        $optionData = [
            'text' => $optionText,
            'recommendation' => $optionDetails['expertRecommendation'],
            'background' => $optionDetails['background'],
            'referenceLinks' => $optionDetails['referenceLinks']
        ];
        $optionsArray[] = $optionData;
    }

    // Insert the question with its options into the database
    insertQuestionWithOptions($questionText, $optionsArray);
}

// Close database connection
$conn->close();
