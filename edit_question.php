<?php
require_once './db_conn.php';
require './functions.php';

if (!isAdmin()) {
    header('location: login.php');
}
$info = '';
// Check if the form is submitted

if (isset($_REQUEST['id'])) {
    $qID = intval($_REQUEST['id']);


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
        if(updateQuestionWithOptions($qID, $questionType, $questionText, $options)){
            $info = '<div class="alert alert-success" role="alert">Question added successfully</div>';
        }else{
            $info = '<div class="alert alert-danger" role="alert">Failed to add question</div>';
        }
    }

    $sql1 = "SELECT * FROM questions WHERE question_id = $qID";
    $result1 = mysqli_query($conn, $sql1);
} else {
    header('location: index.php');
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
                            <span class="lang-en">Edit Question</span>
                        </h1>
                    </div>
                    <?php echo $info; ?>
                    <?php if (mysqli_num_rows($result1) > 0):
                        $row1 = mysqli_fetch_assoc($result1);
                        $questionId = $row1['question_id'];

                        $sql2 = "SELECT * FROM `options` WHERE `question_id` = $questionId";
                        $result2 = mysqli_query($conn, $sql2);

                        ?>
                        <form class="needs-validation pb-5" method="POST"
                            action="edit_question.php?id=<?php echo $questionId; ?>" novalidate>
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
                                            $selected = ($row1['question_type_id'] == $row4['id']) ? 'selected' : '';
                                            echo '<option value="' . $row4['id'] . '" '.$selected.'>' . $row4['type'] . '</option>';
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
                                    name="questionText" placeholder="Enter the question text"
                                    value="<?php echo $row1['question_text']; ?>">
                            </div>
                            <!-- Options -->
                            <div class="mb-3 ps-4">
                                <label class="form-label fw-bold text-white">
                                    <h4 class="fw-bold">Options</h4>
                                </label>
                                <!-- Option 1 -->
                                <?php
                                if (mysqli_num_rows($result2) > 0) {
                                    $optNum = 1;
                                    while ($row2 = mysqli_fetch_assoc($result2)) {

                                        if ($optNum == 0) {
                                            $optText = 'Very Low';
                                        } elseif ($optNum == 1) {
                                            $optText = 'Low';
                                        } elseif ($optNum == 2) {
                                            $optText = 'Medium';
                                        } elseif ($optNum == 3) {
                                            $optText = 'High';
                                        } elseif ($optNum == 4) {
                                            $optText = 'Very High';
                                        }
                                        ?>
                                        <div class="mb-4">
                                            <label for="option<?php echo $optNum; ?>Text"
                                                class="form-label fw-semibold text-white">Option <?php echo $optNum; ?> - <?php echo $optText; ?></label>
                                            <input required type="text" class="form-control bg-transparent" id="option<?php echo $optNum; ?>Text"
                                                name="option<?php echo $optNum; ?>Text" value="<?php echo $row2['option_text']; ?>" placeholder="Enter option <?php echo $optNum; ?> text">
                                            <div class="ps-4 border-bottom border-white pb-4">
                                                <!-- Expert Recommendation -->
                                                <label for="option<?php echo $optNum; ?>Recommendation"
                                                    class="form-label mt-2 text-white">Expert Recommendation</label>
                                                <textarea required class="form-control bg-transparent"
                                                    id="option<?php echo $optNum; ?>Recommendation"
                                                    name="option<?php echo $optNum; ?>Recommendation" rows="3"
                                                    placeholder="Enter expert recommendation"><?php echo $row2['recommendation']; ?></textarea>
                                                <!-- Background -->
                                                <label for="option<?php echo $optNum; ?>Background"
                                                    class="form-label mt-2 text-white">Background</label>
                                                <textarea required class="form-control bg-transparent"
                                                    id="option<?php echo $optNum; ?>Background"
                                                    name="option<?php echo $optNum; ?>Background" rows="3"
                                                    placeholder="Enter background information"><?php echo $row2['background']; ?></textarea>
                                                <!-- References -->
                                                <?php
                                                $optId = $row2['option_id'];
                                                    $sql3 = "SELECT * FROM `references` WHERE option_id = $optId";
                                                    $result3 = mysqli_query($conn, $sql3);
                                                    $optRef1 = $optUrl1 = $optRef2 = $optUrl2 = '';
                                                    if(mysqli_num_rows($result3) > 0){
                                                        $refNum = 1;
                                                        while($row3 = mysqli_fetch_assoc($result3)){
                                                            if($refNum == 1){
                                                                $optRef1 = $row3['link_text'];
                                                                $optUrl1 = $row3['url'];
                                                            }elseif($refNum == 2){
                                                                $optRef2 = $row3['link_text'];
                                                                $optUrl2 = $row3['url'];
                                                            }
                                                            $refNum++;
                                                        }
                                                    }
                                                ?>
                                                <div class="mb-3 mt-2">
                                                    <label for="option<?php echo $optNum; ?>Reference1Text"
                                                        class="form-label text-white">Reference 1</label>
                                                    <input required type="text" class="form-control bg-transparent mb-2"
                                                        id="option<?php echo $optNum; ?>Reference1Text" name="option<?php echo $optNum; ?>Reference1Text"
                                                        placeholder="Enter reference 1 text" value="<?php echo $optRef1; ?>">
                                                    <input required type="url" class="form-control bg-transparent"
                                                        id="option<?php echo $optNum; ?>Reference1Url" name="option<?php echo $optNum; ?>Reference1Url"
                                                        placeholder="Enter reference 1 URL" value="<?php echo $optUrl1; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="option<?php echo $optNum; ?>Reference2Text" class="form-label text-white">Reference
                                                        2</label>
                                                    <input required type="text" class="form-control bg-transparent mb-2"
                                                        id="option<?php echo $optNum; ?>Reference2Text" name="option<?php echo $optNum; ?>Reference2Text"
                                                        placeholder="Enter reference 2 text" value="<?php echo $optRef2; ?>">
                                                    <input required type="url" class="form-control bg-transparent"
                                                        id="option<?php echo $optNum; ?>Reference2Url" name="option<?php echo $optNum; ?>Reference2Url"
                                                        placeholder="Enter reference 2 URL" value="<?php echo $optUrl2; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $optNum++;
                                    }
                                }
                                ?>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    <?php endif; ?>
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