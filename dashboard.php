<?php
session_start();
include 'config.php';

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Process adding a book
if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $pub_year = $_POST['pub_year'];

    $query = $conn->prepare("INSERT INTO books (title, author, pub_year) VALUES (:title, :author, :pub_year)");
    $query->bindParam(":title", $title);
    $query->bindParam(":author", $author);
    $query->bindParam(":pub_year", $pub_year);
    $query->execute();

    // Redirect user to dashboard.php with a GET request after adding the book
    header('Location: dashboard.php');
    exit;
}

$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];

    // Fetch the book to edit from the database
    $query = $conn->prepare("SELECT * FROM books WHERE id=:id");
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $book_to_edit = $query->fetch();
}

// Process updating a book
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $pages = $_POST['pages'];

    $query = $conn->prepare("UPDATE books SET title=:title, author=:author, pages=:pages WHERE id=:id");
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    $query->bindParam(":title", $title, PDO::PARAM_STR);
    $query->bindParam(":author", $author, PDO::PARAM_STR);
    $query->bindParam(":pages", $pages, PDO::PARAM_INT);
    $query->execute();

    // Redirect user to dashboard.php after updating the book
    header('Location: dashboard.php');
    exit;
}

// Process adding a book
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $pages = $_POST['pages'];

    $query = $conn->prepare("INSERT INTO books (title, author, pages) VALUES (:title, :author, :pages)");
    $query->bindParam(":title", $title, PDO::PARAM_STR);
    $query->bindParam(":author", $author, PDO::PARAM_STR);
    $query->bindParam(":pages", $pages, PDO::PARAM_INT);
    $query->execute();
}

// Fetch all books from the database
$query = $conn->prepare("SELECT * FROM books");
$query->execute();
$books = $query->fetchAll();


$query = $conn->prepare("SELECT MAX(id) FROM books");
$query->execute();
$maxID = $query->fetchColumn();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<form method="post" action="logout.php">
    <p id="books-read">Nous en sommes à : <?php echo $maxID; ?> livres !</p>
    <button type="submit">Disconnect</button>
</form>
<div class="message-container">
    <h1>Alors, où en sommes nous dans nos lectures ?</h1>
</div>

<?php if ($edit) { ?>
    <!-- Edit book form -->
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $book_to_edit['id']; ?>">
        <input type="text" name="title" placeholder="Title" value="<?php echo $book_to_edit['title']; ?>" required>
        <input type="text" name="author" placeholder="Author" value="<?php echo $book_to_edit['author']; ?>" required>
        <input type="number" name="pages" placeholder="Pages" value="<?php echo $book_to_edit['pages']; ?>" required>
        <input type="submit" name="update" value="Update Book">
    </form>
<?php } else { ?>
    <!-- Add book form -->
    <form method="post" action="">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" name="pages" placeholder="Pages" required>
        <input type="submit" name="submit" value="Add Book">
    </form>
<?php } ?>

<table>
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Pages</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($books as $book) { ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['pages']; ?></td>
            <td>
                <a href="dashboard.php?edit=<?php echo $book['id']; ?>">Update</a>
                <a href="delete.php?id=<?php echo $book['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
