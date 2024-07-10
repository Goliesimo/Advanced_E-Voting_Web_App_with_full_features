<?php
include 'conn.php';

function generateVoterID() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voterID = '';

    // Generate a random 8-character Voter ID
    for ($i = 0; $i < 8; $i++) {
        $voterID .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $voterID;
}

$isAdmin = 1; // Set $isAdmin to 1 if the user is an admin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $matricNumber = $_POST['matric_number'];
    $school = $_POST['school'];
    $department = $_POST['department'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    if (empty($name) || empty($email) || empty($password) || empty($matricNumber) || empty($school) || empty($department) || empty($dob) || empty($gender)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    }

    // Validate the date format
        if (strtotime($dob) === false) {
            echo "Invalid date format for Date of Birth";
        } 

     else {
        // Check if the email is already in use
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $emailCount = $stmt->fetchColumn();

        if ($emailCount > 0) {
            echo "This email is already in use. Please use a different email address.";
        } else {
            // Generate a unique Voter ID
            $voterID = generateVoterID();

            // Hash the user's password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user information and Voter ID into the database
            $insertQuery = "INSERT INTO users (name, email, password, voter_id, matric_number, school, department, dob, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [$name, $email, $hashedPassword, $voterID, $matricNumber, $school, $department, $dob, $gender];
            
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute($params);

            if ($insertStmt) {
                echo "Registration successful. <a href='login.php'>Login here</a>";
            } else {
                echo "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="matric_number" placeholder="Matric Number" required>
            <input type="text" name="school" placeholder="School" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="date" name="dob" placeholder="Date of Birth" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
        <div id="error-message" style="color: red;"></div> <!-- Display error messages here -->
    </div>
</body>
</html>
