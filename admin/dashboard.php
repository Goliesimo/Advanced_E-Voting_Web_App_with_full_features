<?php
// Include your database connection code
include 'conn.php';
include 'header.php';
include 'functions.php';
include 'getPDO.php';


// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

session_start();

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$query = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$admin_id]);
$user = $stmt->fetch();

// Query the database to retrieve all existing elections
$selectElectionsQuery = "SELECT * FROM elections";
$stmtElections = $pdo->query($selectElectionsQuery);
$elections = $stmtElections->fetchAll(PDO::FETCH_ASSOC);

// Query the database to retrieve all existing candidates
$selectCandidatesQuery = "SELECT * FROM candidates";
$stmtCandidates = $pdo->query($selectCandidatesQuery);
$candidates = $stmtCandidates->fetchAll(PDO::FETCH_ASSOC);

// Query the database to retrieve all existing votes
$selectVotesQuery = "SELECT * FROM votes";
$stmtvotes = $pdo->query($selectVotesQuery);
$votes = $stmtCandidates->fetchAll(PDO::FETCH_ASSOC);


// Sample system statistics display
$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$candidatesCount = $pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
$electionsCount = $pdo->query("SELECT COUNT(*) FROM elections")->fetchColumn();
$votesCount = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();


?>
<!DOCTYPE html>
<html lang="en">
<title>Admin | Dashboard</title>

  <body>
    <div class="container-scroller">
      <div class="row p-0 m-0 proBanner" id="proBanner">
       
      </div>
     <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
         <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="dashboard.php">E-Voting</a>
          <a class="sidebar-brand brand-logo-mini" href="dashboard.php">E-Voting</a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Admin Functions</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="manage_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Manage Users <a class="" href="manage_users.php"></a></span>

            </a>
          </li>
      
          <li class="nav-item menu-items">
            <a class="nav-link" href="verify_candidates.php">
              <span class="menu-icon">
                <i class="mdi mdi-playlist-play"></i>
              </span>
           <span class="menu-title">Verify Candidates <a class="" href="verify_candidates.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="candidates.php">
              <span class="menu-icon">
                <i class="mdi mdi-table-large"></i>
              </span>
           <span class="menu-title">Manage Candidates <a class="" href="candidates.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="elections.php">
              <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
              </span>
         <span class="menu-title">Create Election <a class="" href="elections.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="manage_elections.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Manage Elections <a class="" href="manage_elections.php"></a></span>
            </a>
          </li>

           <li class="nav-item menu-items">
            <a class="nav-link" href="accredit_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Accredit Voters<a class="" href="accredit_users.php"></a></span>
            </a>
          </li>

           <li class="nav-item menu-items">
            <a class="nav-link" href="verified_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Accredited Voters List<a class="" href="verified_users.php"></a></span>
            </a>
          </li>
        </ul>
      </nav>





      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                  <input type="text" class="form-control" placeholder="Search products">
                </form>
              </li>
            </ul>



            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                  <div class="navbar-profile">
                  <div class="col-4">
            <div class="user-profile-picture">
    <?php
    // Display the profile picture if available
    if ($user && isset($user['profile_picture'])) {
        $imagePath = "profile_pictures/" . $user['profile_picture'];

        if (file_exists($imagePath)) {
            echo "<img class='user-photo p-0 m-0' width='50' height='50' style='border-radius:50%; padding:0px; margin:0px;' src='$imagePath' alt='Profile Picture'>";
        } else {
            echo "<p>No profile picture - File does not exist at path: $imagePath</p>";
        }
    } else {
        echo "<p>No profile picture - User or profile picture not set</p>";
    }
    ?>
</div>
        </div>
                    <p class="m-0 p-0 d-none d-sm-block navbar-profile-name">Welcome, <?php echo $_SESSION['admin_name']; ?></p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
              
                  <div class="dropdown-divider"></div>
                 
                  <div class="dropdown-divider"></div>
                
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                       <a href="logout.php"><i class="mdi mdi-logout text-danger"></i>Logout</a>
                      </div>
                    </div>
              </li>
            </ul>




            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card container">
              <h1 class="text-center" style="text-align: center;">System Statistics</h1>            
            </div>
            <div class="row">
                <div class="col-4">
                 <h4>Total Registered Voters</h4>
              <a href="manage_users.php" class="btn btn-outline-primary p-3" style="width:100px"><?php echo $usersCount; ?></a>
              </div>

                <div class="col-4">
                 <h4>Total Candidates</h4>
              <a href="candidates.php" class="btn btn-outline-primary p-3" style="width:100px"><?php echo $candidatesCount; ?></a>
              </div>

               <div class="col-4">
                 <h4>TotalElections</h4>
              <a href="manage_elections.php" class="btn btn-outline-primary p-3" style="width:100px"><?php echo $electionsCount; ?></a>
              </div>

               <div class="col-4">
                 <h4>Total Votes Casted</h4>
              <a href="vote_results.php" class="btn btn-outline-primary p-3" style="width:100px"><?php echo $votesCount; ?></a>
              </div>

              <div class="col-4">
                <!-- Add this form for profile picture upload -->
<form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="profilePicture">Upload Profile Picture:</label>
        <input type="file" class="form-control-file" id="profilePicture" name="profile_picture" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>
              </div>

            </div>

              


                 <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
             
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
  </body>
</html>