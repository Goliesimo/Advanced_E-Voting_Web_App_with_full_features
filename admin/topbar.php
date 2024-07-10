<?php 
// Handle approval or rejection actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['voter_id'])) {
        $voterId = $_POST['voter_id'];

        // Initialize the $pdo variable
        $pdo = getPDO();

        if ($_POST['action'] === 'accredit') {
            // Perform accreditation logic here
            $updateQuery = "UPDATE voters SET status = 'accredited' WHERE voter_id = ?";
                 $updateQuery = "UPDATE users SET status = 'accredited' WHERE voter_id = ?";
            $stmt = $pdo->prepare($updateQuery);
            if ($stmt->execute([$voterId])) {
                // Send notifications for accreditation (if needed)
                echo "User accredited successfully.";
            } else {
                echo "Failed to accredit the user.";
            }
        }
    }
}


// Retrieve accredited users from the database
$selectQuery = "SELECT * FROM voters WHERE status = 'accredited'";
$selectQuery = "SELECT * FROM users WHERE status = 'accredited'";
$stmt = $pdo->query($selectQuery);
// Check if there are any users
if ($stmt->rowCount() > 0) {
    echo "<div class='container text-white'>";
    echo "<table class='table table-striped table-bordered table-hover table-sm text-white'>";
    echo "<tr class='text-crimson'>";
    echo "<th scope='col'>Voter ID</th>";
    echo "<th scope='col'>Name</th>";
    echo "<th scope='col'>Email</th>";
    echo "<th scope='col'>Picture</th>";

    echo "</tr>";
    echo " </div>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr >";
        echo "<td>{$row['voter_id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>";
        // Display user profile picture if available
        $imagePath = "profile_pictures/" . $row['profile_pictures'];
        if (file_exists($imagePath)) {
            echo "<img class='user-photo d-flex' src='profile_pictures' width='50' height='50' src='$imagePath' alt='Profile Picture'>";
        } else {
            echo "<p>No profile picture</p>";
        }
        echo "</td>";
  

        echo "</tr>";

        // Modal for accreditation
        echo "<div class='modal fade' id='accreditModal{$row['voter_id']}' tabindex='-1' role='dialog' aria-labelledby='accreditModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='accreditModalLabel'>Accredit User</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='modal-body'>";
        echo "<p>Are you sure you want to accredit this user?</p>";
        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='action' value='accredit'>";
        echo "<input type='hidden' name='voter_id' value='{$row['voter_id']}'>";
        echo "<button type='submit' class='btn btn-outline-primary' id='accreditConfirmBtn{$row['voter_id']}'>Yes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    echo "</table>";
} else {
     echo "No accredited users found.";
}
 ?>
</div>
</div>
  <script>
    // JavaScript function to handle user accreditation
    function handleAccreditationConfirmation(voterId) {
        // Confirm accreditation
        var confirmation = confirm('Are you sure you want to toggle the accreditation status for this user?');

        if (confirmation) {
            // Implement the logic to toggle the accreditation status
            // For demonstration purposes, let's use AJAX to update the accreditation status

            // Change button text based on the current status
            var accreditButton = document.getElementById('accreditBtn' + voterId);
            if (accreditButton.innerHTML === 'Accredit') {
                accreditButton.innerHTML = 'Unaccredit';
            } else {
                accreditButton.innerHTML = 'Accredit';
            }

            // Simulate accreditation toggle (replace this with your actual logic)
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Successfully updated the accreditation status
                    alert('Accreditation status toggled for user with ID ' + voterId);
                }
            };
            
            // Send an AJAX request to update the accreditation status
            xhr.open('GET', 'update_accreditation.php?voterId=' + voterId, true);
            xhr.send();
        }
    }
</script>