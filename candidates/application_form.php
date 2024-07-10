<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Application Form</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <h1 class="text-center">Candidate Application Form</h1>
    <div class="container col-4">
        <form action="submit_application.php" method="post">
        <label for="full_name">Full Name:</label> <br>
        <input type="text" class="form-group" name="full_name" id="full_name" required>
        <br><br>

        <label for="age">Age:</label> <br>
        <input type="number" class="form-group" name="age" id="age" required>
        <br><br>

        <label for="educational_status">Matric No:</label> <br>
        <input type="text" class="form-group" name="educational_status" id="educational_status" required>
        <br><br>

        <label for="party_affiliation">Department:</label> <br>
        <input type="text" class="form-group" name="party_affiliation" id="party_affiliation" required>
        <br><br>

        <label for="why_should_be_voted">Position your Applying for</label> <br> 
        <input type="text" class="form-group" name="why_should_be_voted" id="why_should_be_voted" rows="6" cols="50" required></input>
        <br><br>

        <button type="submit" class="btn btn-outline-success">Submit Application</button>
    </form>
    </div>
    
</body>
</html>
