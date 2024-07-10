<?php
function generateVoterID() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voterID = '';

    // Generate a random 8-character Voter ID
    for ($i = 0; $i < 8; $i++) {
        $voterID .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $voterID;
}
?>
