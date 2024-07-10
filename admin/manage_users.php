<?php
include 'conn.php';
include 'header.php';
include 'getPDO.php';

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

?>

<?php
// Handle actions (Remove, Edit, Ban)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        // Remove the selected user
        $userId = $_POST['user_id'];

        // Implement user removal logic here
        // Example: You can use a DELETE SQL query to remove the user from the database
        // Replace 'your_delete_query' with the actual SQL query to remove the user by ID
        $deleteQuery = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($deleteQuery);
        if ($stmt->execute([$userId])) {
            // Redirect to this page after removal
            $alertMessage = 'successfull';
        $alertType = 'success';
            header('Location: manage_users.php');
            exit();
        } else {
            // Handle error if the removal fails
            $alertMessage = 'User removal failed. Please try again.';
        $alertType = 'success';
          
        }
    } elseif (isset($_POST['edit'])) {
        // Edit user details
        // Redirect to user edit page with user_id
        $userId = $_POST['user_id'];
        $alertMessage = 'successfull';
        $alertType = 'success';
        header('Location: edit_user.php?user_id=' . $userId);
        exit();
    } 
}

// Fetch a list of users from the database
$query = "SELECT * FROM users";
$stmt = $pdo->query($query);
?>

    <!-- Check if the success message is set in the URL -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Election Successfully Created
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">
<title>View Users</title>

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
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                  <input type="text" class="form-control" placeholder="Search something...">
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
<body>




     <div class="container" style="margin-top:100px">
<table class="table text-white table-bordered table-sm">
    <thead>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Action</th>
         <th scope="col">Picture</th>

    </tr> 
    </thead>
    <tbody>
         <?php
    
    while ($row = $stmt->fetch()) {
    echo '<tr>';

    echo '<td>' . $row['name'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
     
    echo '<td>';
    echo '<form method="post">';
    echo '<input type="hidden" name="user_id" value="' . $row['id'] . '">';
    echo '<button type="submit" class="btn btn-danger" name="remove">Remove</button>';
    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal' . $row['id'] . '">Edit</button>';
    // Add the "Add Profile Picture" button here


    echo '</form>';
    echo '</td>';
       echo "<td>";
        // Display user profile picture if available
        if (isset($row['profile_picture'])) {
            $imagePath = "../profile_pictures/" . $row['profile_picture'];
            if (file_exists($imagePath)) {
                echo "<img class='user-photo' src='$imagePath' width='50' height='50' alt='Profile Picture'>";
            } else {
                echo "<p>No profile picture</p>";
            }
        } else {
            echo "<p>No profile picture</p>";
        }
        echo "</td>";
    echo '</tr>';


}
    ?>

    <!-- Modal for editing user details -->
    <?php
    $stmt = $pdo->query($query); // Re-fetch users for modal population
    while ($row = $stmt->fetch()) {
        echo '<div class="modal fade" id="editModal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">';
        echo '   <div class="modal-dialog" role="document">';
        echo '       <div class="modal-content">';
        echo '           <div class="modal-header">';
        echo '               <h5 class="modal-title" id="editModalLabel">Edit User</h5>';
        echo '               <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        echo '                   <span aria-hidden="true">&times;</span>';
        echo '               </button>';
        echo '           </div>';



        echo '           <div class="modal-body">';
        echo '               <form action="edit_user.php" method="post">';
        echo '               <input type="hidden" name="user_id" value="' . $row['id'] . '">';
        echo '                   <div class="form-group">';
        echo '                       <label for="name">User Name:</label>';
        echo '    <input type="text" class="form-control text-white" name="name" value="' . $row['name'] . '" required>';
        echo '                   </div>';
        echo '                   <div class="form-group">';
        echo '                       <label for="email">Email:</label>';
        echo '  <input type="email" class="form-control text-white" name="email" value="' . $row['email'] . '" required>';
        echo '                   </div>';

           echo '                   <div class="form-group">';
        echo '                       <label for="email">Password:</label>';
        echo '  <input type="password" class="form-control text-white" name="password" value="' . $row['password'] . '" required>';
        echo '                   </div>';
        echo '               </div>';
        echo '               <div class="modal-footer">';
        echo '                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        echo '                   <button type="submit" name="update" class="btn btn-outline-primary">Update User</button>';
        echo '               </div>';
        echo '               </form>';
        echo '           </div>';
        echo '       </div>';
        echo '   </div>';
        echo '</div>';
    }
    ?>
    </tbody>
</table>
 </div>
</div>

   
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
  </body>
</html>


