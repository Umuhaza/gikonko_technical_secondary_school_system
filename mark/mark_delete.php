<?php
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("DELETE FROM Marks WHERE Mark_Id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: marks.php");
exit;
