<?php
session_start();

// Destroy all session data
session_destroy();

// Redirect to index.php
header('Location: admin_login.php');
exit();
?>
