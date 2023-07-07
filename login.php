<?php
session_start();
include 'config.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the provided login information is valid
    $query = $conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
    $query->bindParam(":username", $username, PDO::PARAM_STR);
    $query->bindParam(":password", $password, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        
    } else {
        // If the login information is valid, store the user ID in the session and redirect to the dashboard
        $_SESSION['user_id'] = $result['id'];
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="logStyles.css">
</head>
<body>
<div class="login-container">
    <div class="siteTitle">
        <h1>BookMarks Maker</h1>
    </div>
    <div class="login-form">
        <p class="login-text">Veuillez vous connecter</p>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Login">
        </form>
        <div class="error-message">
            <?php if (isset($result) && !$result) {
                echo 'Your login information is not valid. Please try again.';
            } ?>
        </div>
    </div>
</div>
</body>
</html>
