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
                echo "<h3 class='text-center'>Registration successful. <a href='login.php'>Login here</a></h3>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User | Sign Up</title>
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
    <style>
        input[type=text]{
padding: 5px;
margin: 10px;
width: 100%;
box-shadow: #f2f2f2;
border-radius: 5px;
color: black;
        }

            input[type=email]{
padding: 5px;
margin: 10px;
width: 100%;
box-shadow: #f2f2f2;
border-radius: 5px;
color: black;
        }

            input[type=password]{
padding: 5px;
margin: 10px;
width: 100%;
box-shadow: #f2f2f2;
border-radius: 5px;
color: black;
        }
                input[type=date]{
padding: 5px;
margin: 10px;
width: 100%;
box-shadow: #f2f2f2;
border-radius: 5px;
color: black;
        }

        label{
            color: white;
        }
        select{
            width:100%;
        }
    </style>
    <div class="container col-6">
        <h2>User Registration</h2>
        <div class="register bg-dark" style="text-align: center; padding: 10px;">
                 <form class="form" action="register.php" method="post">
            <input class="form-group" type="text" name="name" placeholder="Full Name" required> <br>
            <input class="form-group" type="email" name="email" placeholder="Email" required> <br>
            <input class="form-group" type="password" name="password" placeholder="Password" required> <br>
            <input class="form-group" type="text" name="matric_number" placeholder="Matric Number" required> <br>
            <input class="form-group" type="text" name="school" placeholder="School" required> <br>
            <input class="form-group" type="text" name="department" placeholder="Department" required> <br>
            <label for="dob">Date of Birth</label> <br>
            <input class="form-group" type="date" name="dob" placeholder="Date of Birth" required> <br>

            <label for="gender">Gender:</label> <br>
            <select class="form-group" name="gender" required> <br>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select> <br>

            <button type="submit" class="btn btn-primary">Register</button> <br>
        </form>
         </div>
        <p>Already have an account? <a href="login.php">Log in</a></p> <br>
        <div id="error-message" style="color: red;">
            
        </div> <!-- Display error messages here -->
       
   
    </div>
<footer class="footer bg-dark">
    <div class="container bg-dark">
        <div class="row">
            <div class="col-3">
                <a href="index.html"><img src="Voting-image-6-scaled.jpg" height="70" width="70" alt=""></a>
            </div>
             <div class="col-6">
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
