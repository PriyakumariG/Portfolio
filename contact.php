<?php
$servername = "localhost:3307";  
$username = "root";
$password = ""; 
$dbname = "message";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check request method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "All fields are required.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare SQL to avoid SQL injection
    $stmt = $mysqli->prepare("INSERT INTO portfolio (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // Show JS alert then redirect
        echo '<script>
                alert("Message sent successfully!");
                window.location.href = "index.html";
              </script>';
        exit();
    } else {
        echo "Error submitting form. Please try again.";
    }

    $stmt->close();
} else {
    // Wrong method (GET, PUT, etc.)
    http_response_code(405);
    echo "405 Method Not Allowed";
}

$mysqli->close();
?>
