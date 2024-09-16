<?php
include "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE crud2 SET is_deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('location: index.php');
exit;
?>
