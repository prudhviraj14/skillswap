<?php
// Database connection details
$host = 'localhost';
$db = 'skillswap_';  // Database name
$user = 'root';      // Username for your database
$pass = 'prudhvi@30'; // Password for your database

// Create a connection to MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $channelName = $conn->real_escape_string($_POST['channelName']);
    $channelLink = $conn->real_escape_string($_POST['channelLink']);
    $programToTrain = $conn->real_escape_string($_POST['programToTrain']);
    $certification = $conn->real_escape_string($_POST['certification']);
    $email = $conn->real_escape_string($_POST['email']);

    // Insert form data into the database
    $query = "INSERT INTO youtube_collaborations (channel_name, channel_link, program_to_train, provides_certification, contact_email)
              VALUES ('$channelName', '$channelLink', '$programToTrain', '$certification', '$email')";

    if ($conn->query($query) === TRUE) {
        echo "Success: Your collaboration request has been submitted.";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
