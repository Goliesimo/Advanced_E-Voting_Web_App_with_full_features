<?php
include 'conn.php';

// Query to retrieve candidates from the database
$query = "SELECT * FROM candidates";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligible | Candidates</title>
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
        <a class="nav-link text-white" href="#">Home <span class="sr-only">(current)</span></a>
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
    <h1 class="text-center">Candidates for Upcoming Election</h1>
    <div class="container candidates-list">
        <?php
        // Check if there are candidates to display
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                echo '<div class="candidate row justify-content-between">';
                echo '<img class="candidate-photo" width="100" height="100" src="candidate_photos/' . $row['photo'] . '" alt="Candidate Photo">';

                echo '<img class="party-logo d-none" width="100" height="100" src="party_logos/' . $row['photo'] . '" alt="Candidate Photo">'; 

                 echo '<p style="font-weight:bold; font-size:30px;">' . $row['name'] . '</p>';
                echo '<p>Party: ' . $row['party'] . '</p>';
                echo '<p>Position: ' . $row['position'] . '</p>';
                echo '<p>Age: ' . $row['age'] . '</p>'; 
                echo '</div>';
            }
        } else {
            echo '<p>No candidates available for the upcoming election.</p>';
        }
        ?>
    </div>

    

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