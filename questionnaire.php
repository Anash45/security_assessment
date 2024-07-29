<?php

include './db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facility Security Vulnerability Assessment</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                background-color: white;
                color: black;
                transition: background-color 0.3s, color 0.3s;
            }

            body.dark-mode {
                background-color: #121212;
                color: #e0e0e0;
            }

            h1 {
                color: #1e3a8a;
                border-bottom: 2px solid #3b82f6;
                padding-bottom: 10px;
            }

            body.dark-mode h1 {
                color: #90caf9;
                border-bottom: 2px solid #42a5f5;
            }

            .input-group {
                margin-bottom: 10px;
            }

            label {
                display: block;
                margin-bottom: 5px;
            }

            input {
                width: 100%;
                padding: 5px;
                border-radius: 3px;
                border: 1px solid #ccc;
                background-color: white;
                color: black;
            }

            body.dark-mode input {
                background-color: #424242;
                color: #e0e0e0;
                border: 1px solid #616161;
            }

            button,
            .btn {
                margin: 5px;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                background-color: #3b82f6;
                color: white;
                font-size: 14px;
            }

            body.dark-mode button {
                background-color: #42a5f5;
                color: black;
            }

            .question-group {
                margin-bottom: 20px;
            }

            .recommendation,
            .background {
                margin-top: 20px;
                padding: 15px;
                border-radius: 5px;
                background-color: #f0f9ff;
                color: black;
            }

            body.dark-mode .recommendation,
            body.dark-mode .background {
                background-color: #1e1e1e;
                color: #e0e0e0;
            }

            a {
                color: #3b82f6;
                text-decoration: none;
            }

            body.dark-mode a {
                color: #90caf9;
            }

            a:hover {
                text-decoration: underline;
            }

            .option-button {
                background-color: #e2e8f0;
                color: black;
                display: block;
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
                padding: 10px;
                border-radius: 5px;
                border: none;
            }

            body.dark-mode .option-button {
                background-color: #424242;
                color: #e0e0e0;
            }

            .option-button:hover {
                filter: brightness(0.9);
            }

            .option-button.selected {
                color: white;
            }

            #dark-mode-toggle {
                margin: 10px;
                padding: 10px;
                background-color: #1e3a8a;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            body.dark-mode #dark-mode-toggle {
                background-color: #90caf9;
                color: black;
            }

            .text-center {
                text-align: center !important;
            }

            /* Background color primary */
            .bg-primary {
                background-color: #0d6efd !important;
                /* The default primary color in BS5 is #0d6efd */
            }

            /* Text color white */
            .text-white {
                color: #fff !important;
            }

            .question-type {
                padding: 12px;
                border-radius: 8px;
            }

            .question-group {
                margin-bottom: 35px;
            }

            .selected-option {
                color: #FFF !important;
            }
        </style>
        <script>
            function updateFacilityName() {
                let facilityName = $('#facilityName').val();
                if (facilityName.length > 0) {
                    $('.facility-name').text(facilityName);
                } else {
                    $('.facility-name').text('Facility');
                }
            }
        </script>
    </head>

    <body>
        <h1 class="doc-name"><span class="facility-name">Facility</span> Security Vulnerability Assessment</h1>
        <button onclick="clearAllData()">Clear all Data</button>
        <button onclick="clearResponses()">Clear all Security/Emergency Management Responses</button>
        <button id="dark-mode-toggle" onclick="toggleDarkMode()">Enable Dark Mode</button>
        <a href="./index.php" class="btn">Admin</a>
        <div class="input-group">
            <label for="facilityName">Facility Name:</label>
            <input type="text" id="facilityName" onkeyup="updateFacilityName()">
        </div>
        <div class="input-group">
            <label for="reportDate">Date of Report:</label>
            <input type="date" id="reportDate">
        </div>
        <div class="input-group">
            <label for="reportNumber">Report Number:</label>
            <input type="text" id="reportNumber">
        </div>
        <div class="input-group">
            <label><input type="checkbox" id="includeName" value="Yes" style="width: fit-content;"> Include facility
                name in report title?</label>
        </div>
        <div class="input-group">
            <label><input type="checkbox" id="includeLinks" value="Yes" style="width: fit-content;"> Include links in
                report?</label>
        </div>
        <?php


        function displayQuestionTypesAndQuestions($conn)
        {
            $output = '';

            // Step 1: Fetch question types
            $sqlTypes = "SELECT id, type FROM question_types";
            $resultTypes = $conn->query($sqlTypes);

            if ($resultTypes->num_rows > 0) {
                while ($rowType = $resultTypes->fetch_assoc()) {
                    $typeId = $rowType['id'];
                    $typeName = htmlspecialchars($rowType['type']);
                    $typeToID = strtolower(str_replace(" ", "-", $typeName));

                    // Display question type heading
                    $output .= '<div class="question-type" id="' . $typeToID . '">';
                    $output .= '<h3 class="text-center bg-primary text-white question-type">' . $typeName . '</h3>';

                    // Step 2: Fetch questions for the current question type
                    $sqlQuestions = "SELECT question_id, question_text
                             FROM questions
                             WHERE question_type_id = $typeId AND `hidden` = 0";
                    $resultQuestions = $conn->query($sqlQuestions);

                    if ($resultQuestions->num_rows > 0) {
                        while ($rowQuestion = $resultQuestions->fetch_assoc()) {
                            $output .= '<div class="question-group">';
                            $questionId = $rowQuestion['question_id'];
                            $questionText = htmlspecialchars($rowQuestion['question_text']);

                            // Display each question
                            $output .= '<h4 class="question-text">' . $questionText . '</h4>';
                            $sqlOptions = "SELECT option_id, option_text, recommendation, background
                                 FROM options
                                 WHERE question_id = $questionId";
                            $resultOptions = $conn->query($sqlOptions);
                            if ($resultOptions->num_rows > 0) {
                                $optNum = 0;
                                while ($rowOption = $resultOptions->fetch_assoc()) {
                                    $optionId = $rowOption['option_id'];
                                    $optionText = htmlspecialchars($rowOption['option_text']);
                                    $recommendation = htmlspecialchars($rowOption['recommendation']);
                                    $background = htmlspecialchars($rowOption['background']);

                                    // Initialize or reset references array for each option
                                    $referencesArray = [];

                                    $sqlReferences = "SELECT reference_id, link_text, url
                                                     FROM `references`
                                                     WHERE option_id = $optionId";
                                    $resultReferences = $conn->query($sqlReferences);

                                    if ($resultReferences->num_rows > 0) {
                                        while ($rowReference = $resultReferences->fetch_assoc()) {
                                            $referenceId = $rowReference['reference_id'];
                                            $linkText = htmlspecialchars($rowReference['link_text']);
                                            $url = htmlspecialchars($rowReference['url']);

                                            // Build the reference object
                                            $referenceObject = [
                                                'linkText' => $linkText,
                                                'url' => $url
                                            ];

                                            // Add the reference object to the references array
                                            $referencesArray[] = $referenceObject;
                                        }

                                        // Encode the references array to JSON
                                        $referencesJSON = json_encode($referencesArray);
                                    } else {
                                        // If no references are found, set the JSON to an empty array
                                        $referencesJSON = json_encode([]);
                                    }

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

                                    // Display each option
                                    $output .= '<button class="option-button" 
            id="option-' . htmlspecialchars($typeToID, ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($questionId, ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($optionId, ENT_QUOTES, 'UTF-8') . '" 
            onclick="selectOption(event, \'' . addslashes(htmlspecialchars($background, ENT_QUOTES, 'UTF-8')) . '\', \'' . addslashes(htmlspecialchars($recommendation, ENT_QUOTES, 'UTF-8')) . '\', \'' . htmlspecialchars(addslashes($referencesJSON), ENT_QUOTES, 'UTF-8') . '\', ' . htmlspecialchars($optionId, ENT_QUOTES, 'UTF-8') . ')">';

                                    $output .= '<strong>' . $optText . '</strong>: <span>' . $optionText . '</span>';
                                    $output .= '</button>';
                                    $optNum++;
                                }

                                $output .= '<div class="recommendation" style="display: none;">
                                    <h4>Expert Recommendation</h4>
                                    <p></p>
                                </div>';
                                $output .= '<button class="background-btn" id="toggleBackground-' . $typeToID . '-1" onclick="toggleBackground(event)" style="display: none;">Show Background Information</button>';
                                $output .= '<div id="background-' . $typeToID . '" class="background" style="display: none;">
                                    <h4>Background:</h4>
                                    <p></p>
                                    <h4>References:</h4>
                                    <ul>
                                        <li><a href="#" target="_blank" rel="noopener noreferrer"></a></li>
                                    </ul>
                                </div>';
                            }

                            $output .= '</div>'; // Close questions div
                        }
                    } else {
                        $output .= '<p>No questions found for ' . $typeName . '</p>';
                    }

                    $output .= '</div>'; // Close question-type div
                }
            } else {
                $output .= '<p>No question types found</p>';
            }

            return $output;
        }

        // Usage example:
// Assuming $conn is your database connection object
// $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Call the function to display question types and questions
        echo displayQuestionTypesAndQuestions($conn);

        // Close database connection
        $conn->close();


        // Example usage:
        // $outputHtml = getSecurityTypesAndQuestions($conn);
        
        // echo $outputHtml;
        ?>
        <button onclick="downloadHtml()">Download HTML</button>
        <button onclick="generatePDF()">Generate PDF</button>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <script>

            const optionsTemplate = [
                { label: 'Very Low', color: '#ef4444' },
                { label: 'Low', color: '#f97316' },
                { label: 'Medium', color: '#eab308' },
                { label: 'High', color: '#22c55e' },
                { label: 'Very High', color: '#3b82f6' }
            ];

            function selectOption(event, background, recommendation, referencesJSON, optionId) {
                // Parse the JSON string into a JavaScript object
                var references = JSON.parse(referencesJSON);

                console.log(references);
                // Get the target button element using jQuery
                var $target = $(event.currentTarget);

                $target.siblings('.option-button').removeClass('selected-option');
                $target.addClass('selected-option');
                // Find the closest `.question-group` parent element using jQuery
                var $questionGroup = $target.closest('.question-group');
                $questionGroup.addClass('question-selected');
                $target.closest('.question-type').addClass('question-type-selected');

                if ($questionGroup.length > 0) {
                    var $linksUL = $questionGroup.find('.background ul');
                    // Remove background color from all option buttons within this question group
                    $questionGroup.find('.option-button').css({ 'background-color': '' });

                    // Find all option buttons within the `.question-group` div
                    var $optionButtons = $questionGroup.find('.option-button');

                    // Get the index of the clicked option button within the question group
                    var index = $optionButtons.index($target);

                    console.log(index);


                    // Set background color based on index
                    if (index >= 0 && index < optionsTemplate.length) {
                        var color = optionsTemplate[index].color;
                        $target.css({ 'background-color': color });
                    }

                    // Update background, recommendation, and other functionality as needed
                    // Example:
                    updateBackground($questionGroup, background);
                    updateRecommendation($questionGroup, recommendation);
                    // Update links in the ul within .background
                    updateReferences($questionGroup, references);
                }
            }
            // Example function to update background text
            function updateBackground($questionGroup, background) {
                var $backgroundDiv = $questionGroup.find('.background');
                var $pElement = $backgroundDiv.find('p');
                if ($pElement.length > 0) {
                    $pElement.html(background);
                } else {
                    $backgroundDiv.append('<p>' + background + '</p>');
                }
            }

            // Example function to update recommendation text
            function updateRecommendation($questionGroup, recommendation) {
                var $recommendationDiv = $questionGroup.find('.recommendation');
                var $backgroundBtn = $questionGroup.find('.background-btn');
                var $pElement = $recommendationDiv.find('p');
                if ($pElement.length > 0) {
                    $pElement.html(recommendation);
                } else {
                    $recommendationDiv.append('<p>' + recommendation + '</p>');
                }
                $recommendationDiv.show();
                $backgroundBtn.show();
            }

            // Example function to update references (links)
            function updateReferences($questionGroup, references) {
                var $backgroundDiv = $questionGroup.find('.background');
                var $ulElement = $backgroundDiv.find('ul');
                if ($ulElement.length > 0) {
                    $ulElement.empty(); // Clear existing list items
                    references.forEach(function (reference) {
                        $ulElement.append('<li><a href="' + reference.url + '" target="_blank">' + reference.linkText + '</a></li>');
                    });
                } else {
                    $ulElement = $('<ul></ul>'); // Create new ul element
                    references.forEach(function (reference) {
                        $ulElement.append('<li><a href="' + reference.url + '" target="_blank">' + reference.linkText + '</a></li>');
                    });
                    $backgroundDiv.append($ulElement); // Append ul element to .background div
                }
            }



            function toggleBackground(event) {
                // Use event.target to get the element that triggered the event
                var $target = $(event.target || event.srcElement);
                console.log($target);

                // Find the closest '.question-group' parent and then find the '.background' element within it
                var $questionGroup = $target.closest('.question-group');
                var $backgroundDiv = $questionGroup.find('.background');

                // Toggle the visibility of the '.background' div
                $backgroundDiv.toggle();

                // Check if the '.background' div is visible and update the button text accordingly
                if ($backgroundDiv.is(':visible')) {
                    $target.text('Hide Background Information');
                } else {
                    $target.text('Show Background Information');
                }
            }

            function clearAllData() {
                document.getElementById('facilityName').value = '';
                document.getElementById('reportDate').value = '';
                document.getElementById('reportNumber').value = '';

                clearResponses();
                alert('All data cleared!');
            }

            function clearResponses() {
                // Unselect all options
                $('.option-button').css('background-color', '');
                $('.option-button').css('color', '#000');
                $('.question-group').removeClass('question-selected');
                $('.question-type').removeClass('question-type-selected');
                $('.option-button').removeClass('selected-option');

                // Hide all .recommendation and .background-btn
                $('.recommendation').hide();
                $('.background-btn').hide();

                // Hide all .background and clear their content
                $('.background').hide().empty();

                alert('All Security/Emergency Management Responses cleared!');
            }


            function downloadHtml() {
                const element = document.createElement('a');
                const file = new Blob([document.documentElement.outerHTML], { type: 'text/html' });
                element.href = URL.createObjectURL(file);
                element.download = 'security-dashboard.html';
                document.body.appendChild(element);
                element.click();
            }



            async function generatePDF() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                let docTitle = $('.doc-name').text();

                if (!$('#includeName:checked').length > 0) {
                    docTitle = '';
                }

                doc.setFont("helvetica", "bold");
                doc.setFontSize(16);
                doc.text(docTitle, 10, 10);

                let yOffset = 30; // Initial yOffset
                const pageHeight = doc.internal.pageSize.height;
                const lineHeight = 7; // Adjust the line height based on your needs
                // Get values from input fields
                let facilityName = $('#facilityName').val();
                let reportDate = $('#reportDate').val();
                let reportNumber = $('#reportNumber').val();

                // Add input field values to PDF
                doc.setFontSize(12);
                doc.text("Facility Name: ", 10, yOffset);
                doc.setFont("helvetica", "normal");
                doc.text(facilityName, 45, yOffset);
                yOffset += 10;

                doc.setFont("helvetica", "bold");
                doc.text("Date of Report: ", 10, yOffset);
                doc.setFont("helvetica", "normal");
                doc.text(reportDate, 45, yOffset);
                yOffset += 10;

                doc.setFont("helvetica", "bold");
                doc.text("Report Number: ", 10, yOffset);
                doc.setFont("helvetica", "normal");
                doc.text(reportNumber, 45, yOffset);
                yOffset += 20; // Adding extra space before main content

                // Function to check if a new page is needed
                const addPageIfNeeded = (increment) => {
                    if (yOffset + increment > pageHeight - 20) { // 20 for margin
                        doc.addPage();
                        yOffset = 20; // Reset yOffset for the new page, considering top margin
                    }
                };
                let quesNum = 1;
                // Iterate through each selected question type
                $('.question-type-selected').each(function (index) {
                    const sectionTitle = $(this).find('h3').text();

                    // Add section title to PDF with light grey background and centered alignment
                    addPageIfNeeded(15); // Check if new page is needed
                    doc.setFillColor(240, 240, 240); // Light grey background color
                    doc.rect(10, yOffset - 6.6, 190, 10, 'F'); // Draw rectangle for background
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(14);
                    doc.setTextColor(0); // Black text color
                    doc.text(sectionTitle, 105, yOffset, { align: 'center' }); // Center-aligned text
                    yOffset += 15; // Increment yOffset for next element

                    // Iterate through each selected question in this type
                    $(this).find('.question-selected').each(function (idx) {
                        const questionTitle = quesNum + '.  ' + $(this).find('h4.question-text').text();
                        const optionType = $(this).find('.selected-option>strong').text();
                        const optionText = $(this).find('.selected-option>span').text();
                        const backgroundText = $(this).find('.background > p').text();
                        const recommendationText = $(this).find('.recommendation > p').text();

                        // Add question title to PDF
                        doc.setFontSize(16);
                        doc.setFont("helvetica", "bold");
                        doc.setTextColor(0); // Black text color
                        let lines = doc.splitTextToSize(questionTitle, 180); // Adjust width as needed
                        addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                        doc.text(lines, 15, yOffset);
                        yOffset += (lines.length * lineHeight) + 5; // Increment yOffset based on number of lines


                        // Find the corresponding background color
                        let bgColor = '#808080'; // Default background color if not found
                        let option = optionsTemplate.find(opt => opt.label === optionType);
                        if (option) {
                            bgColor = option.color;
                        }


                        let lines1 = doc.splitTextToSize(optionType, 180);
                        let lines2 = doc.splitTextToSize(optionText, 180);
                        let lines3 = lines1.length + lines2.length;

                        // Add background color behind the section
                        doc.setFillColor(bgColor);
                        doc.roundedRect(12, yOffset - 7.5, 188, (lines3 * lineHeight) + 5, 3, 3, 'FD');

                        let textColor = '#FFFFFF';

                        doc.setTextColor(textColor);
                        // Set font for optionType (bold)
                        doc.setFont("helvetica", "bold");
                        lines = doc.splitTextToSize(optionType, 180);
                        // addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                        doc.text(lines, 15, yOffset);
                        yOffset += (lines.length * lineHeight) + 0;

                        // Set font for optionText (normal)
                        doc.setFont("helvetica", "normal");
                        lines = doc.splitTextToSize(optionText, 180);
                        // addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                        doc.text(lines, 15, yOffset);
                        yOffset += (lines.length * lineHeight) + 10;


                        doc.setTextColor(0);


                        // Add recommendation text
                        if (recommendationText) {
                            doc.setFont("helvetica", "bold");
                            lines = doc.splitTextToSize(`Recommendation: `, 180);
                            addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                            doc.text(lines, 15, yOffset);
                            yOffset += (lineHeight);
                            doc.setFont("helvetica", "normal");
                            lines = doc.splitTextToSize(`${recommendationText}`, 180);
                            addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                            doc.text(lines, 15, yOffset);
                            yOffset += (lines.length * lineHeight) + 5;
                        }

                        // Add background text
                        if (backgroundText) {
                            doc.setFont("helvetica", "bold");
                            lines = doc.splitTextToSize(`Background: `, 180);
                            addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                            doc.text(lines, 15, yOffset);
                            yOffset += (lineHeight);
                            doc.setFont("helvetica", "normal");
                            lines = doc.splitTextToSize(`${backgroundText}`, 180);
                            addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed
                            doc.text(lines, 15, yOffset);
                            yOffset += (lines.length * lineHeight) + 0.1;
                        }

                        // Add references (links)
                        const $references = $(this).find('.background > ul > li > a');

                        if ($references.length > 0 && $('#includeLinks:checked').length > 0) {
                            yOffset += 5;
                            addPageIfNeeded(10); // Check if new page is needed
                            doc.setFont("helvetica", "bold");
                            doc.text('References:', 15, yOffset);
                            yOffset += (lineHeight);
                            doc.setFont("helvetica", "normal");
                            $references.each(function () {
                                const refText = $(this).text().trim();
                                const refUrl = $(this).attr('href').trim();

                                // Set text color for the link
                                doc.setTextColor('#3b82f6');

                                // Split the text if needed (adjust lineHeight if required)
                                const lines = doc.splitTextToSize(refText, 180);
                                addPageIfNeeded(lines.length * lineHeight); // Check if new page is needed

                                // Define the starting y position
                                let currentYOffset = yOffset;

                                // Add text to the PDF
                                doc.text(lines, 15, currentYOffset);

                                // Add the clickable link
                                lines.forEach((line, index) => {
                                    doc.link(15, currentYOffset + (index * lineHeight), 180, lineHeight, { url: refUrl });
                                });

                                // Update yOffset for the next block of text
                                yOffset += (lines.length * lineHeight) + 0.3;
                            });

                        }

                        // Add some space between questions
                        yOffset += 20;
                        addPageIfNeeded(5); // Check if new page is needed
                        quesNum++;
                    });

                    // Add space between sections
                    yOffset += 10;
                    addPageIfNeeded(10); // Check if new page is needed
                });

                doc.save("Facility_Security_Self-Assessment.pdf");
            }


            function toggleDarkMode() {
                document.body.classList.toggle('dark-mode');
                const toggleButton = document.getElementById('dark-mode-toggle');
                if (document.body.classList.contains('dark-mode')) {
                    toggleButton.textContent = 'Disable Dark Mode';
                } else {
                    toggleButton.textContent = 'Enable Dark Mode';
                }
            }
        </script>
    </body>

</html>