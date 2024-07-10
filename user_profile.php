<?php
include 'conn.php';
include 'getPDO.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Redirect to the login page if the user is not logged in
    exit();
}


$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, voter_id, profile_picture FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<header>
       <div class="container-fluid bg-dark">
           <div class="row justify-content-around">
               <div class="col-2 mt-4">
                   <a href="dashboard.php"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
               </div>

               <div class="col-5">
                 <nav class="navbar navbar-expand-lg navbar-light">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link text-white" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
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

               <div class="col-3 mt-4">
             <a href="user_profile.php" class="btn btn-primary m-0 p-0"><?php echo $user['name']; ?>!</a>
  
        
        </div>


        
               <div class="col-2 mt-4">
        <a class="nav-link dropdown-toggle text-white m-0 p-0 w-0" style="width: 0px; padding: 0px; margin: 0px;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          User
        </a>
        <div class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
              <a href="user_profile.php" class=" m-0 p-0 btn btn-primary m-2 p-2"><?php echo $user['name']; ?>!</a>
         <?php
    // Display the profile picture if available
    if ($user && isset($user['profile_picture'])) {
        $imagePath = "profile_pictures/" . $user['profile_picture'];

        if (file_exists($imagePath)) {
            echo "<img class='user-photo d-flex' width='50' height='50' style='border-radius:50%;' src='$imagePath' alt='Profile Picture'>";
        } else {
            echo "<p>No profile picture - File does not exist at path: $imagePath</p>";
        }
    } else {
        echo "<p>No profile picture - User or profile picture not set</p>";
    }
    ?> <br>
    
    <a href="logout.php" class="btn btn-danger">Log Out</a> <!-- Include a logout link -->
        

        </div>
        </div>
           </div>
       </div>
   </header>
<body>



<div class="container">
    <div class="profiles mt-5">
        <div class="row">
            <div class="col-4 bg-dark text-white p-2 m-4 d-flex justify-content-between">
          
                     <?php
    // Display the profile picture if available
    if ($user && isset($user['profile_picture'])) {
        $imagePath = "profile_pictures/" . $user['profile_picture'];

        if (file_exists($imagePath)) {
            echo "<img class='user-photo d-flex' width='50' height='50' style='border-radius:50%;' src='$imagePath' alt='Profile Picture'>";
        } else {
            echo "<p>No profile picture - File does not exist at path: $imagePath</p>";
        }
    } else {
        echo "<p>No profile picture - User or profile picture not set</p>";
    }
    ?>
                <!-- Add this form for profile picture upload -->
<form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="profilePicture">Upload/Change Profile Picture:</label>
        <input type="file" class="form-control-file" id="profilePicture" name="profile_picture" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

            </div>


            <div class="col-3 bg-dark text-white">
                 <p>Name:<br> <b style="font-size: 20px; text-transform: uppercase;"><?php echo $user['name']; ?></b></p>
            </div>

             <div class="col-3 bg-dark text-white">
                    <p>Here's your unique Voter ID: <b style="font-size: 20px"><?php echo $user['voter_id']; ?></b></p>
            </div>
        </div>
    </div>
</div>







   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
