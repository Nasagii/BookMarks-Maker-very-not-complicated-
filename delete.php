<?php
session_start();
include 'config.php';

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete book from the database
    $query = $conn->prepare("DELETE FROM books WHERE id=:id");
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $result = $query->execute();

    if (!$result) {
        echo "Erreur lors de la suppression du livre.";
        exit;
    }
}

// Redirect to dashboard.php after deleting the book
header('Location: dashboard.php');
exit;
?>
