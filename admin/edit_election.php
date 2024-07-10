<?php
include 'conn.php';

session_start();

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();


}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Retrieve the election's information
    $electionId = $_GET['id'];
    $query = "SELECT * FROM elections WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$electionId]);

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
    } else {
        echo "Election not found.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Handle the election information update
    $electionId = $_POST['id'];
    $electionName = $_POST['electionName'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $description = $_POST['description'];

    $query = "UPDATE elections SET name = ?, start_date = ?, end_date = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$electionName, $startDate, $endDate, $description, $electionId])) {
        // Election information updated successfully
         $alertMessage = 'Election status successfully updated';
        $alertType = 'success';
        header('Location: manage_elections.php'); // Redirect to manage_elections page
        exit();
    } else {
          $alertMessage = 'Election information update failed. Please try again.';
        $alertType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election</title>
    <link rel="stylesheet" href="../styles.css">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
     
    <div class="container">
        <div class="row">
            <div class="col-8">
                    <h1>Edit Election</h1>
            </div>
            <div class="col-3">
                    <a href="manage_elections.php" class="btn btn-outline-primary">Go back</a>
                </div>
        </div>
    </div>

    <div class="container">
        <form action="edit_election.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="electionName">Election Name:</label>
                <input type="text" class="form-control" name="electionName" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" name="start_date" value="<?php echo $row['start_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" name="end_date" value="<?php echo $row['end_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description"><?php echo $row['description']; ?></textarea>
            </div>
         
            <button type="submit" name="update" class="btn btn-primary">Update Election</button>
        </form>
    </div>

    <!-- Add Bootstrap JavaScript and jQuery links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
