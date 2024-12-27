<?php
// MySQL database credentials
$host = "localhost"; // MySQL hostname
$port = "3306";            // MySQL port
$dbname = "userDB";        // Database name
$username = "root";        // MySQL username
$password = "Password.com"; // MySQL password

try {
    // Connect to MySQL database using PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8"; // Add port explicitly
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE, -- Added UNIQUE constraint for email
                password VARCHAR(255) NOT NULL
            )";
    $conn->exec($sql);

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Sanitize and validate user input
        $user_name = htmlspecialchars(trim($_POST['username']));
        $user_email = htmlspecialchars(trim($_POST['email']));
        $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Ensure input values are not empty
        if (!empty($user_name) && !empty($user_email) && !empty($_POST['password'])) {
            // Insert the user data into the table
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $user_name);
            $stmt->bindParam(':email', $user_email);
            $stmt->bindParam(':password', $user_password);

            if ($stmt->execute()) {
                echo "User registered successfully!";
            } else {
                echo "Error registering user.";
            }
        } else {
            echo "All fields are required!";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
