<?php
include 'conn.php';
include 'getPDO.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to the login page if the user is not logged in
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
                   <a href="index.php"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
               </div>

               <div class="col-5">
                 <nav class="navbar navbar-expand-lg navbar-light">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link text-white" href="index.php">Home <span class="sr-only">(current)</span></a>
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
    <div class="container text-center">
        <h1>Welcome to the School Voting System</h1>
   
    </div>


    <div id="carouselExampleIndicators" class="container carousel slide" style="margin-bottom: 100px;" data-ride="carousel">
 
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" style="height:400px" src="1679754742486.png" alt="First slide">
      <div class="carousel-caption d-none d-md-block">
    <h5>Best Way to vote online</h5>
    <p>Voting made easy</p>
  </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" style="height:400px" src="IMG_1533-1-scaled-e1630683971807.jpg" alt="Second slide">
      <div class="carousel-caption d-none d-md-block">
    <h5>Vote your Favourite candidate</h5>
    <p>The choice is yours</p>
  </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" style="height:400px" src="Voting-image-6-scaled.jpg" alt="Third slide">
      <div class="carousel-caption d-none d-md-block">
    <h5>...</h5>
    <p>...</p>
  </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


<footer class="footer bg-dark">
    <div class="container bg-dark">
        <div class="row">
            <div class="col-3">
                <a href="index.php"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
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
