<?php
require_once './db_conn.php';
require './functions.php';

if (!isAdmin()) {
    header('location: login.php');
}
$info = '';
// Check if the form is submitted

if (isset($_REQUEST['id'])) {
    $qID = $_REQUEST['id'];

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the question text
    $questionType = $_POST['questionType'];
    $questionText = $_POST['questionText'];

    // Fetch options and their details
    $options = [];
    for ($i = 1; $i <= 5; $i++) {
        $optionText = $_POST['option' . $i . 'Text'];
        $recommendation = $_POST['option' . $i . 'Recommendation'];
        $background = $_POST['option' . $i . 'Background'];

        $references = [];
        if (!empty($_POST['option' . $i . 'Reference1Text']) && !empty($_POST['option' . $i . 'Reference1Url'])) {
            $references[] = [
                'text' => $_POST['option' . $i . 'Reference1Text'],
                'url' => $_POST['option' . $i . 'Reference1Url']
            ];
        }
        if (!empty($_POST['option' . $i . 'Reference2Text']) && !empty($_POST['option' . $i . 'Reference2Url'])) {
            $references[] = [
                'text' => $_POST['option' . $i . 'Reference2Text'],
                'url' => $_POST['option' . $i . 'Reference2Url']
            ];
        }

        $options[] = [
            'text' => $optionText,
            'recommendation' => $recommendation,
            'background' => $background,
            'references' => $references
        ];
    }

    // Insert data into the database
    if (insertQuestionWithOptions($questionType, $questionText, $options)) {
        $info = '<div class="alert alert-success" role="alert">Question added successfully</div>';
    } else {
        $info = '<div class="alert alert-danger" role="alert">Failed to add question</div>';
    }
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
        <link rel="stylesheet" href="./assets/css/style.css?v=2">
    </head>

    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <?php include './sidebar.php'; ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="d-flex align-items-center pt-3 mb-1 justify-content-center gap-2 flex-sm-wrap flex-wrap">
                        <h1 class="section-title fw-bold text-center mb-0 text-white text-center">
                            <span class="lang-en">Add Question</span>
                        </h1>
                    </div>
                    <?php echo $info; ?>
                    <form class="needs-validation pb-5" method="POST" action="" novalidate>
                        <!-- Question Text -->
                        <div class="mb-3">
                            <label for="questionType" class="form-label fw-bold text-white">
                                <h3 class="fw-bold">Question Type</h3>
                            </label>
                            <select required class="form-control bg-transparent" id="questionType" name="questionType">
                                <option value="">Select a question type</option>
                                <?php
                                $sql4 = "SELECT * FROM question_types";
                                $result4 = $conn->query($sql4);
                                if (mysqli_num_rows($result4) > 0) {
                                    while ($row4 = mysqli_fetch_assoc($result4)) {
                                        echo '<option value="' . $row4['id'] . '">' . $row4['type'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="questionText" class="form-label fw-bold text-white">
                                <h3 class="fw-bold">Question</h3>
                            </label>
                            <input required type="text" class="form-control bg-transparent" id="questionText"
                                name="questionText" placeholder="Enter the question text">
                        </div>
                        <!-- Options -->
                        <div class="mb-3 ps-4">
                            <label class="form-label fw-bold text-white">
                                <h4 class="fw-bold">Options</h4>
                            </label>
                            <!-- Option 1 -->
                            <div class="mb-4">
                                <label for="option1Text" class="form-label fw-semibold text-white">Option 1 - Very
                                    Low</label>
                                <input required type="text" class="form-control bg-transparent" id="option1Text"
                                    name="option1Text" placeholder="Enter option 1 text">
                                <div class="ps-4 border-bottom border-white pb-4">
                                    <!-- Expert Recommendation -->
                                    <label for="option1Recommendation" class="form-label mt-2 text-white">Expert
                                        Recommendation</label>
                                    <textarea required class="form-control bg-transparent" id="option1Recommendation"
                                        name="option1Recommendation" rows="3"
                                        placeholder="Enter expert recommendation"></textarea>
                                    <!-- Background -->
                                    <label for="option1Background" class="form-label mt-2 text-white">Background</label>
                                    <textarea required class="form-control bg-transparent" id="option1Background"
                                        name="option1Background" rows="3"
                                        placeholder="Enter background information"></textarea>
                                    <!-- References -->
                                    <div class="mb-3 mt-2">
                                        <label for="option1Reference1Text" class="form-label text-white">Reference
                                            1</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option1Reference1Text" name="option1Reference1Text"
                                            placeholder="Enter reference 1 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option1Reference1Url" name="option1Reference1Url"
                                            placeholder="Enter reference 1 URL">
                                    </div>
                                    <div class="mb-3">
                                        <label for="option1Reference2Text" class="form-label text-white">Reference
                                            2</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option1Reference2Text" name="option1Reference2Text"
                                            placeholder="Enter reference 2 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option1Reference2Url" name="option1Reference2Url"
                                            placeholder="Enter reference 2 URL">
                                    </div>
                                </div>
                            </div>
                            <!-- Option 2 -->
                            <div class="mb-4">
                                <label for="option2Text" class="form-label fw-semibold text-white">Option 2 -
                                    Low</label>
                                <input required type="text" class="form-control bg-transparent" id="option2Text"
                                    name="option2Text" placeholder="Enter option 2 text">
                                <div class="ps-4 border-bottom border-white pb-4">
                                    <!-- Expert Recommendation -->
                                    <label for="option2Recommendation" class="form-label mt-2 text-white">Expert
                                        Recommendation</label>
                                    <textarea required class="form-control bg-transparent" id="option2Recommendation"
                                        name="option2Recommendation" rows="3"
                                        placeholder="Enter expert recommendation"></textarea>
                                    <!-- Background -->
                                    <label for="option2Background" class="form-label mt-2 text-white">Background</label>
                                    <textarea required class="form-control bg-transparent" id="option2Background"
                                        name="option2Background" rows="3"
                                        placeholder="Enter background information"></textarea>
                                    <!-- References -->
                                    <div class="mb-3 mt-2">
                                        <label for="option2Reference1Text" class="form-label text-white">Reference
                                            1</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option2Reference1Text" name="option2Reference1Text"
                                            placeholder="Enter reference 1 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option2Reference1Url" name="option2Reference1Url"
                                            placeholder="Enter reference 1 URL">
                                    </div>
                                    <div class="mb-3">
                                        <label for="option2Reference2Text" class="form-label text-white">Reference
                                            2</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option2Reference2Text" name="option2Reference2Text"
                                            placeholder="Enter reference 2 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option2Reference2Url" name="option2Reference2Url"
                                            placeholder="Enter reference 2 URL">
                                    </div>
                                </div>
                            </div>
                            <!-- Repeat for Options 3, 4, and 5 -->
                            <div class="mb-4">
                                <label for="option3Text" class="form-label fw-semibold text-white">Option 3 -
                                    Medium</label>
                                <input required type="text" class="form-control bg-transparent" id="option3Text"
                                    name="option3Text" placeholder="Enter option 3 text">
                                <div class="ps-4 border-bottom border-white pb-4">
                                    <!-- Expert Recommendation -->
                                    <label for="option3Recommendation" class="form-label mt-2 text-white">Expert
                                        Recommendation</label>
                                    <textarea required class="form-control bg-transparent" id="option3Recommendation"
                                        name="option3Recommendation" rows="3"
                                        placeholder="Enter expert recommendation"></textarea>
                                    <!-- Background -->
                                    <label for="option3Background" class="form-label mt-2 text-white">Background</label>
                                    <textarea required class="form-control bg-transparent" id="option3Background"
                                        name="option3Background" rows="3"
                                        placeholder="Enter background information"></textarea>
                                    <!-- References -->
                                    <div class="mb-3 mt-2">
                                        <label for="option3Reference1Text" class="form-label text-white">Reference
                                            1</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option3Reference1Text" name="option3Reference1Text"
                                            placeholder="Enter reference 1 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option3Reference1Url" name="option3Reference1Url"
                                            placeholder="Enter reference 1 URL">
                                    </div>
                                    <div class="mb-3">
                                        <label for="option3Reference2Text" class="form-label text-white">Reference
                                            2</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option3Reference2Text" name="option3Reference2Text"
                                            placeholder="Enter reference 2 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option3Reference2Url" name="option3Reference2Url"
                                            placeholder="Enter reference 2 URL">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="option4Text" class="form-label fw-semibold text-white">Option 4 -
                                    High</label>
                                <input required type="text" class="form-control bg-transparent" id="option4Text"
                                    name="option4Text" placeholder="Enter option 4 text">
                                <div class="ps-4 border-bottom border-white pb-4">
                                    <!-- Expert Recommendation -->
                                    <label for="option4Recommendation" class="form-label mt-2 text-white">Expert
                                        Recommendation</label>
                                    <textarea required class="form-control bg-transparent" id="option4Recommendation"
                                        name="option4Recommendation" rows="3"
                                        placeholder="Enter expert recommendation"></textarea>
                                    <!-- Background -->
                                    <label for="option4Background" class="form-label mt-2 text-white">Background</label>
                                    <textarea required class="form-control bg-transparent" id="option4Background"
                                        name="option4Background" rows="3"
                                        placeholder="Enter background information"></textarea>
                                    <!-- References -->
                                    <div class="mb-3 mt-2">
                                        <label for="option4Reference1Text" class="form-label text-white">Reference
                                            1</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option4Reference1Text" name="option4Reference1Text"
                                            placeholder="Enter reference 1 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option4Reference1Url" name="option4Reference1Url"
                                            placeholder="Enter reference 1 URL">
                                    </div>
                                    <div class="mb-3">
                                        <label for="option4Reference2Text" class="form-label text-white">Reference
                                            2</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option4Reference2Text" name="option4Reference2Text"
                                            placeholder="Enter reference 2 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option4Reference2Url" name="option4Reference2Url"
                                            placeholder="Enter reference 2 URL">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="option5Text" class="form-label fw-semibold text-white">Option 5 - Very
                                    High</label>
                                <input required type="text" class="form-control bg-transparent" id="option5Text"
                                    name="option5Text" placeholder="Enter option 5 text">
                                <div class="ps-4 border-bottom border-white pb-4">
                                    <!-- Expert Recommendation -->
                                    <label for="option5Recommendation" class="form-label mt-2 text-white">Expert
                                        Recommendation</label>
                                    <textarea required class="form-control bg-transparent" id="option5Recommendation"
                                        name="option5Recommendation" rows="3"
                                        placeholder="Enter expert recommendation"></textarea>
                                    <!-- Background -->
                                    <label for="option5Background" class="form-label mt-2 text-white">Background</label>
                                    <textarea required class="form-control bg-transparent" id="option5Background"
                                        name="option5Background" rows="3"
                                        placeholder="Enter background information"></textarea>
                                    <!-- References -->
                                    <div class="mb-3 mt-2">
                                        <label for="option5Reference1Text" class="form-label text-white">Reference
                                            1</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option5Reference1Text" name="option5Reference1Text"
                                            placeholder="Enter reference 1 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option5Reference1Url" name="option5Reference1Url"
                                            placeholder="Enter reference 1 URL">
                                    </div>
                                    <div class="mb-3">
                                        <label for="option5Reference2Text" class="form-label text-white">Reference
                                            2</label>
                                        <input required type="text" class="form-control bg-transparent mb-2"
                                            id="option5Reference2Text" name="option5Reference2Text"
                                            placeholder="Enter reference 2 text">
                                        <input required type="url" class="form-control bg-transparent"
                                            id="option5Reference2Url" name="option5Reference2Url"
                                            placeholder="Enter reference 2 URL">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </main>
            </div>
        </div>
        <script src="./assets/js/jquery-3.6.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="./assets/js/bootstrap.bundle.min.js"></script>
        <!-- Modal -->
        <script src="./assets/js/script.js?v=2"></script>
    </body>

</html>