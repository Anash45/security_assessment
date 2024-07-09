<?php
// Function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['admin_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function isUser()
{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'user';
}


// Function to insert question, options, and references into the database
function insertQuestionWithOptions($questionType, $questionText, $options)
{
    global $conn;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert question
        $stmt = $conn->prepare("INSERT INTO questions (question_type_id, question_text) VALUES (?, ?)");
        $stmt->bind_param("is", $questionType, $questionText);
        $stmt->execute();
        $questionId = $stmt->insert_id;
        $stmt->close();

        // Insert options
        foreach ($options as $option) {
            $stmt = $conn->prepare("INSERT INTO options (question_id, option_text, recommendation, background) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $questionId, $option['text'], $option['recommendation'], $option['background']);
            $stmt->execute();
            $optionId = $stmt->insert_id;

            // Insert references for each option
            foreach ($option['references'] as $reference) {
                $stmtRef = $conn->prepare("INSERT INTO `references` (option_id, link_text, url) VALUES (?, ?, ?)");
                $stmtRef->bind_param("iss", $optionId, $reference['text'], $reference['url']);
                $stmtRef->execute();
                $stmtRef->close();
            }

            $stmt->close();
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

    // Close connection
    $conn->close();
}

function updateQuestionWithOptions($questionId, $questionType, $questionText, $options)
{
    global $conn;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert question
        $stmt = $conn->prepare("UPDATE questions SET question_text = ?, question_type_id = ? WHERE question_id = ?");
        $stmt->bind_param("sii", $questionText, $questionType, $questionId,);
        $stmt->execute();
        $stmt->close();


        $stmtDelRef = $conn->prepare("DELETE FROM `references` WHERE option_id IN (SELECT option_id FROM options WHERE question_id = ?)");
        $stmtDelRef->bind_param("i", $questionId);
        $stmtDelRef->execute();
        $stmtDelRef->close();

        $stmtDelOpt = $conn->prepare("DELETE FROM `options` WHERE question_id = ?");
        $stmtDelOpt->bind_param("i", $questionId);
        $stmtDelOpt->execute();
        $stmtDelOpt->close();


        // Insert options
        foreach ($options as $option) {
            $stmt = $conn->prepare("INSERT INTO options (question_id, option_text, recommendation, background) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $questionId, $option['text'], $option['recommendation'], $option['background']);
            $stmt->execute();
            $optionId = $stmt->insert_id;

            // Insert references for each option
            foreach ($option['references'] as $reference) {
                $stmtRef = $conn->prepare("INSERT INTO `references` (option_id, link_text, url) VALUES (?, ?, ?)");
                $stmtRef->bind_param("iss", $optionId, $reference['text'], $reference['url']);
                $stmtRef->execute();
                $stmtRef->close();
            }

            $stmt->close();
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

    // Close connection
    $conn->close();
}