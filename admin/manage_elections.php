<?php
// Include your database connection code
include 'conn.php';
include 'getPDO.php';
include 'header.php';


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

// Sample system statistics display
$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$candidatesCount = $pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
$electionsCount = $pdo->query("SELECT COUNT(*) FROM elections")->fetchColumn();


?>

<!DOCTYPE html>
<html lang="en">
<title>Manage Eelctions</title>

  <body>
    <div class="container-fluid">
      <div class="row p-0 m-0 proBanner" id="proBanner">
       
      </div>
      <!-- partial:partials/_sidebar.html -->
     


      

      <!-- partial -->

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 container col-5 d-flex flex-row mt-0" style="background: transparent;">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        
         
   


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



   <div class="container mt-4">
        <!-- Bootstrap Alert -->
        <?php if (!empty($alertMessage)): ?>
            <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                <?php echo $alertMessage; ?>
            </div>
        <?php endif; ?>
    </div>

 <div class="container">
    <div class="row">
        <div class="col-5">
            <h1 style="text-center">Edit Election</h1>
        </div>
        <div class="col-5">
            <a href="dashboard.php" class="btn btn-outline-primary">Back</a>
        </div>
    </div>
     
   
 </div>
        
<table class="table table-bordered table-sm text-white">
      <thead>
    <tr class="text-center text-white">
        <th scope="col">Name</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Description</th>
 
        <th scope="col">Action</th>
         <th scope="col">Select a Candidate</th>
          <th scope="col">Candidates</th>
      
    </tr>
</thead>
  <tbody class="">
    <?php foreach ($elections as $election): ?>
    <tr>
       
        <td class="col-2"><?php echo $election['name']; ?></td>
        <td><?php echo $election['start_date']; ?></td>
        <td><?php echo $election['end_date']; ?></td>
        <td class="col-3"><?php echo $election['description']; ?></td>
       
        <td class="">
            <a class="btn btn-primary mr-2" href="edit_election.php?id=<?php echo $election['id']; ?>">Edit</a> <br> <br>
            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $election['id']; ?>)">Delete</button>
        </td>
      
                <td class="">
                <form action="associate_candidates.php" method="post">
                    <label for="election">Select Election:</label> <br>
                    <select name="election" id="election">
                        <?php foreach ($elections as $electionOption): ?>
                            <option value="<?php echo $electionOption['id']; ?>"><?php echo $electionOption['name']; ?></option>
                        <?php endforeach; ?>
                    </select> <br> <br>


                    <?php 

                    // Query the database to retrieve all existing candidates
$selectCandidatesQuery = "SELECT * FROM candidates";
$stmtCandidates = $pdo->query($selectCandidatesQuery);
$candidates = $stmtCandidates->fetchAll(PDO::FETCH_ASSOC);

                     ?>

                   <label for="candidate">Select Candidate:</label> <br>
<select name="candidate" id="candidate">
    <?php foreach ($candidates as $candidateOption): ?>
        <option value="<?php echo $candidateOption['id']; ?>"><?php echo $candidateOption['name']; ?></option>
    <?php endforeach; ?>
</select> <br>


                    <button class="btn btn-outline-success" type="submit">Associate Candidate</button>
                </form>
            </td>

<!-- Display Associated Candidates under the corresponding <td> -->
<td class="row">
    <?php
    // Retrieve associated candidates for the current election
    $associatedCandidatesQuery = "SELECT candidates.* FROM candidates
                                  JOIN election_candidates ON candidates.id = election_candidates.candidate_id
                                  WHERE election_candidates.election_id = :election_id";
    $associatedCandidatesStmt = $pdo->prepare($associatedCandidatesQuery);
    $associatedCandidatesStmt->execute(['election_id' => $election['id']]);
    $associatedCandidates = $associatedCandidatesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are associated candidates
    if (!empty($associatedCandidates)) {
        foreach ($associatedCandidates as $associatedCandidate) {
            ?>
            <div class="d-flex align-items-center mb-2">
                <?php
                $imagePath = "../candidate_photos/" . $associatedCandidate['photo'];
                if (file_exists($imagePath)) {
                    echo "<img class='party-photo d-flex' width='50' height='50' src='$imagePath' alt='Candidate Photo'>";
                } else {
                    echo "<p>Image not found</p>";
                }
                ?>

                <p class="ml-2"><?php echo $associatedCandidate['name']; ?></p>

                <!-- Add remove button with a confirmation modal for each candidate -->
                <button type="button" class="btn btn-outline-danger btn-sm ml-auto" data-toggle="modal" data-target="#confirmRemoveModal<?php echo $associatedCandidate['id']; ?>">Remove</button>

                <!-- Confirmation Modal -->
                <div class="modal fade" id="confirmRemoveModal<?php echo $associatedCandidate['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmRemoveModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmRemoveModalLabel">Confirm Removal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to remove <?php echo $associatedCandidate['name']; ?> from this election?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form action="remove_candidate.php" method="post">
                                    <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
                                    <input type="hidden" name="candidate_id" value="<?php echo $associatedCandidate['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No associated candidates for this election.</p>";
    }
    ?>
</td>



        </tr>
    <?php endforeach; ?>
</tbody>
</table>   

      <!-- main-panel ends -->
      </div>

<script>
    function confirmDelete(electionId) {
        var confirmation = confirm("Are you sure you want to delete this election?");
        if (confirmation) {
            // Redirect to delete_election.php with the election ID to perform the delete action
            window.location.href = "delete_election.php?id=" + electionId;
        }
    }
</script>


    <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
             
            </div>
          </footer>
          <!-- partial -->
      
  
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    
  </body>
</html>


