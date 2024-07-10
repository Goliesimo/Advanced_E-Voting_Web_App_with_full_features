<?php 

include 'conn.php'; 

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Vote for Your Candidate</h2>
        <form action="vote.php" method="post">
            <!-- List candidates and their information here -->
            <!-- Use radio buttons to allow users to select a candidate -->
            <!-- You can populate this dynamically from your database using PHP -->
            <input type="radio" name="candidate" value="1" required> Candidate 1
            <input type="radio" name="candidate" value="2" required> Candidate 2
            <input type="radio" name="candidate" value="3" required> Candidate 3
            <!-- Add a submit button to cast the vote -->
            <button type="submit">Vote</button>
        </form>
    </div>
</body>
</html>
