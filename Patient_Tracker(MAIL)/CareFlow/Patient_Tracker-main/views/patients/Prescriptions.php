<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Report</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7; /* Light background for a clean look */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            color: #003366; /* Dark blue for headings */
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Image Styles */
        .prescription-image {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 0 auto 20px; /* Center image and add margin below */
            border: 1px solid #ddd; /* Optional border for image */
            border-radius: 8px; /* Rounded corners for the image */
        }

        /* Link Styles */
        .download-container {
            display: flex; /* Use flexbox to align items */
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Center items vertically */
            margin-top: 20px; /* Add space above the download section */
        }

        .download-link {
            display: inline-block; /* Keep as inline block */
            width: 200px; /* Set a width for the button */
            text-align: center; /* Center text inside the button */
            color: #003366; /* Dark blue for links */
            font-weight: bold;
            text-decoration: none;
            padding: 10px 0; /* Padding for top and bottom */
            border: 1px solid #003366;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-left: 20px; /* Space between image and button */
        }

        .download-link:hover {
            background-color: #003366;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Prescription Report</h2>
        <p>You can download your prescription report below.</p>
        
        <!-- Prescription Image -->
        <img src="images/IMG_8659.JPG" alt="Prescription Preview" class="prescription-image">

        <!-- Download Section -->
        <div class="download-container">
            <a href="records/prescription.pdf" class="download-link" download="Prescription.pdf">Download Prescription</a>
        </div>
    </div>
</body>
</html>
