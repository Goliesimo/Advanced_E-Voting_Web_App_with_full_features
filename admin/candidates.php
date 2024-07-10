<?php
include 'conn.php';
include 'getPDO.php';
include 'header.php';

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

// Query to retrieve candidates from the database
$query = "SELECT * FROM candidates";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<title>Candidates List</title>

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

         <?php
        // Check if there are candidates to display
        if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch()) {
        // Wrap the content in an <a> tag with a link to candidate_details.php
       
        echo '<div class="container-fluid" style="margin-top:200px">';
        echo '<div class="row">';
        echo '<div class=" col-12">';
        echo '<a href="candidate_details.php?id=' . $row['id'] . '" class="candidate-link"> <img class="candidate-photo mr-3 mb-4" width="100" height="100" style="border-radius:50%" src="../candidate_photos/' . $row['photo'] . '" alt="Candidate Photo"></a>';

        echo '<img class="candidate-photo mr-3 mb-4" style="display:none" width="50" height="50" src="../party_logos/' . $row['photo'] . '" alt="Candidate Photo">';

        echo '<div class="" style="font-size:20px; font-weight:bold">';
        echo '<p style="font-weight:bold; font-size:30px;">' . $row['name'] . '</p>';
        echo '<p>School of Study: ' . $row['party'] . '</p>';
        echo '<p>Position: ' . $row['position'] . '</p>';
        echo '<p>Age: ' . $row['age'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
               

                // Add Bootstrap buttons for edit and delete
                echo '<div class="btn-group" role="group" aria-label="Candidate Actions">';
                echo '<button type="button" class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editModal' . $row['id'] . '">Edit</button>';
                echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal' . $row['id'] . '">Delete</button>';
                echo '</div>';
                echo '<br>';

                // Modal for delete confirmation
                echo '<div class="modal fade" id="deleteModal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">';
                echo '   <div class="modal-dialog" role="document">';
                echo '       <div class="modal-content">';
                echo '           <div class="modal-header">';
                echo '               <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>';
                echo '               <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '                   <span aria-hidden="true">&times;</span>';
                echo '               </button>';
                echo '           </div>';
                echo '           <div class="modal-body">';
                echo '               Are you sure you want to delete this candidate?';
                echo '           </div>';
                echo '           <div class="modal-footer">';
                echo '               <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>';
                echo '               <a href="delete_candidate.php?id=' . $row['id'] . '" class="btn btn-danger">Yes</a>';
                echo '           </div>';
                echo '       </div>';
                echo '   </div>';
                echo '</div>';


// Modal for editing candidate
echo '<div class="modal fade" id="editModal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">';
echo '   <div class="modal-dialog" role="document">';
echo '       <div class="modal-content">';
echo '           <div class="modal-header">';
echo '               <h5 class="modal-title" id="editModalLabel">Edit Candidate</h5>';
echo '               <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
echo '                   <span aria-hidden="true">&times;</span>';
echo '               </button>';
echo '           </div>';
echo '           <div class="modal-body">';
echo '               <form action="edit_candidate.php" method="post">';
echo '                   <input type="hidden" name="id" value="' . $row['id'] . '">';
echo '                   <div class="form-group">';
echo '                       <label for="name">Candidate Name:</label>';
echo '                       <input type="text" class="form-control text-white" name="name" value="' . $row['name'] . '" required>';
echo '                   </div>';
echo '                   <div class="form-group">';
echo '                       <label for="party">School of Study:</label>';
echo '                       <select class="form-control text-white" name="party" required>';
echo '                           <option value="Applied Science">Applied Science</option>';
echo '                           <option value="Arts">Arts</option>';
echo '                           <option value="Pure Science">Pure Science</option>';
echo '                           <option value="Social Science">Social Science</option>';
echo '                           <option value="PPE">Management</option>';

echo '                       </select>';
echo '                   </div>';
echo '                   <div class="form-group">';
echo '                       <label for="position">Position:</label>';
echo '                       <select class="form-control text-white" name="position" required>';
echo '                           <option value="President">SUG President</option>';
echo '                           <option value="Governor">Vice President</option>';
echo '                           <option value="LGA Chairman">Secretary</option>';
echo '                           <option value="President">PRO</option>';
echo '                           <option value="Governor">Provost</option>';
echo '                           <option value="LGA Chairman">CSO Chairman</option>';
echo '                       </select>';
echo '                   </div>';
echo '                   <div class="form-group">';
echo '                       <label for="age">Age:</label>';
echo '                       <input type="number" class="form-control text-white" name="age" value="' . $row['age'] . '">';
echo '                   </div>';
echo '               </div>';
echo '               <div class="modal-footer">';
echo '                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
echo '                   <button type="submit" name="update" class="btn btn-primary">Update Candidate</button>';
echo '               </div>';
echo '               </form>';
echo '           </div>';
echo '       </div>';
echo '   </div>';
echo '</div>';


            }
        } else {
            echo '<p>No candidates available.</p>';
        }
        ?>


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
        <div class="container" style="text-align: center;">
         <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#addCandidateModal">Add a New Candidate</button>
          
       
      <!-- Modal for adding a new candidate -->
<div class="modal fade" id="addCandidateModal" tabindex="-1" role="dialog" aria-labelledby="addCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCandidateModalLabel">Add New Candidate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add the form to add a new candidate here -->
                <form class="form-group" action="add_candidate.php" method="post" enctype="multipart/form-data">
                    <input type="text" class="form-group" name="name" placeholder="Candidate Name" required>
                    <input type="text" class="form-group" name="party" placeholder="Department">
                    <input type="text" class="form-group" name="position" placeholder="Position" required>
                    <input type="file" class="form-group" name="photo" accept="image/*" required>
                    <input type="file" class="form-group" name="party_logo" accept="image/*">
                    <input type="number" class="form-group" name="age" placeholder="Age">
                    <button type="submit" class="btn-success">Add Candidate</button>
                </form>
            </div>
        </div>
    </div>
</div>

    </div>



    <script>
        // Function to show the delete confirmation modal
        function showDeleteModal(candidateId) {
            $('#deleteModal' + candidateId).modal('show');
        }

        // Function to show the edit modal
        function showEditModal(candidateId) {
            $('#editModal' + candidateId).modal('show');
        }

        // Attach click event to the "Delete" buttons
        $('.btn-delete').click(function() {
            var candidateId = $(this).data('candidate-id');
            showDeleteModal(candidateId);
        });

        // Attach click event to the "Edit" buttons
        $('.btn-edit').click(function() {
            var candidateId = $(this).data('candidate-id');
            showEditModal(candidateId);
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
  </body>
</html>

