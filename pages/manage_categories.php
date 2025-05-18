<?php
// Database connection (adjust credentials)
$conn = new mysqli("localhost", "username", "password", "database");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all categories
$result = $conn->query("SELECT * FROM service_categories");
$categories = $result->fetch_all(MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
      $stmt = $conn->prepare("INSERT INTO service_categories (name) VALUES (?)");
      $stmt->bind_param("s", $name);
      $stmt->execute();
      header("Location: manage_categories.php"); // Refresh to show changes
      exit();
    }
  }
  if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM service_categories WHERE id = $id");
    header("Location: manage_categories.php");
    exit();
  }
?>
