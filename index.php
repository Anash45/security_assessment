<?php
require_once './db_conn.php';
require './functions.php';

if (!isAdmin()) {
    header('location: login.php');
}
$info = '';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM questions WHERE question_id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $info = '<div class="alert alert-success" role="alert">Question deleted successfully.</div>';
    } else {
        $info = '<div class="alert alert-danger" role="alert">Error deleting question.</div>';
    }
}

// Fetch all questions from the database
$query = "SELECT q.question_id, q.question_text, qt.type 
          FROM questions q
          INNER JOIN question_types qt ON q.question_type_id = qt.id";
$result = mysqli_query($conn, $query);

// Check if there are any questions to display
if (!$result || mysqli_num_rows($result) == 0) {
    $info = '<div class="alert alert-warning" role="alert">No questions found.</div>';
}

$page = 'questions';
?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>Dashboard - Security Assessment</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css"
            integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.material.min.css"
            integrity="sha512-xvrm5KqgBtR7kE0ehXfSSkQvzArzm/iBSx6aXcINru5dM0YWCaqrHfsN1PHCQBgL03/7fJHqypWZoA5w0T6lMA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="./assets/css/style.css?v=2">
    </head>

    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <?php include './sidebar.php'; ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="d-flex align-items-center pt-3 mb-4 justify-content-between gap-2 flex-sm-wrap flex-wrap">
                        <h1 class="section-title fw-bold text-center mb-0 text-white">
                            <span class="lang-en">Questions</span>
                        </h1>
                        <a href="add_question.php" class="btn">Add Question</a>
                    </div>
                    <?php echo $info; ?>
                    <table class="table table-bordered text-white">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the fetched questions and display them
                            while ($row = mysqli_fetch_assoc($result)) {
                                $questionId = $row['question_id'];
                                $questionText = $row['question_text'];
                                echo "<tr>
                            <td>{$questionId}</td>
                            <td>{$row['type']}</td>
                            <td>{$questionText}</td>
                            <td class='text-center'>
                                <a href='edit_question.php?id={$questionId}' class='text-white px-2 py-1 rounded bg-primary'><i class='fa fa-edit'></i></a>
                                <a href='?delete_id={$questionId}' class='text-white px-2 py-1 rounded bg-danger' onclick='return confirm(\"Are you sure you want to delete this question?\");'><i class='fa fa-trash'></i></a>
                            </td>
                          </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
        <script src="./assets/js/jquery-3.6.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
            integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="./assets/js/bootstrap.bundle.min.js"></script>
        <!-- Modal -->
        <script>
            $(document).ready(function () {
                $('.table').DataTable({
                    "pageLength": 25
                });
            });
        </script>
        <script src="./assets/js/script.js?v=2"></script>
    </body>

</html>