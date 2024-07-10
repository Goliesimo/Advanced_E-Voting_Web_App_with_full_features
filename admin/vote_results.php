<?php
// Include your database connection code here
include 'conn.php';
include 'getPDO.php';


// Get the PDO instance
$pdo = getPDO();

// Retrieve votes data from the database
$selectVotesQuery = "SELECT candidate_name, candidate_picture, election_name, COUNT(*) AS vote_count FROM votes GROUP BY candidate_name, candidate_picture, election_name";
$stmt = $pdo->query($selectVotesQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

   </head>
   <style>
   

   </style>
   <header>
       <div class="container-fluid bg-dark">
           <div class="row justify-content-center">
               <div class="col-2">
                   <a href="index.html"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
               </div>

               <div class="col-6">
                 <nav class="navbar navbar-expand-lg navbar-light">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link text-white" href="index.html">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="candidates.php">Candidates</a>
      </li>
       <li class="nav-item">
        <a class="nav-link text-white" href="candidates/index.php">Apply for Candidacy</a>
      </li>
    
     <li class="nav-item">
        <a class="nav-link text-white" href="vote.php">Elections</a>
      </li>
    
     <li class="nav-item">
        <a class="nav-link text-white" href="vote_results.php">Votes Casted</a>
      </li>
    
    
      
    </ul>
       
  </div>
</nav>
</div>

               <div class="col-4">
                  
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-o"></i>
          User
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item btn btn-secondary" href="login.php">Login</a>
          <a class="dropdown-item  btn btn-outline-primary" href="register.php">Register</a>
          <div class="dropdown-divider"></div>

        </div>
    
               </div>
           </div>
       </div>
   </header>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Vote Results</h2>
    <?php 
// Check if there are any votes
if ($stmt->rowCount() > 0) {
    echo "<div class='container'>";
    echo "<table class='table table-striped table-bordered table-hover table-sm'>";
    echo "<tr>";

    echo "<th scope='col'>Candidate Name</th>";
    echo "<th scope='col'>Candidate Picture</th>";
    echo "<th scope='col'>Election Name</th>";
    echo "<th scope='col'>Vote Count</th>";
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['candidate_name']}</td>";
        echo "<td>";
        // Display candidate picture if available
        $candidatePicturePath = "../candidate_photos/" . $row['candidate_picture'];
        if (file_exists($candidatePicturePath)) {
            echo "<img class='candidate-photo d-flex' src='$candidatePicturePath' alt='Candidate Picture' style='height:50px; width:50px; border-radius:50%' >";
        } else {
            echo "<p>No picture</p>";
        }
        echo "</td>";
        echo "<td>{$row['election_name']}</td>";
        echo "<td>{$row['vote_count']}</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "No votes found.";
}

     ?>


  
<footer class="footer bg-dark">
    <div class="container bg-dark">
        <div class="row">
            <div class="col-3">
                <a href="index.html"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
            </div>
             <div class="col-6 d-flex">
             <input type="text" placeholder="Subscribe to our mail.."  class="m-5"> 
             <button class="btn btn-primary" value="Subscribe" type="submit">Submit</button>
            </div>
        </div>
    </div>
</footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

