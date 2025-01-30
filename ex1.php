<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js here -->
    <title>Scilab</title>
    <style>
        body {
            font-family: Space Grotesk;
        }

        .container {
            position: absolute;
            border: 2px solid black;
            width: 45px;
            height: 45px;
            cursor: move;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 18px;
            display: none;
            /* Initially hidden */
        }

        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 10px;
            border-right: 1px solid #ccc;
            height: 95vh;
            margin-top: 50px;
        }

        .container-text {
            position: absolute;
            /* Position text absolutely */
            top: 50%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust to keep the text centered */
        }

        .arrow {
            width: 0;
            height: 0;
            border-style: solid;
            position: absolute;
            cursor: pointer;
        }

        .arrow-left {
            border-width: 5px 7px 5px 0;
            border-color: transparent black transparent transparent;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
        }

        .arrow-right {
            border-width: 5px 0 5px 7px;
            border-color: transparent transparent transparent black;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
        }

        .arrow-top {
            border-width: 7px 5px 0;
            border-color: black transparent transparent transparent;
            top: -7px;
            left: 50%;
            transform: translateX(-50%);
        }

        .arrow-down {
            border-width: 5px 5px 0;
            border-color: black transparent transparent transparent;
            bottom: -7px;
            left: 50%;
            transform: translateX(-50%);
        }

        .resize-handle {
            width: 5px;
            height: 5px;
            background-color: blue;
            position: absolute;
            cursor: nwse-resize;
        }

        .resize-top-left {
            top: -5px;
            left: -5px;
        }

        .resize-top-right {
            top: -5px;
            right: -5px;
        }

        .resize-bottom-left {
            bottom: -5px;
            left: -5px;
        }

        .resize-bottom-right {
            bottom: -5px;
            right: -5px;
        }

        .line {
            position: absolute;
            height: 2px;
            background-color: black;
            transform-origin: 0 0;
            z-index: -1;
            display: none;
            /* Initially hidden */
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: absolute;
            /* Absolute positioning */
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            z-index: 1000;
            /* On top */
            width: 300px;
            /* Fixed width for the modal */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            /* Shadow for depth */
            border-radius: 8px;
            /* Rounded corners */
        }

        .modal-header {
            display: flex;
            /* Flexbox for aligning items */
            align-items: center;
            /* Center items vertically */
        }

        .modal-header h2 {
            margin: 0;
            /* Remove default margin */
            flex-grow: 1;
            /* Allow heading to take available space */
        }

        .container-grid {
            display: flex;
            flex-wrap: wrap;
            /* Allow wrapping to the next line */
            justify-content: space-between;
            /* Even spacing between items */
            gap: 10px;
            /* Optional, to add space between containers */
        }

        .mini-container {
            width: calc(25% - 10px);
            /* Set width to 25% for the first row to fit 4 items */
            height: 50px;
            /* Fixed height for containers */
            border: 1px solid #ccc;
            /* Border for visibility */
            box-sizing: border-box;
            /* Include padding and border in the total width and height */
            text-align: center;
            /* Center text */
        }

        .image-name {
            margin-top: 5px;
            font-size: 12px;
            /* Small font for the image name */
            color: #333;
            /* Text color */
            text-align: center;
            /* Center the text */
        }

        /* Ensuring that after 4 items, the next row can hold 5 items */
        .container-grid .mini-container:nth-child(n+5) {
            width: calc(20% - 10px);
            /* Set width to 20% for items in the next row (5 items per row) */
            margin-top: 20px;
            /* Add a gap between rows */

        }

        .mini-container:hover {
            background-color: #f0f0f0;
            /* Change background on hover */
        }

        .close-btnn {
            margin-top: 20px;
            padding: 4px 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 245px;
        }

        .close-btnn:hover {
            background-color: #0056b3;
            /* Darker on hover */
        }

        .close-btn.x-btn {
            margin-top: -4px;
            padding: 0px 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-btn.x-btn:hover {
            background-color: #0056b3;
            /* Darker on hover */
        }

        .form-container {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            position: fixed;
            /* Fixed positioning */
            top: 50%;
            /* Position from the top */
            left: 50%;
            /* Position from the left */
            transform: translate(-50%, -50%);
            /* Center the form */
            z-index: 10;
            /* Show above other elements */
            /* Shadow for visibility */
            cursor: move;
            /* Indicate that it can be moved */
            border-radius: 8px;
        }

        .close-btns {
            cursor: pointer;
            float: right;
            font-size: 20px;
            color: red;
            margin-top: -100px;
        }

        .lines {
            margin: 10px -15px;
            border-bottom: 1px solid #ccc;
            margin-top: -2px;
            margin-bottom: 13px;
        }

        .lines3 {
            margin: 10px -15px;
            border-bottom: 1px solid #ccc;
            margin-top: 15px;
            margin-bottom: 13px;
        }

        .lines4 {
            margin: 10px -15px;
            border-bottom: 1px solid #ccc;
            margin-top: -15px;
            margin-bottom: 13px;
        }

        .lines2 {
            margin: 10px -15px;
            border-bottom: 1px solid #ccc;
            margin-top: 45px;
            margin-bottom: 23px;
        }

        .form-header h1 {
            font-size: 16px;
            /* Adjust font size for h1 */
            margin-bottom: -6px;
            /* Adds space between the headings */
        }

        .form-header h2 {
            font-size: 14px;
            /* Adjust font size for h2 */
            margin-bottom: 9px;
            /* Adjusts the gap before the close button */
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            /* Space between label and input */
            align-items: center;
            /* Align items vertically */
            margin-bottom: 10px;
            /* Space between rows */
        }

        .form-row label {
            width: 200px;
            /* Set a fixed width for labels */
            text-align: right;
            /* Align text to the right */
            margin-right: 10px;
            /* Space between label and input */
        }

        .form-row input {
            flex-grow: 1;
            /* Allow input to take the remaining space */
            padding: 5px;
            /* Add some padding */
            border: 1px solid #ccc;
            /* Add a border */
            border-radius: 4px;
            /* Round corners */
        }



        .container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Ensure full image is shown */
            background-color: #f0f0f0;
            /* Matches the container's background */
        }

        .header {
            background-color: #4c6faf;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 24px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            margin-left: -7px;
        }

        .lines1 {
            margin: 10px -15px;
            border-bottom: 1px solid #ccc;
            margin-top: 7px;
            margin-bottom: 23px;
        }

        .chart-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 0;
            /* Remove default margin for centering */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            transition: transform 0.1s ease;
            /* Optional */
            position: relative;
            /* Ensure position is relative for transform */
        }


        .modal-header {
            display: flex;
            /* Flexbox for alignment */
            justify-content: space-between;
            /* Space between title and button */
            align-items: center;
            /* Center vertically */
        }

        #modal1 {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Fixed positioning to stay in place while scrolling */
            top: 50%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust positioning to truly center */
            z-index: 1000;
            /* Ensure it stays above other elements */
            background-color: white;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 80%;
            /* Ensure modal takes a reasonable width */
            max-width: 300px;
            /* Ensure it doesn't get too wide */
            text-align: center;
            /* Center the text inside the modal */
        }

        #excelContent {
            position: absolute;
            /* Make the content position absolute */
            top: 55%;
            /* Position it in the middle of the page */
            left: 55%;
            /* Position it in the middle horizontally */
            transform: translate(-50%, -50%);
            /* Move it back by 50% of its size to truly center it */
            width: 80%;
            /* You can adjust the width as needed */
            max-height: 80%;
            /* Limit the height if necessary to avoid overflow */
            overflow-y: auto;
            /* Allow scrolling if content overflows */
            background-color: #fff;
            /* Optional: Make the background white to highlight the content */
            padding: 20px;
            /* Optional: Add some padding */
            border: 1px solid #ccc;
            /* Optional: Border for visual distinction */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Optional: Add shadow for a floating effect */
            z-index: 1000;
            /* Make sure it's on top of other elements */
            display: none;
            /* Initially hidden until file is opened */
        }

        #excelContent table {
            width: 100%;
            border-collapse: collapse;
        }

        #excelContent td,
        #excelContent th {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        #excelContent th {
            background-color: #f4f4f4;
        }

        #closeExcelBtn {
            position: absolute;
            top: 10px;
            right: -5px;
            background-color: red;
            color: white;
            border: none;
            font-size: 17px;
            padding: 0px 5px;
            cursor: pointer;
        }

        #closeExcelBtn:hover {
            background-color: darkred;
        }

        #modal2 {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Fixed positioning to stay in place while scrolling */
            top: 50%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust positioning to truly center */
            z-index: 1000;
            /* Ensure it stays above other elements */
            background-color: white;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 80%;
            /* Ensure modal takes a reasonable width */
            max-width: 300px;
            /* Ensure it doesn't get too wide */
            text-align: center;
            /* Center the text inside the modal */
        }

        #modal3 {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Fixed positioning to stay in place while scrolling */
            top: 50%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust positioning to truly center */
            z-index: 1000;
            /* Ensure it stays above other elements */
            background-color: white;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 80%;
            /* Ensure modal takes a reasonable width */
            max-width: 300px;
            /* Ensure it doesn't get too wide */
            text-align: center;
            /* Center the text inside the modal */
        }

        /* Close button style */
        .close-buttons {
            position: absolute;
            /* Positioning the button */
            top: 10px;
            /* Distance from the top */
            right: 10px;
            /* Distance from the right */
            background-color: #ff0000;
            /* Transparent background */
            border: none;
            /* Remove border */
            color: #fff;
            /* White color for the X */
            font-size: 20px;
            /* Size of the X */
            font-weight: bold;
            /* Bold text */
            cursor: pointer;
            /* Change cursor to pointer */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .file-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #e0e0e0;
            /* Light blue background */
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }



        /* Style for links inside the file items */
        .file-item a {
            text-decoration: none;
            color: #000;
            /* Black text */
            font-weight: bold;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            border: none;
            padding: 1px 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            /* Green background for the Upload button */
            color: white;
            /* White text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            /* Darker green when hovering */
        }
    </style>
</head>

<body>
    <!-- Header section -->
    <div class="header">
        Cooling Pressure
    </div>
    <div class="sidebar" style="display: flex; flex-direction: column; align-items: flex-start;">
        <button
            style="font-size: 20px; margin-top: 22px; background-color: #0090e5; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;            font-family: Space Grotesk;"
            onclick="showModal(event)">Parameter</button>
        <!-- Pass event to get button position -->
    </div>

    <!-------------------------------------------- First Container--------------------------------------------------->
    <div class="container" id="container1" style="top: 100px; left: 300px;">

        <div class="arrow arrow-right" id="arrowRight1"></div>
        <!-- Resize handles -->
        <div class="resize-handle resize-top-left"></div>
        <div class="resize-handle resize-top-right"></div>
        <div class="resize-handle resize-bottom-left"></div>
        <div class="resize-handle resize-bottom-right"></div>
    </div>
    <!-- Modal for file list (modal1) -->
    <!-- Modal for file list (modal1) -->
    <div id="modal1" class="modal1" style="display: none;"> <!-- Initially hidden -->
        <div class="modal-content">
            <!-- Close button for modal1 -->
            <button type="button" class="close-buttons" onclick="closeModal('modal1')">X</button>
            <!-- Heading and Horizontal Rule -->
            <h2>Excel Files</h2>
            <hr>
            <!-- File list container -->
            <div id="fileListContainer"></div>
        </div>
    </div>

    <!-- Div to show Excel content -->
    <div id="excelContent" style="display: none;">
        <button id="closeExcelBtn" onclick="closeExcelContent()">X</button>
        <div id="excelTableContainer"></div> <!-- Excel table will be inserted here -->
    </div>

    <!-------------------------------------------- Second Conatiner--------------------------------------------------->
    <div class="container" id="container2" style="top: 100px; left: 445px;">

        <!-- <span class="container-text">C2</span> -->
        <div class="arrow arrow-left" id="arrowLeft2"></div>
        <div class="arrow arrow-right" id="arrowRight2"></div>

        <!-- Resize handles -->
        <div class="resize-handle resize-top-left"></div>
        <div class="resize-handle resize-top-right"></div>
        <div class="resize-handle resize-bottom-left"></div>
        <div class="resize-handle resize-bottom-right"></div>
    </div>
    <div id="modal2" class="modal2">
        <button class="close-btn" onclick="closeModal('modal2')">X</button>
        <h2 style="font-size:20px;">Enter Data and View Calculations</h2>
        <hr class="modal-lines">
        <form id="dataForm">
            <label for="highPressure">High Pressure:</label>
            <input type="number" name="highPressure" id="highPressure" step="0.01" required />
            <br /><br />

            <label for="lowPressure">Low Pressure:</label>
            <input type="number" name="lowPressure" id="lowPressure" step="0.01" required />
            <br /><br />

            <label for="unit">Unit:</label>
            <select name="unit" id="unit" required>
                <option value="1">kPa</option>
                <option value="2">MPa</option>
                <option value="3">bar</option>
            </select>
            <br /><br />
            <label for="mass">Mass Flow Rate:</label>
            <input type="number" name="mass" id="mass" step="0.01" required />
            <br /><br />

            <button type="submit">Submit</button>
        </form>
    </div>

    <!-------------------------- Modal for contaner2 ------------------------------------------>

    <!-------------------------------------------- fifth Conatiner--------------------------------------------------->
    <!-- Your container5 div -->
    <div class="container" id="container3" style="top: 100px; left: 580px;">

        <div class="arrow arrow-left" id="arrowLeft3"></div>
        <!-- Resize handles -->
        <div class="resize-handle resize-top-left"></div>
        <div class="resize-handle resize-top-right"></div>
        <div class="resize-handle resize-bottom-left"></div>
        <div class="resize-handle resize-bottom-right"></div>
    </div>
    <div id="modal3" class="modal3" style="display: none;">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('modal3')">X</button>
            <div id="result"></div>
        </div>
    </div>

    <!-----------------------------------Modal for c4--------------------------------------------------------------->

    <!----------------------------------------------------Modal for sidebr-------------------------------------------->
    <div class="modal" id="modal">
        <div class="modal-header">
            <h2>Palettes</h2>
            <button class="close-btn x-btn" onclick="closeMainModal()">&times;</button>
        </div>
        <div class="lines1"></div>
        <div class="container-grid">
            <div id="miniContainer1" class="mini-container" onclick="selectContainer('container1')">

                <p class="image-name">View Excel</p>
            </div>
            <div id="miniContainer2" class="mini-container" onclick="selectContainer('container2')">

                <p class="image-name">Input</p>
            </div>
            <div id="miniContainer3" class="mini-container" onclick="selectContainer('container3')">

                <p class="image-name">Output</p>
            </div>

        </div>
        <button class="close-btnn" onclick="closeMainModal()">Close</button>
    </div>
    <div id="line1" class="line"></div> <!-- Line for C1 to C2 -->
    <div id="line2" class="line"></div> <!-- Line for C2 to C3 -->
    <div id="line3" class="line"></div> <!-- Line for C2 to C3 -->
    <!-----------------------------------------------Chart Dispaly---------------------------------------------------------->

    <script>
        // Get elements
        let container1 = document.getElementById('container1');
        let container2 = document.getElementById('container2');
        let container3 = document.getElementById('container3');

        let arrowRight1 = document.getElementById('arrowRight1');
        let arrowLeft2 = document.getElementById('arrowLeft2');
        let arrowRight2 = document.getElementById('arrowRight2');
        let arrowLeft3 = document.getElementById('arrowLeft3'); // New top arrow


        let line1 = document.getElementById('line1');
        let line2 = document.getElementById('line2');
        let line3 = document.getElementById('line3');


        let isDraggingArrow = false; // To check if arrow dragging has started
        let lineConnected1 = false; // To check if line1 is connected
        let lineConnected2 = false; // To check if line2 is connected
        let lineConnected3 = false; // To check if line3 is connected

        // Function to update the line position and length dynamically during drag
        function updateLinePosition(line, startX, startY, endX, endY) {
            const length = Math.hypot(endX - startX, endY - startY);
            const angle = Math.atan2(endY - startY, endX - startX) * 180 / Math.PI;

            // Update line position and rotation
            line.style.width = `${length}px`;
            line.style.left = `${startX}px`;
            line.style.top = `${startY}px`;
            line.style.transform = `rotate(${angle}deg)`;
        }

        // Function to handle arrow dragging and connecting logic
        function handleArrowDrag(startArrow, endArrow, line, onConnect) {
            startArrow.addEventListener('mousedown', (e) => {
                e.preventDefault(); // Prevent default action
                isDraggingArrow = true;

                const startRect = startArrow.getBoundingClientRect();
                const startX = startRect.left + startRect.width / 2;
                const startY = startRect.top + startRect.height / 2;

                function onMouseMove(event) {
                    if (isDraggingArrow) {
                        const endX = event.clientX;
                        const endY = event.clientY;
                        line.style.display = 'block'; // Show the line during drag
                        updateLinePosition(line, startX, startY, endX, endY);
                    }
                }

                function onMouseUp(event) {
                    const endRect = endArrow.getBoundingClientRect();

                    if (event.clientX >= endRect.left && event.clientX <= endRect.right &&
                        event.clientY >= endRect.top && event.clientY <= endRect.bottom) {
                        // If mouse is released over the target arrow, connect the line
                        onConnect();
                        const endX = endRect.left + endRect.width / 2;
                        const endY = endRect.top + endRect.height / 2;
                        updateLinePosition(line, startX, startY, endX, endY);
                    } else {
                        // If not connected, hide the line and update connection status
                        line.style.display = 'none';
                        if (line === line1) lineConnected1 = false;
                        else if (line === line2) lineConnected2 = false;
                        else if (line === line3) lineConnected3 = false;
                    }

                    isDraggingArrow = false;
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
        }

        // Connect C1 to C2 (right side of C1 connects to left side of C2)
        handleArrowDrag(arrowRight1, arrowLeft2, line1, () => {
            lineConnected1 = true;
            line1.style.display = "block"; // Show line1 when connected
        });

        // Connect C2 to C3 (right side of C2 connects to left side of C3)
        handleArrowDrag(arrowRight2, arrowLeft3, line2, () => {
            lineConnected2 = true;
            line2.style.display = "block"; // Show line2 when connected
        });

        // Remove right arrow for Container 3 (last container)
        document.getElementById("arrowLeft3").style.display = "block"; // Keep left arrow
        const rightArrow3 = container3.querySelector(".arrow.arrow-right");
        if (rightArrow3) {
            rightArrow3.remove(); // Remove right arrow from Container 3
        }

        // Update mouse move function for dynamic line updates
        [container1, container2, container3].forEach((container) => {
            container.addEventListener("mousedown", (e) => {
                if (!isDraggingArrow) {
                    let startX = e.clientX - container.offsetLeft;
                    let startY = e.clientY - container.offsetTop;

                    function onMouseMove(event) {
                        container.style.left = event.clientX - startX + "px";
                        container.style.top = event.clientY - startY + "px";

                        // Update line positions if connected
                        if (lineConnected1) {
                            const rect1 = arrowRight1.getBoundingClientRect();
                            const rect2 = arrowLeft2.getBoundingClientRect();
                            updateLinePosition(line1, rect1.left + rect1.width / 2, rect1.top + rect1.height / 2,
                                rect2.left + rect2.width / 2, rect2.top + rect2.height / 2);
                        }

                        if (lineConnected2) {
                            const rect2 = arrowRight2.getBoundingClientRect();
                            const rect3 = arrowLeft3.getBoundingClientRect();
                            updateLinePosition(line2, rect2.left + rect2.width / 2, rect2.top + rect2.height / 2,
                                rect3.left + rect3.width / 2, rect3.top + rect3.height / 2);
                        }
                    }

                    function onMouseUp() {
                        document.removeEventListener("mousemove", onMouseMove);
                        document.removeEventListener("mouseup", onMouseUp);
                    }

                    document.addEventListener("mousemove", onMouseMove);
                    document.addEventListener("mouseup", onMouseUp);
                }
            });
        });

        // Check connections when clicking on Container 3
        container3.addEventListener("click", function () {
            if (lineConnected1 && lineConnected2) {
                // All containers are connected, show the result
                openModal("modal3");
            } else {
                // Show alert if not all containers are connected
                alert("Connection failed. Please ensure all containers are connected properly.");
            }
        });

        // Helper function to update line position
        function updateLinePosition(line, x1, y1, x2, y2) {
            const length = Math.sqrt((x2 - x1) ** 2 + (y2 - y1) ** 2);
            const angle = Math.atan2(y2 - y1, x2 - x1) * (180 / Math.PI);

            line.style.width = length + "px";
            line.style.transform = `rotate(${angle}deg)`;
            line.style.left = x1 + "px";
            line.style.top = y1 + "px";
        }

        // Resize functionality
        document.querySelectorAll('.resize-handle').forEach(handle => {
            handle.addEventListener('mousedown', (e) => {
                const container = e.target.parentElement;
                let startX = e.clientX;
                let startY = e.clientY;
                let startWidth = container.offsetWidth;
                let startHeight = container.offsetHeight;

                function onMouseMove(event) {
                    const diffX = event.clientX - startX;
                    const diffY = event.clientY - startY;

                    if (handle.classList.contains('resize-bottom-right')) {
                        container.style.width = `${startWidth + diffX}px`;
                        container.style.height = `${startHeight + diffY}px`;
                    } else if (handle.classList.contains('resize-bottom-left')) {
                        container.style.width = `${startWidth - diffX}px`;
                        container.style.height = `${startHeight + diffY}px`;
                        container.style.left = `${container.offsetLeft + diffX}px`;
                    } else if (handle.classList.contains('resize-top-right')) {
                        container.style.width = `${startWidth + diffX}px`;
                        container.style.height = `${startHeight - diffY}px`;
                        container.style.top = `${container.offsetTop + diffY}px`;
                    } else if (handle.classList.contains('resize-top-left')) {
                        container.style.width = `${startWidth - diffX}px`;
                        container.style.height = `${startHeight - diffY}px`;
                        container.style.left = `${container.offsetLeft + diffX}px`;
                        container.style.top = `${container.offsetTop + diffY}px`;
                    }
                }

                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
        });
        <!----------------------------------------------------for sidebar modal----------------------------------------->
        function showModal(event) {
            const buttonRect = event.target.getBoundingClientRect(); // Get button's position
            const modal = document.getElementById('modal');

            // Position the modal next to the button with a left margin of 100px
            modal.style.top = `${buttonRect.bottom + window.scrollY}px`; // Below the button
            modal.style.left = `${buttonRect.left + 100}px`; // Align with the button plus 100px margin

            modal.style.display = 'block'; // Show the modal
        }

        // Closing function for the main modal
        function closeMainModal() {
            document.getElementById('modal').style.display = 'none'; // Hide main modal
        }

        function selectContainer(containerId) {
            // Get the selected container
            const selectedContainer = document.getElementById(containerId);

            // Check the current display state of the selected container
            if (selectedContainer.style.display === 'block') {
                // If it's already displayed, hide it
                selectedContainer.style.display = 'none';
            } else {
                // If it's not displayed, show it
                selectedContainer.style.display = 'block';
            }

        }


        // Function to open the modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block'; // Show the modal
                fetchExcelFiles();
            }
        }

        // Function to close the modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none'; // Hide the modal
            }
        }

        // Add double-click event listeners
        container1.addEventListener('dblclick', () => {
            openModal('modal1'); // Open modal1 on double-click
        });

        container2.addEventListener('dblclick', () => {
            openModal('modal2'); // Open modal2 on double-click
        });

        container3.addEventListener('dblclick', () => {
            openModal('modal3'); // Open modal3 on double-click
        });


        // Open modal to show list of Excel files
        window.onload = function () {
            // Open the modal immediately when the page loads
            openModal();
        };


        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close the Excel content view
        function closeExcelContent() {
            document.getElementById('excelContent').style.display = 'none';
        }

        // Fetch the list of Excel files from the server and display them in the modal
        // Fetch the list of Excel files from the server and display them in the modal
        function fetchExcelFiles() {
            const fileListContainer = document.getElementById('fileListContainer');
            fileListContainer.innerHTML = ''; // Clear previous content

            fetch('fetch_excel_files.php') // PHP script to fetch the list of files
                .then(response => response.json())
                .then(files => {
                    if (files.length > 0) {
                        files.forEach(file => {
                            const fileDiv = document.createElement('div');
                            fileDiv.className = 'file-item'; // Add a CSS class for styling
                            fileDiv.innerHTML = `<a href="#" onclick="openExcelFile('${file}')">${file}</a>`;
                            fileListContainer.appendChild(fileDiv);
                        });
                    } else {
                        fileListContainer.innerHTML = '<p>No Excel files found.</p>';
                    }
                })
                .catch(error => console.error('Error fetching files:', error));
        }

        // Open the selected Excel file and display its contents
        function openExcelFile(fileName) {
            document.getElementById('excelContent').style.display = 'block';

            fetch('view.php?file=' + fileName) // PHP script to fetch Excel content
                .then(response => response.json())
                .then(data => {
                    const excelTableContainer = document.getElementById('excelTableContainer');
                    excelTableContainer.innerHTML = ''; // Clear previous content

                    if (data.length > 0) {
                        const table = document.createElement('table');
                        const headerRow = document.createElement('tr');
                        data[0].forEach(cell => {
                            const th = document.createElement('th');
                            th.textContent = cell;
                            headerRow.appendChild(th);
                        });
                        table.appendChild(headerRow);

                        data.slice(1).forEach(row => {
                            const tableRow = document.createElement('tr');
                            row.forEach(cell => {
                                const td = document.createElement('td');
                                td.textContent = cell;
                                tableRow.appendChild(td);
                            });
                            table.appendChild(tableRow);
                        });
                        excelTableContainer.appendChild(table);
                    } else {
                        excelTableContainer.innerHTML = '<p>No content available in the selected file.</p>';
                    }
                })
                .catch(error => console.error('Error fetching Excel content:', error));
        }
        let calculationResult = null; // Global variable to store the result

        // Handle form submission
        document.getElementById("dataForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent normal form submission

            const formData = new FormData(this);

            // Send the form data to the server via AJAX
            fetch("script.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Store the result in the global variable
                        calculationResult = data.data;

                        alert("Calculation successful! Double-click the container to view results.");

                        // Close modal2
                        closeModal("modal2");
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while processing your request.");
                });
        });

        // Handle double-click on container3 to display the result in modal3
        document.getElementById("container3").addEventListener("dblclick", function () {
            if (calculationResult) {
                // Prepare the result to display in modal3
                let resultHTML = "<h2>Calculated Results</h2>";
                resultHTML += '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
                resultHTML += "<tr><th>Parameter</th><th>Value</th></tr>";

                // Loop through the data and create table rows
                for (const key in calculationResult) {
                    if (calculationResult.hasOwnProperty(key)) {
                        resultHTML += `<tr><td>${key}</td><td>${calculationResult[key]}</td></tr>`;
                    }
                }

                resultHTML += "</table>";

                // Show the result in modal3
                document.getElementById("result").innerHTML = resultHTML;

                // Open modal3
                openModal("modal3");
            } else {
                alert("No results available. Please submit the form first.");
            }
        });
    </script>
    </head>

</html>
