<?php
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Retrieve the candidate's information
    $candidateId = $_GET['id'];
    $query = "SELECT * FROM candidates WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$candidateId]);

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
    } else {
        echo "Candidate not found.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Handle the candidate information update
    $candidateId = $_POST['id'];
    $name = $_POST['name'];
    $party = $_POST['party'];
    $position = $_POST['position'];
    $age = $_POST['age'];

    $query = "UPDATE candidates SET name = ?, party = ?, position = ?, age = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$name, $party, $position, $age, $candidateId])) {
        // Candidate information updated successfully
        header('Location: candidates.php'); // Redirect to candidates page
        exit();
    } else {
        echo "Candidate information update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate</title>
    <link rel="stylesheet" href="../styles.css">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Edit Candidate</h1>
    <div class="container">
        <form action="edit_candidate.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="name">Candidate Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="party">Party Name:</label>
                <input type="text" class="form-control" name="party" value="<?php echo $row['party']; ?>">
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" class="form-control" name="position" value="<?php echo $row['position']; ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" name="age" value="<?php echo $row['age']; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Candidate</button>
        </form>
    </div>
    
    <!-- Add Bootstrap JavaScript and jQuery links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>