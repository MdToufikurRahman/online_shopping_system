<?php
require_once "includes/db_config.php";
session_start();

/* Auth check */
if (!isset($_SESSION['email'])) {
  header("Location: index.php");
  exit;
}

/* Validate ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['error'] = "Invalid user ID.";
  header("Location: users.php");
  exit;
}

$id = (int) $_GET['id'];

/* Prevent self delete */
$stmt = $db->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($userEmail);
$stmt->fetch();
$stmt->close();

if ($userEmail === $_SESSION['email']) {
  $_SESSION['error'] = "You cannot delete your own account.";
  header("Location: users.php");
  exit;
}

/* Delete */
$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  $_SESSION['success'] = "User deleted successfully.";
} else {
  $_SESSION['error'] = "Failed to delete user.";
}

$stmt->close();
header("Location: users.php");
exit;
